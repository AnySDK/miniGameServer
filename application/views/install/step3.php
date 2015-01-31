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
        #body .error {margin-left: 40px;padding: 10px;background-color: #f2dede;border: 1px solid #DBC4C4;border-radius: 4px;color: #a94442;}
        #body .hint{margin-left: 40px;}
        #body .need_star{color: red;font-weight: bold;line-height: 10px;vertical-align: -5px;font-size: 20px;}
	p.footer{text-align: right;font-size: 11px;border-top: 1px solid #D0D0D0;line-height: 32px;padding: 0 10px 0 10px;margin: 20px 0 0 0;}
	#container{margin: 10px;border: 1px solid #D0D0D0;-webkit-box-shadow: 0 0 8px #D0D0D0;}
        .agreement_text {width: 960px;height: 300px;border: 1px solid #DDD;overflow-y: scroll;margin: auto;padding:5px;}
        #installation_form {padding: 10px;text-align: right;}
        #installation_form .btn {border: none;cursor: pointer;padding: 8px 19px;font-size: 16px;border-radius: 5px;color: #fff;}
        #installation_form .btn-green {background-color: #0B9E2E;}
        #installation_form .btn-gray {background-color: gray;}
        #installation_form .form_body{text-align: left;margin: 0 30px 25px 30px;}
        #installation_form .form_body p{padding-left: 50px;}
        #installation_form .form_body h3{text-align: left;}
        #installation_form .form_body label{width:100px;display: inline-block;font-size: 14px;text-align: left;vertical-align: middle;}
        #installation_form .form_body input {width: 312px;height: 22px;line-height: 22px;padding: 3px;border:1px solid #B3B3B3;border-radius: 4px;}
        #installation_form .form_body span {padding-left: 10px;padding-top: 10px;display: inline-block;}
        #installation_form .form_body #app_key_shown{vertical-align: middle;padding: 0px;}
        #installation_form .form_body #app_key_new,#installation_form .form_body #app_secret_new {margin-left: 20px;vertical-align: middle;padding:0px;color: blue;cursor: pointer;}
        #installation_form .form_body .font-red{color:red;font-weight: bold;}
	</style>
</head>
<body>

<div id="container">
	<h1>MobGameServ 安装程序</h1>

	<div id="body">
                <h2><?php echo $page_title;?></h2>
                
                <p class="hint">带 <strong class="need_star"> * </strong> 的为必填参数</p>
                
                <?php if ($error):?>
                        <div class="error"><?php echo $error;?></div>
                <?php endif;?>
                
                <form id="installation_form" method="post" action="<?php echo $form_action;?>">
                        <div class="form_body">
                                
                                <h3>数据库连接配置</h3>
                                <p>
                                        <label for="db_host">数据库主机</label>
                                        <input type="text" name="db_host" id="db_host" placeholder="例如：localhost" value="<?php echo $info['db_host'];?>" />
                                        <strong class="need_star"> * </strong>
                                        <span></span>
                                </p>
                                <p>
                                        <label for="db_name">数据库名</label>
                                        <input type="text" name="db_name" id="db_name" placeholder="例如：game_db" value="<?php echo $info['db_name'];?>" />
                                        <strong class="need_star"> * </strong>
                                        <span>您需要手动新建数据库，或者填写您已有的数据库名</span>
                                </p>
                                <p>
                                        <label for="db_user">数据库用户名</label>
                                        <input type="text" name="db_user" id="db_user" placeholder="例如：root" value="<?php echo $info['db_user'];?>" />
                                        <strong class="need_star"> * </strong>
                                        <span></span>
                                </p>
                                <p>
                                        <label for="db_pass">数据库密码</label>
                                        <input type="text" name="db_pass" id="db_pass" placeholder="例如：123456" value="<?php echo $info['db_pass'];?>" />
                                        <strong class="need_star"> * </strong>
                                        <span></span>
                                </p>
                                <p>
                                        <label for="db_pass">数据库表前缀</label>
                                        <input type="text" name="table_prefix" id="table_prefix" placeholder="例如：anysdk_" value="<?php echo $info['table_prefix'];?>" />
                                        <strong class="need_star"> * </strong>
                                        <span>避免表名与您现存数据库中的其他表重名</span>
                                </p>
                                
                                <h3>AnySDK 参数配置</h3>
                                <p>您的服务器在接收 AnySDK 的支付通知的时候，需要使用此密钥验证签名。</p>
                                <p>
                                        <label for="anysdk_pay_key">支付密钥 PrivateKey</label>
                                        <input type="text" name="anysdk_pay_key" id="anysdk_pay_key" value="<?php echo $info['anysdk_pay_key'];?>" placeholder="例如：5DDBFE07DE4376609548036B42A9280E" />
                                        <strong class="need_star"> * </strong>
                                        <span>AnySDK 提供的 PrivateKey 参数</span>
                                </p>
                                <p>
                                        <label for="anysdk_enhanced_key">增强密钥</label>
                                        <input type="text" name="anysdk_enhanced_key" id="anysdk_enhanced_key" value="<?php echo $info['anysdk_enhanced_key'];?>" placeholder="例如：MwM2MYEwN4ZTdkZGZDIT2ODETdk" />
                                        <strong class="need_star"> * </strong>
                                        <span>AnySDK 提供的 增强密钥 参数</span>
                                </p>
                                <p>
                                        <label for="anysdk_login_url">登录验证地址</label>
                                        <input type="text" name="anysdk_login_url" id="anysdk_login_url" value="<?php echo $info['anysdk_login_url'];?>" placeholder="例如：http://oauth.anysdk.com/api/User/LoginOauth/" />
                                        <strong class="need_star"> * </strong>
                                        <span>如果您没有使用 AnySDK 企业版，请保持此值不变。</span>
                                </p>
                                
                                <h3>接口参数</h3>
                                <p>app_key用于您的游戏客户端和服务端交互的验证，<span class="font-red">请记下此参数</span>，您的游戏查询订单结果时需用此参数进行签名。</p>
                                <p>
                                        <label for="app_key">app_key:</label>
                                        <input type="hidden" name="app_key" id="app_key_input" value="<?php echo $info['app_key'];?>" />
                                        <span id="app_key_shown"><?php echo $info['app_key'];?></span><span id="app_key_new">换一个</span>
                                </p>
                                <p>
                                        <label for="app_secret">app_secret:</label>
                                        <input type="hidden" name="app_secret" id="app_secret_input" value="<?php echo $info['app_secret'];?>" />
                                        <span id="app_secret_shown"><?php echo $info['app_secret'];?></span><span id="app_secret_new">换一个</span>
                                </p>
                        </div>
                        
                        <input type="hidden" id="step_input" name="step" value="3" />
                        <input type="submit" onclick="javascript:window.history.back();return false;" class="btn btn-gray" onclick="" value="上一步" />
                        <input type="submit" name="agreement" class="btn btn-green" value="安装" >
                </form>
	</div>
</div>
<div style="text-align: right;padding: 10px;"><img src="../../statics/img/powered-by-AnySDK.png"></div>
<script src="<?php echo site_url();?>statics/js/jquery-1.11.1.min.js" type="text/javascript"></script>
<script type="text/javascript">
        $(document).ready(function(){
                $('#app_key_new').click(function(){
                        $.ajax({
                                url: '<?php echo site_url();?>install/app_key_new',
                                data: {new:1},
                                dataType: 'json',
                                type: 'post',
                                cache: false,
                                success: function(resp){
                                        if (resp.app_key) {
                                                $('input#app_key_input').val(resp.app_key);
                                                $('span#app_key_shown').html(resp.app_key);
                                        }
                                }
                        });
                });
                $('#app_secret_new').click(function(){
                        $.ajax({
                                url: '<?php echo site_url();?>install/app_secret_new',
                                data: {new:1},
                                dataType: 'json',
                                type: 'post',
                                cache: false,
                                success: function(resp){
                                        if (resp.app_secret) {
                                                $('input#app_secret_input').val(resp.app_secret);
                                                $('span#app_secret_shown').html(resp.app_secret);
                                        }
                                }
                        });
                });
        });
</script>


</body>
</html>