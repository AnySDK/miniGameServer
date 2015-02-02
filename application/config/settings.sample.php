<?php
return array (
  'desc' => 'app_key 和 app_secret 用来验证游戏客户端和服务端通信的，请记下此参数，您的游戏查询订单结果时需用此参数进行签名。',
  'install.lock' => false,
  'db_config' => 
  array (
    'hostname' => 'localhost',  // 数据库主机
    'username' => 'root',       // 数据库用户名
    'password' => '',           // 数据库密码
    'database' => '',           // 数据库名
    'dbdriver' => 'mysql',
    'dbprefix' => '',
    'table_prefix' => 'anysdk_', // 数据表前缀
    'pconnect' => false,
    'db_debug' => false,
    'cache_on' => false,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'autoinit' => true,
    'stricton' => false,
  ),
  'use_counter'         => true,                // 统计计数器
  'cid'                 => 'c_1410161936_663',  // 随机生成的客户代码
  'anysdk_pay_key'      => '',                  // anysdk 分配的 private key
  'anysdk_enhanced_key' => '',                  // anysdk 分配的 增强密钥
  'anysdk_login_url'    => 'http://oauth.anysdk.com/api/User/LoginOauth/',
  'app_key'             => '',                  // 安装过程生成的app_key
  'app_secret'          => '',                  // 安装过程生成的app_secret
);