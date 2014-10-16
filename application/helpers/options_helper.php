<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * options_mdl 模型的快速访问接口, 包括设置选项值和获取选项值
 * 
 * @param type $key
 * @param type $value
 * @return type
 */
function option () {
        $param_num = func_num_args();
        
        if ($param_num == 1) {
                $key = func_get_arg(0);
        } else if ($param_num ==2) {
                $key = func_get_arg(0);
                $value = func_get_arg(1);
        } else {
                return false;
        }
        
        if (empty($key)) {
                return false;
        }
        
        $CI =& get_instance();
        $CI->load->model('options_mdl');
        $option = $CI->options_mdl->getByName($key);
        
        // 设置
        if (isset($value)) {
                if (empty($option)) {
                        $insert = array(
                            'name' => $key,
                            'value' => $value
                        );
                        $CI->options_mdl->add($insert);
                } else {
                        $update = array(
                            'value' => $value
                        );
                        $CI->options_mdl->editById($option['id'], $update);
                }
                
        // 获取
        } else {
                return $option['value'];
        }
}

/**
 * 类似 option，不同的是此函数的键值数据存储在配置文件里面
 * 配置文件路径：application/config/settings.php
 * 
 * @staticvar string $settings
 * @param type $name
 * @return mixed 
 */
function settings ($name = '') {
        $settings_file = FCPATH . APPPATH . 'config/settings.php';
        $settings_file_sample = FCPATH . APPPATH . 'config/settings.sample.php';
        $config_dir = FCPATH . APPPATH . 'config/';

        if (!file_exists($settings_file) && is_writable($config_dir)) {
                @copy($settings_file_sample, $settings_file);
        }
        
        // 清空文件状态缓存，对于 redirect 跳转之后配置还保持老配置的情况，这个操作似乎没用
        clearstatcache(true);
        if (file_exists($settings_file)) {
                $settings = include $settings_file;
        } else {
                $settings = include $settings_file_sample;
        }
        
        if (empty($name)) {
                return $settings;
        }
        
        return $settings[$name];
}