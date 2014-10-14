<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo $page_title;?></title>

	<style type="text/css">

	::selection{ background-color: #E13300; color: white; }
	::moz-selection{ background-color: #E13300; color: white; }
	::webkit-selection{ background-color: #E13300; color: white; }

	body {background-color: #fff;margin: 40px;font: 13px/20px normal Helvetica, Arial, sans-serif;color: #4F5155;}
	a {color: #003399;background-color: transparent;font-weight: normal;}
	h1 {color: #444;background-color: transparent;border-bottom: 1px solid #D0D0D0;font-size: 19px;font-weight: normal;margin: 0 0 14px 0;padding: 14px 15px 10px 15px;}
	code {font-family: Consolas, Monaco, Courier New, Courier, monospace;font-size: 12px;background-color: #f9f9f9;border: 1px solid #D0D0D0;color: #002166;display: block;margin: 14px 0 14px 0;padding: 12px 10px 12px 10px;}
	#body{max-width:976px;margin:auto}
        #body h2 {margin-left: 10px;}
        #body p {margin-left: 50px;}
	p.footer{text-align: right;font-size: 11px;border-top: 1px solid #D0D0D0;line-height: 32px;padding: 0 10px 0 10px;margin: 20px 0 0 0;}
	#container{margin: 10px;border: 1px solid #D0D0D0;-webkit-box-shadow: 0 0 8px #D0D0D0;}
        .agreement_text {width: 960px;height: 300px;border: 1px solid #DDD;overflow-y: scroll;margin: auto;padding:5px;}
        #installation_form {padding: 10px;text-align: right;}
        #installation_form input {border: none;cursor: pointer;padding: 8px 19px;font-size: 16px;border-radius: 5px;color: #fff;background-color: #0B9E2E;}
        #installation_form .btn-gray {background-color: gray;}
	</style>
</head>
<body>

<div id="container">
	<h1>MobGameServ 安装程序</h1>

	<div id="body">
                <h2><?php echo $page_title;?></h2>
                <?php if ($settings_file_writable):?>
                        <p>文件：<?php echo $settings_file;?> ······ <span style="color:green;font-weight: bold;">可写</span></p>
                <?php else:?>
                        <p>文件：<?php echo $settings_file;?> ······ <span style="color:red;font-weight: bold;">不可写</span></p>
                <?php endif;?>
                <?php foreach ($db_driver_supported as $key => $value):?>
                        <p>PHP 扩展：<?php echo $key;?> ······ 
                        <?php if ($value):?>
                                <span style="color:green;font-weight: bold;">可用</span></p>
                        <?php else:?>
                                <span style="color:red;font-weight: bold;">不可用</span></p>
                        <?php endif;?>
                <?php endforeach;?>
                <form id="installation_form" method="post" action="<?php echo $form_action;?>">
                        <input type="hidden" name="step" value="2" />
                        <input type="button" onclick="javascript:window.location.reload();return false;" class="btn-gray" onclick="" value="刷新" />
                        <input type="submit" name="agreement" value="下一步" >
                </form>
	</div>
</div>
<div style="text-align: right;padding: 10px;"><img src="../../statics/img/powered-by-AnySDK.png"></div>
</body>
</html>