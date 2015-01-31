<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * 安装界面
 */

class Install extends MY_Controller {
        
        private $_num_chinexe = array(
            '1' => '一',
            '2' => '二',
            '3' => '三',
            '4' => '四',
            '5' => '五'
        );
        
        /**
         * 所有步骤
         * @var Array 
         */
        private $_steps = array(
            '1' => array(
                'name' => '第一步：用户协议'
            ),
            '2' => array(
                'name' => '第二步 环境检测'
            ),
            '3' => array(
                'name' => '第三步 安装'
            ),
            '4' => array(
                'name' => '第四步 完成'
            )
        );
        
        /**
         * 当前进行的步骤，主要用于post处理
         * @var type 
         */
        private $_step = 1;
        
        public function __construct() {
                parent::__construct();
                
                $this->_step = $this->input->post('step');
        }
        
        /**
         * 安装界面
         */
        public function index () {
                redirect('install/step/1', 'location', 301);
        }
        
        /**
         * 步骤展示页面
         * @param type $step
         */
        public function step ($step = 1) {
                session_start();
                
                // 最后一步执行完成后
                if ($step > 4 || ($step !=4 && settings('install.lock') == true)) {
                        redirect('/', 'location', 301);
                }

                if ($this->input->post() && $this->agreement()) {
                        $method = 'step_post_' . $step;
                        if (method_exists($this, $method)) {
                                $this->$method();
                        }
                }

                $method = 'step_process_' . $step;
                if (method_exists($this, $method)) {
                        $v_data = $this->$method();
                }

                $v_data['form_action'] = site_url('install/step/' . $step);
                $v_data['page_title'] = $this->_steps[$step]['name'] . '（共' . $this->_num_chinexe[count($this->_steps)] . '步）';

                $this->load->view('install/step' . $step . '.php', $v_data);
        }
        
        /**
         * 判断是否同意用户协议
         * @return boolean
         */
        public function agreement () {
                
                if ($this->_step == 1) {
                        $agreement = $this->input->post('agreement');
                        
                        if ($agreement) {
                                $_SESSION['agreement'] = md5($agreement . $this->input->ip_address() . $this->input->user_agent());
                                $this->input->set_cookie('agreement', $_SESSION['agreement'], 0);
                        }
                } else {
                        $agreement_c = $this->input->cookie('agreement');
                        if (isset($_SESSION['agreement']) && $_SESSION['agreement'] == $agreement_c) {
                                $agreement = true;
                        } else {
                                $agreement = false;
                        }
                }
                
                return $agreement;
        }
        
        /**
         * 生成一个新的app_key
         */
        public function app_key_new () {
                if ($this->input->post('new') == 1) {
                        echo json_encode(array('app_key' => $this->_gen_app_key()));
                }
        }
        
        /**
         * 生成一个新的app_secret
         */
        public function app_secret_new () {
                if ($this->input->post('new') == 1) {
                        echo json_encode(array('app_secret' => $this->_gen_app_secret()));
                }
        }
        
        /**
         * step_process_num 一类的方法负责处理页面展示前的逻辑
         * @return type
         */
        private function step_process_1 () {
                return array();
        }
        
        private function step_process_2 () {
                
                // 检查数据库驱动是否存在
                $db_driver_supported = array(
                    'mysql' => false,
                    'pdo_mysql' => false,
                    'curl' => false
                );
                
                $extension_loaded = get_loaded_extensions();
                foreach ($db_driver_supported as $key => $loaded) {
                        if (in_array($key, $extension_loaded)) {
                                $db_driver_supported[$key] = true;
                        }
                }
                
                // 检查配置文件是否可写
                $settings_file = FCPATH.APPPATH . 'config' . DIRECTORY_SEPARATOR . 'settings.php';
                
                if (is_writable($settings_file)) {
                        $settings_file_writable = true;
                } else {
                        $settings_file_writable = false;
                }
                
                return array(
                    'db_driver_supported' => $db_driver_supported,
                    'settings_file_writable' => $settings_file_writable,
                    'settings_file' => $settings_file
                );
        }
        
