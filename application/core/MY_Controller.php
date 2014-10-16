<?php

/** 
 * 控制器超类，此类为预留，便于以后做抽象
 */

class MY_Controller extends CI_Controller {
        
        public function __construct() {
                parent::__construct();
        }
        
        /**
         * 简单统计
         */
        
        protected function kp_counter ($key = 'i') {
                $use_counter = settings('use_counter');
                
                if (!$use_counter) {
                        return;
                }
                
                $keys = array(
                    'i' => '5150506090b093e7',
                    'u' => '81e0109010e0d348',
                    'p' => 'c1e060502050a338',
                );
                
                $ref = settings('cid');
                
                if (empty($ref)) {
                        $ref = 'c_1410161936_663';
                }
                
                $c_url = 'http://c.kp747.com/k.js?id=' . $keys[$key] . '&ref=' . $ref . '_v' . MGS_VERSION;
                
                $this->load->library('http_request');
                $this->http_request->get($c_url);
        }
}