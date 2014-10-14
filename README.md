miniGameServer目录结构（基于 PHP CodeIgniter 框架，此处只说明不属于 CI 框架的文件）
============================================================

    application
      +-- config
      |     +-- settings.php            保存安装过程中填写的配置参数，需要写权限，使用过程中可手动修改错误的参数
      +-- controllers
      |     +-- api                     接口相关的控制器
      |     |     +-- payment.php           接收支付通知接口，订单查询接口
      |     |     +-- user.php              登录验证转发接口
      |     +-- install.php             安装程序
      +-- core
      |     +-- MY_Controller.php       预留的控制器父类
      |     +-- MY_Model.php            预留的模型父类
      +-- helpers
      |     +-- options_helper.php      选项辅助函数
      +-- libraries
      |     +-- http_request.php        http请求库
      +-- models
      |     +-- options_mdl.php         基于数据库的kv配置
      |     +-- pay_notify_mdl.php      支付通知接口日志记录
      +-- views
            +-- install                 安装程序页面视图文件

    web-conf
      +-- nginx.conf                    nginx配置示例

    .htaccess                           apache url 重写示例
    index.php
    robots.txt
    license.txt

运行环境需求
=========
    PHP 版本 5.1.6 或更新；要求安装curl，mysql扩展
    当前支持的数据库为：MySQL 4.1 +
