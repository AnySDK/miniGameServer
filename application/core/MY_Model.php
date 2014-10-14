<?php

/** 
 * 模型超类
 */
class MY_Model extends CI_Model {
        
        protected $_table_prefix = '';


        public function __construct() {
                parent::__construct();
                
                $db_config = settings('db_config');
                
                $this->_table_prefix = $db_config['table_prefix'];
                
                $this->load->database($db_config);
        }
        
}