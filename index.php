<?php
require "config.php";
require "function.php";
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<title><?php echo $config[ 'title'];?></title>
	<link rel="stylesheet" href="//s1.pstatp.com/cdn/expire-1-y/bootstrap/4.5.3/css/bootstrap.min.css">
	<style>
		.title{font-size:3.5rem;margin-top:2rem;margin-bottom:2rem;color:#dc3545}.content{margin-top:1rem;border:1px
		solid#ccc}.content:hover{border:1px solid#dc3545}.input-group-lg label{font-size:1.5rem;margin-top:1rem;margin-bottom:0;color:#666}.input-group-sm
		label{font-size:1.1rem;margin-top:1rem;margin-bottom:0;color:#666}.form-control{border:1px
		solid#ccc}.form-control-lg,.input-group-lg>.form-control,.input-group-lg>.input-group-append>.btn,.input-group-lg>.input-group-append>.input-group-text,.input-group-lg>.input-group-prepend>.btn,.input-group-lg>.input-group-prepend>.input-group-text{font-size:1.25rem;line-height:1.5;padding:.5rem
		1rem}.input-group>.form-control:not(:first-child){-moz-border-radius:.5rem;border-radius:.5rem}.form-control:focus{color:#495057;border-color:#80bdff;outline:0;background-color:#fff;-webkit-box-shadow:0
		0 0.2rem rgba(220,53,69,.1);-moz-box-shadow:0 0 0.2rem rgba(220,53,69,.1);box-shadow:0
		0 0.2rem rgba(220,53,69,.1)}button{font-weight:bold;margin:2rem}.friendLink{font-size:1.2rem;margin-top:2.5rem;margin-bottom:2.5rem}.friendLink
		a{text-decoration:none}.friendLink a:hover{text-decoration:underline;color:#dc3545}.qrcode{width:200px;height:200px;margin:1rem
		auto;padding-top:10px;border:1px solid#ccc;-moz-border-radius:5px;border-radius:5px;-moz-background-size:cover;background-size:cover}
	</style>
</head>

<body>
	<div class="container">
		<h1 class="title text-center">
			<?php echo $config[ 'title'];?>
		</h1>
		<br>
		<br>
		<br>
		<form id="main" action="" method="post">
			<div class="text-center">
				<div class="input-group input-group-lg">
					<input type="text" name="url" id="url" class="content form-control" placeholder="请输入需缩短的网址">
					<br>
				</div>
				<button id="submit" class="btn btn-lg btn-danger" type="button" name="create">
					生成短网址
				</button>
			</div>
		</form>
		<br>
		<br>
		<br>
		<div class="text-center">
			<h3 id="short_url">
				<a id="m_gbck"></a>
			</h3>
			<div class="qrcode" id="qrcode" style="display:none;">
			</div>
			<h3 class="text-warning" id="notice">
			</h3>
			<p class="friendLink">
				Copyright©2022 dwz.ge,All Rights Reserved
			</p>
		</div>
	</div>
	<script src="//s1.pstatp.com/cdn/expire-1-M/jquery/3.6.0/jquery.min.js"></script>
	<script src="//s1.pstatp.com/cdn/expire-1-M/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
	<script src="//s1.pstatp.com/cdn/expire-1-M/clipboard.js/1.6.1/clipboard.min.js"></script>
	<script>
	    $("#submit").click(function(){$("#short_url").css("display",'none');$('#qrcode').css("display",'none');var data=$("#main").serialize();$.ajax({url:"create.php",type:'POST',data:data,dataType:'json',success:function(data){if(data.code==200){$("#short_url").css("display",'block');$("#short_url").html("<a id=\"m_gbck\" data-clipboard-text=\""+data.shortUrl+"\">"+data.shortUrl+"</a>");$('#qrcode').css("display",'block');$('#qrcode').text('');$('#qrcode').qrcode({width:180,height:180,text:data.shortUrl})}$("#notice").text(data.message);btn=$("#m_gbck")[0];clipboard=new Clipboard(btn);clipboard.on("success",function(e){$("#short_url").html("<a href=\""+data.shortUrl+"\" target=\"_blank\">"+data.shortUrl+"</a>");$("#notice").text("短网址已复制，再点打开")})},error:function(e){$("#notice").text("服务器错误，请稍后再试")},})})
	</script>
</body>

</html>