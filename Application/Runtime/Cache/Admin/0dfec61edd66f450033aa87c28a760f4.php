<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/Public/styles/general.css" rel="stylesheet" type="text/css" />
<link href="/Public/styles/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/Public/JS/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/Public/JS/jquery.validate.min.js"></script>
<script type="text/javascript" src="/Public/JS/validate_zh_cn.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/Js/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/Js/ueditor/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="/Public/Js/ueditor/lang/zh-cn/zh-cn.js"></script>
<style type="text/css">
body {
  color: white;
}
</style>

</head>
<body style="background:  #1AFD9C ">
<form method="post" action="/index.php/Admin-Login-login.html" name='pri_form'>
  <table cellspacing="0" cellpadding="0" style="margin-top: 100px" align="center">
  <tr>
    <td style="padding-left: 50px">
      <table>
      <tr>
        <td>管理员姓名：</td>
        <td><input type="text" name="username" /></td>
      </tr>
      <tr>
        <td>管理员密码：</td>
        <td><input type="password" name="password" /></td>
      </tr>
      <tr>
        <td>验证码：</td>
        <td><input type="text" name="checkcode" class="capital" /></td>
      </tr>
      <tr>
      <td colspan="2" align="right"><img src="<?php echo U('checkcodeImg');?>" alt="CAPTCHA" border="1" onclick= "this.src='<?php echo U('checkcodeImg');?>#'+Math.random();" style="cursor: pointer;" title="看不清？点击更换另一个验证码。" />
      </td>
      </tr>
      <tr><td>&nbsp;</td><td><input type="submit" value="进入管理中心" class="button" /></td></tr>
      </table>
    </td>
  </tr>
  </table>
</form>

</body>
</html>
<script type="text/javascript">
	$("form[name=pri_form]").validate({
		rules:{
			pri_name:{
				required:true
			},
			module_name:{
				required:true
			},
			controller_name:{
				required:true
			},
			action_name:{
				required:true
			}
		}
	});
</script>