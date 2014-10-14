<?php

/* 
 * 存储“键值对”的数据表，提供 option 辅助函数进行快捷调用
 */
class Options_mdl extends MY_Model {
        
        /**
         * 表名
         */
        const TABLE = 'options';
        
        /**
         * 
         */
        public function __construct() {
                parent::__construct();
        }
        
        /**
         * 通过“键”获取值
         * 
         * @param type $name
         * @return String
         */
        public function getByName($name) {
                $this->db->where(array('name' => $name));
                $option = $this->db->get($this->_table_prefix . self::TABLE)->result_array();
                if (empty($option)) {
                        return null;
                } else {
                        return array_shift($option);
                }
        }
        
        /**
         * 通过id修改键值对
         * 
         * @param int $id
         * @param array $update
         * @return mixed 
         */
        public function editById ($id, Array $update) {
                if (empty($id) || empty($update)) {
                        return false;
                }
                
                $this->db->where(array('id' => $id));
                return $this->db->update($this->_table_prefix . self::TABLE, $update);
        }
        
        /**
         * 新增键值对
         * 
         * @param array $insert
         * @return boolean
         */
        public function add (Array $insert) {
                if (empty($insert)) {
                        return false;
                }
                $this->db->insert($this->_table_prefix . self::TABLE, $insert);
                return $this->db->insert_id();
        }
}