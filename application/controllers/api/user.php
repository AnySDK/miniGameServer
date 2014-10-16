<?php

/* 
 * 用户登录验证相关接口
 */

class User extends MY_Controller {
        
        /**
         * anysdk统一登录地址
         * @var string
         */
        private $_loginCheckUrl = 'http://oauth.anysdk.com/api/User/LoginOauth/';

        public function __construct() {
                parent::__construct();
                
                $login_check_url = settings('anysdk_login_url');
                
                if ($login_check_url) {
                        $this->_loginCheckUrl = $login_check_url;
                }
        }
        
        /**
         * 登录验证转发接口
         */
        public function login () {
                $params = $_REQUEST;
                
                //检测必要参数
                if (!$this->parametersIsset($params)) {
                        echo 'parameter not complete';
                        exit;
                }
                
                //发送http请求
                $this->load->library('http_request');
                //这里建议使用post方式提交请求，避免客户端提交的参数再次被urlencode导致部分渠道token带有特殊符号验证失败
                $result = $this->http_request->post($this->_loginCheckUrl, $params);
                
                
                //@todo在这里处理游戏逻辑，在服务器注册用户信息等
                
                
                //返回示例： {"status":"ok","data":{--渠道服务器返回的信息--},"common":{"channel":"渠道标识","uid":"用户标识"},"ext":""}
                echo $result;
                
                $this->kp_counter('u');
        }
        
        /**
         * 检查 channel, uapi_key, uapi_secret 是否存在
         * 
         * @param type $params
         * @return boolean
         */
        private function parametersIsset($params) {
                if (!(isset($params['channel']) && isset($params['uapi_key']) && isset($params['uapi_secret']))) {
                        return false;
                }
                return TRUE;
        }
}