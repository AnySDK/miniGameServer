<?php

/* 
 * 支付相关接口，包括查询，支付通知回调
 */

class Payment extends MY_Controller {
        
        /**
         * 成功后返回给 AnySDK 的信息
         * @var type 
         */
        private $_returnSuccess = 'ok';
        
        /**
         * 失败后返回给 AnySDK 的信息
         * @var type 
         */
        private $_returnFailure = 'failed';
        
        public function __construct() {
                parent::__construct();
                
                $this->load->model('pay_notify_mdl');
        }
        
        /**
         * 接收支付结果通知，供AnySDK回调
         * 地址 api/payment/callback
         */
        public function callback () {
                // AnySDK 分配的 private_key
                $privateKey = settings(ANYSDK_PAY_KEY);
                
                $params = $_POST;
                foreach ($params as $key => $value) {
                        $params[$key] = $value;
                }
                
                if (!$this->_checkSign($params, $privateKey)) {
                        echo $this->_returnFailure . '_check_sign';
                        return;
                }
                
                // AnySDK 分配的 增强密钥
                $enhancedKey = settings(ANYSDK_ENHANCED_KEY);
                if (!$this->_checkEnhancedSign($params, $enhancedKey)) {
                        echo $this->_returnFailure . '_check_enhanced_sign';
                        return;
                }
                
                // todo: 在这里加入其他处理逻辑
                
                // 删除除下列字段的其他字段， 下列字段是 2014年7月31日，AnySDK 文档中所支持的字段
                // 以防游服部署了老的版本，然后AnySDK有新加字段之后导致游服处理出错
                $params_list = array(
                        'order_id',
                        'product_count',
                        'amount',
                        'pay_status',
                        'pay_time',
                        'user_id',
                        'order_type',
                        'game_user_id',
                        'game_id',
                        'server_id',
                        'product_name',
                        'product_id',
                        'private_data',
                        'channel_number',
                        'sign',
                        'source'
                );
                
                foreach ($params as $key => $value) {
                        if (!in_array($key, $params_list)) {
                                unset($params[$key]);
                        }
                }
                
                // 将记录写入数据库
                $insert = $params;
                $insert['time'] = time();
                $this->pay_notify_mdl->add($insert);
                
                // 先记日志后判断该返回的状态
                
                // 是否通知成功
                $feedback_order = false;
                $order_exists = $this->pay_notify_mdl->getSuccessfulNotifyByOrderId($params['order_id']);
                if ($order_exists) {
                        $feedback_order = true;
                }
                
                if ($feedback_order) {
                        echo $this->_returnSuccess;
                } else {
                        echo $this->_returnFailure . '_write_db';
                }
                
                $this->kp_counter('p');
        }
        
        /**
         * 查询订单状态，单机游戏调用
         * 接口地址：api/payment/check_order
         * 
         */
        public function check_order () {
                
                // 验证 app_key 和 app_secret
                $app_key = settings('app_key');
                $app_secret = settings('app_secret');
                
                $order_id = trim($this->input->post('order_id'));
                $time = trim($this->input->post('time'));
                $sign = trim($this->input->post('sign'));
                $ver  = trim($this->input->post('ver'));
                if (empty($ver)) {
                        $ver = 0;
                }

                $submit_app_key = trim($this->input->post('app_key'));

                if (empty($order_id)) {
                        echo json_encode(array('errno' => '101', 'errmsg' => 'order_id不能为空'));
                        return;
                }
                
                /**
                 * 若有填写app_key则需要验证签名
                 */
                if ($app_key) {
                        
                        if (empty($sign)) {
                                echo json_encode(array('errno' => '103', 'errmsg' => '缺少签名sign'));
                                return;
                        }
                        
                        if ($submit_app_key != $app_key) {
                                echo json_encode(array('errno' => '104', 'errmsg' => 'app_key无效'));
                                return;
                        }
                        
                        // 验证签名
                        $sign_local = md5($app_key.$order_id.$time);
                        
                        if ($sign_local != $sign) {
                                echo json_encode(array('errno' => '105', 'errmsg' => '签名sign无效'));
                                return;
                        }
                }
                
                $order = $this->pay_notify_mdl->getSuccessfulNotifyByOrderId($order_id);
                
                if (empty($order)) {
                        echo json_encode(array('errno' => '100', 'errmsg' => ' 订单不存在'));
                } else {
                        unset($order['id']);
                        unset($order['sign']);
                        unset($order['time']);
                        
                        // 生成订单信息签名
                        if ($ver >= 1) {
                                $order_sign = $this->order_sign($order, $app_secret);
                                echo json_encode(array('errno' => '0', 'errmsg' => '查询成功', 'sign' => strtolower($order_sign), 'data' => $order));
                        } else {
                                echo json_encode(array('errno' => '0', 'errmsg' => '查询成功', 'data' => $order));
                        }
                }
        }
        
        /**
         * 验签
         * @param array $data 接收到的所有请求参数数组，通过$_POST可以获得。注意data数据如果服务器没有自动解析，请做一次urldecode(参考rfc1738标准)处理
         * @param array $privateKey AnySDK分配的游戏privateKey
         * @return bool
         */
        private function _checkSign($data, $privateKey) {
                if (empty($data) || !isset($data['sign']) || empty($privateKey)) {
                        return false;
                }
                $sign = $data['sign'];
                $_sign = $this->_getSign($data, $privateKey);
                if ($_sign != $sign) {
                        return false;
                }
                return true;
        }

        /**
         * 计算签名
         * @param array $data
         * @param string $privateKey
         * @return string
         */
        private function _getSign($data, $privateKey) {
                //sign 不参与签名
                unset($data['sign']);
                //数组按key升序排序
                ksort($data);
                //将数组中的值不加任何分隔符合并成字符串
                $string = implode('', $data);
                //做一次md5并转换成小写，末尾追加游戏的privateKey，最后再次做md5并转换成小写
                return strtolower(md5(strtolower(md5($string)) . $privateKey));
        }
        
        /**
         * 对返回给客户端的订单数据做签名
         * @param type $order
         * @param type $app_secret
         */
        private function order_sign($order, $app_secret) {
                ksort($order);
                
                $sign_str = '';
                foreach ($order as $value) {
                        $sign_str .= $value;
                }
                
                $sign = md5($sign_str . $app_secret);
                
                return $sign;
        }
        
        private function _checkEnhancedSign ($data, $enhancedKey) {
                if (empty($data) || !isset($data['enhanced_sign']) || empty($enhancedKey)) {
                        return false;
                }
                $enhancedSign = $data['enhanced_sign'];
                //sign及enhanced_sign 不参与签名
                unset($data['enhanced_sign']);
                $_enhancedSign = $this->_getSign($data, $enhancedKey);
                if ($_enhancedSign != $enhancedSign) {
                        return false;
                }
                return true;
        }
}