        private function step_process_3 () {
                
                $install = array();
                if (isset($_SESSION['install_error'])) {
                        $install['error'] = $_SESSION['install_error'];
                        $install['info'] = $_SESSION['install_info'];
                        unset($_SESSION['install_error']);
                        unset($_SESSION['install_info']);
                } else {
                        $install['error'] = '';
                        $install['info'] = array(
                            'db_host'           => 'localhost',
                            'db_name'           => '',
                            'db_user'           => 'root',
                            'db_pass'           => '',
                            'table_prefix'      => 'anysdk_',
                            'anysdk_pay_key'    => '',
                            'anysdk_login_url'  => 'http://oauth.anysdk.com/api/User/LoginOauth/',
                            'app_key'           => $this->_gen_app_key(),
                            'app_secret'        => $this->_gen_app_secret()
                        );
                }
                
                return $install;
        }
        
        /**
         * step_post_num 一类的方法负责处理接收到的post请求
         */
        private function step_post_1 () {
                redirect('install/step/' . ($this->_step + 1));
        }
        
        private function step_post_2 () {
                redirect('install/step/' . ($this->_step + 1));
        }
        
        /**
         * 执行安装步骤
         * @return type
         */
        private function step_post_3 () {
                
                $settings_file = FCPATH.APPPATH . 'config' . DIRECTORY_SEPARATOR . 'settings.php';
                
                $setting_arr = settings();
                
                $db_host                = $this->input->post('db_host');
                $db_name                = $this->input->post('db_name');
                $db_user                = $this->input->post('db_user');
                $db_pass                = $this->input->post('db_pass');
                $table_prefix           = $this->input->post('table_prefix');
                $anysdk_pay_key         = $this->input->post('anysdk_pay_key');
                $anysdk_enhanced_key    = $this->input->post('anysdk_enhanced_key');
                $anysdk_login_url       = $this->input->post('anysdk_login_url');
                $app_key                = $this->input->post('app_key');
                $app_secret             = $this->input->post('app_secret');
                
                if (  empty($db_host) || empty($db_name)
                   || empty($db_user) || empty($db_pass)
                   || empty($anysdk_pay_key)) {
                        $_SESSION['install_error'] = '缺少必填字段';
                        $_SESSION['install_info'] = array(
                            'db_host'             => $db_host,
                            'db_name'             => $db_name,
                            'db_user'             => $db_user,
                            'db_pass'             => $db_pass,
                            'table_prefix'        => $table_prefix,
                            'anysdk_pay_key'      => $anysdk_pay_key,
                            'anysdk_enhanced_key' => $anysdk_enhanced_key,
                            'anysdk_login_url'    => $anysdk_login_url,
                            'app_key'             => $app_key,
                            'app_secret'          => $app_secret
                        );
                        return ;
                }
                
                $setting_arr['db_config']['hostname']     = $db_host;
                $setting_arr['db_config']['username']     = $db_user;
                $setting_arr['db_config']['password']     = $db_pass;
                $setting_arr['db_config']['database']     = $db_name;
                $setting_arr['db_config']['table_prefix'] = $table_prefix;
                $setting_arr['anysdk_pay_key']            = $anysdk_pay_key;
                $setting_arr['anysdk_enhanced_key']       = $anysdk_enhanced_key;
                $setting_arr['anysdk_login_url']          = $anysdk_login_url;
                $setting_arr['app_key']                   = $app_key;
                $setting_arr['app_secret']                = $app_secret;
                $setting_arr['cid']                       = 'c_' . date('ymdHi_') . rand(0,999);
                $setting_arr['install.lock']              = true;
                
                file_put_contents($settings_file, "<?php\nreturn " . var_export($setting_arr, true) . ';');
                
                $DB = $this->load->database($setting_arr['db_config'], TRUE);
                if (!is_resource ($DB->conn_id)) {
                        $_SESSION['install_error'] = '数据库连接失败，请检查您的参数是否正确';
                        $_SESSION['install_info'] = array(
                            'db_host'             => $db_host,
                            'db_name'             => $db_name,
                            'db_user'             => $db_user,
                            'db_pass'             => $db_pass,
                            'table_prefix'        => $table_prefix,
                            'anysdk_pay_key'      => $anysdk_pay_key,
                            'anysdk_enhanced_key' => $anysdk_enhanced_key,
                            'anysdk_login_url'    => $anysdk_login_url,
                            'app_key'             => $app_key,
                            'app_secret'          => $app_secret
                        );
                        return ;
                }
                
                $this->load->database($setting_arr['db_config']);
                
                $this->load->dbutil();
                $this->load->dbforge();
                
                if (!$this->dbutil->database_exists($db_name)) {
                        $_SESSION['install_error'] = '数据库不存在，您需要手动创建';
                        $_SESSION['install_info'] = array(
                            'db_host'             => $db_host,
                            'db_name'             => $db_name,
                            'db_user'             => $db_user,
                            'db_pass'             => $db_pass,
                            'table_prefix'        => $table_prefix,
                            'anysdk_pay_key'      => $anysdk_pay_key,
                            'anysdk_enhanced_key' => $anysdk_enhanced_key,
                            'anysdk_login_url'    => $anysdk_login_url,
                            'app_key'             => $app_key,
                            'app_secret'          => $app_secret
                        );
                        return ;
                }
                
                $this->db->query("DROP TABLE IF EXISTS `{$table_prefix}options`");
                $this->db->query("CREATE TABLE `{$table_prefix}options` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `value` varchar(8192) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name_index` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT=\"简单的 key => value 存储表\"");
                $this->db->query("DROP TABLE IF EXISTS `{$table_prefix}pay_notify`");
                $this->db->query("CREATE TABLE `{$table_prefix}pay_notify` (" . '`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` char(50) NOT NULL DEFAULT "" COMMENT "订单号，anysdk产生的订单号",
  `product_count` int(11) NOT NULL COMMENT "要购买商品数量",
  `amount` float NOT NULL COMMENT "支付金额，单位元，值根据不同渠道的要求可能为浮点类型",
  `pay_status` tinyint(1) NOT NULL COMMENT "支付状态，1为成功, 其他为失败",
  `pay_time` varchar(32) NOT NULL COMMENT "支付时间，YYYY-mm-dd HH:ii:ss格式",
  `order_type` mediumint(5) NOT NULL COMMENT "支付方式，详见支付渠道标识表",
  `source` text NOT NULL COMMENT "渠道服务器通知 AnySDK 时请求的参数",
  `user_id` varchar(50) NOT NULL COMMENT "用户id，用户系统的用户id",
  `game_user_id` varchar(50) NOT NULL COMMENT "游戏内用户id,支付时传入的Role_Id参数",
  `game_id` varchar(50) NOT NULL COMMENT "游戏id",
  `server_id` varchar(50) NOT NULL COMMENT "服务器id，支付时传入的server_id 参数",
  `product_id` varchar(50) NOT NULL COMMENT "商品id,支付时传入的product_id 参数",
  `product_name` varchar(50) NOT NULL COMMENT "商品名称，支付时传入的product_name 参数",
  `private_data` varchar(500) NOT NULL COMMENT "自定义参数，调用客户端支付函数时传入的EXT参数，透传给游戏服务器",
  `channel_number` varchar(16) NOT NULL COMMENT "渠道编号",
  `sign` varchar(128) NOT NULL COMMENT "签名串，验签参考签名算法",
  `time` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id_index` (`order_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8');
                
                $table_create_success = $this->db->table_exists($table_prefix . 'pay_notify');
                if (!$table_create_success) {
                        $_SESSION['install_error'] = '数据表 ' . $table_prefix . 'pay_notify' . ' 创建失败';
                        $_SESSION['install_info'] = array(
                            'db_host'             => $db_host,
                            'db_name'             => $db_name,
                            'db_user'             => $db_user,
                            'db_pass'             => $db_pass,
                            'table_prefix'        => $table_prefix,
                            'anysdk_pay_key'      => $anysdk_pay_key,
                            'anysdk_enhanced_key' => $anysdk_enhanced_key,
                            'anysdk_login_url'    => $anysdk_login_url,
                            'app_key'             => $app_key,
                            'app_secret'          => $app_secret
                        );
                        return ;
                }
                
                $this->kp_counter('i');
                
                redirect('install/step/' . ($this->_step + 1));
        }
        
        private function step_post_4 () {
                redirect('install/step/' . ($this->_step + 1));
        }
        
        private function _gen_app_key () {
                $this->load->helper('string');
                
                return random_string('alnum', 23);
        }
        
        private function _gen_app_secret () {
                $this->load->helper('string');
                
                return random_string('unique', 0);
        }
}