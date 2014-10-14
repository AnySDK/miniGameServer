<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>单机游戏服务端 - 测试页面</title>

	<style type="text/css">

	::selection{ background-color: #E13300; color: white; }
	::moz-selection{ background-color: #E13300; color: white; }
	::webkit-selection{ background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body{
		margin: 0 15px 0 15px;
                padding-bottom: 10px;
	}
	
	p.footer{
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}
	
	#container{
		margin: 10px;
		border: 1px solid #D0D0D0;
		-webkit-box-shadow: 0 0 8px #D0D0D0;
	}
        
        iframe{
                margin-top: 20px;
                width:  960px;
                height: 200px;
                border: #DDD solid 1px;
        }
        
        .stm{
                color:#888;
        }
	</style>
</head>
<body>

<div id="container">
	<h1>用户登录接口!</h1>

	<div id="body">
                <form action="/api/user/login" method="post" target="_target1">
                        <input type="text" name="channel" placeholder="渠道标识" value="simsdk" />
                        <input type="text" name="uapi_key" placeholder="uapi_key" value="129F6698-8E11-3222-5C8F-B28D27E1FF36" />
                        <input type="text" name="uapi_secret" placeholder="uapi_secret" value="973f5803d2a9142a3257e228e2f64362" />
                        <input type="text" name="private_key" placeholder="private_key" value="3ABC78255C696423C70B6467D8F97C3B" />
                        <input type="text" name="ext1" placeholder="ext1" value="" />
                        <input type="text" name="ext2" placeholder="ext2" value="1025" />
                        <input type="text" name="ext3" placeholder="ext3" value="" />
                        <input type="text" name="ext4" placeholder="ext4" value="" />
                        <input type="text" name="ext5" placeholder="ext5" value="" />
                        <input type="submit" value="用户登录" /><span class="stm">&nbsp;API: /api/user/login</span>
                </form>
                <p>先<a href="http://sim.qudao.local/?CFF3FC15FBF182A225E970DA2CEC7F6E=abc123" target="_blank">去Sim渠道登录</a>  帐号：lbzqh156 密码：123456</p>
                <iframe name="_target1" id="_target1"></iframe>
	</div>
</div>

<div id="container">
	<h1>用户登录接口!</h1>
        
	<div id="body">
                <p>支付回调地址：<?php echo site_url();?>api/payment/callback</p>
        </div>
</div>

<div id="container">
	<h1>查询支付结果!</h1>

	<div id="body">
                <form action="/api/payment/check_order" method="post" target="_blank">
                        <input type="text" name="order_id" placeholder="AnySDK 订单号" />
                        <input type="text" name="app_key" placeholder="app_key" />
                        <input type="text" name="time" placeholder="时间" value="<?php echo date('Y-m-d H:i:s'); ?>"/>
                        <input type="text" name="ver" placeholder="1" value="1" />
                        <input type="text" name="sign" placeholder="签名" />
                        <input type="submit" value="查询订单" /><span class="stm">&nbsp;API: /api/payment/check_order</span>
                </form>
                <iframe name="_target3" id="_target3"></iframe>
	</div>
</div>

</body>
</html>