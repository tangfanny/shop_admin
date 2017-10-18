<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - <?php echo $title ;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/Public/Styles/general.css" rel="stylesheet" type="text/css" />
<link href="/Public/Styles/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/Public/JS/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/Public/JS/jquery.validate.min.js"></script>
<script type="text/javascript" src="/Public/JS/validate_zh_cn.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/Js/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/Js/ueditor/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="/Public/Js/ueditor/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/Js/tron.js"></script>
<script type="text/javascript">
	var URL_MODEL ="<?php echo C('URL_MODEL') ;?>";
</script>
<script type="text/javascript" charset="utf-8" src="/Public/Js/function.js"></script>
<style type="text/css">
	a.num{padding:5px; margin:5px; border:solid 1px #FFF;}
	span.current{color:blue; background:yellow; margin:5px; font-weight:blod; border:solid 1px;}
	input.error {border:1px solid #F00;}
	lable.error {color:#F00;}
</style>
</style>
</head>
<body>
	
<h1>
    <span class="action-span"><a href="<?php echo U('lst?p='.I('get.p',1));?>">管理员列表</a></span>
    <span class="action-span1"><a href="#">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 添加管理员 </span>
    <div style="clear:both"></div>
</h1>
<div class="main-div">
    <form name="goods_form" method="post" action="/Admin-Admin-save-id-9-p-1.html"  >
    	<input type='hidden' name="id" value="<?php echo $info['id'];?>" />
        <table cellspacing="1" cellpadding="3" width="100%">
            <tr>
                <td class="label">管理员名称</td>
                <td>
               <input type="text" name="username" value="<?php echo $info['username'];?>"/>
                </td>
            </tr>
            <tr>
                <td class="label">密码</td>
                <td>
               <input type="password" id="password" name="password" />
                </td>
            </tr>
            <tr>
                <td class="label">确认密码</td>
                <td>
               <input type="password"  name="cpassword"/>
                </td>
            </tr>
            <tr>
                <td class="label">选择角色</td>
                <td>
                	<?php foreach($data as $k=>$v): if(strpos(','.$roleId.',',','.$v['id'].',')!== FALSE) $check = 'checked="checked"'; else $check = ''; ?>
               <input type="checkbox"  <?php echo $check; ?> name="role_id[]" value="<?php echo $v['id']; ?>"/>
			   	<?php echo $v['role_name']; ?></br>
				<?php endforeach;?>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center"><br />
                    <input type="submit" class="button" value=" 确定修改 " />
                    <input type="reset" class="button" value=" 重置 " />
                </td>
            </tr>
        </table>
    </form>
</div>

<!--
	js客户端代码定义验证的规则
-->
<script type="text/javascript">
	$("form[name=goods_form]").validate({
		rules:{
			username:{
				// required:true 必须输入的字段
				required:true
				//remote:"<?php echo U('ajaxChkUserName'); ?>"
			}
		}
	});
	
</script>










<div id="footer">
共执行 9 个查询，用时 0.025161 秒，Gzip 已禁用，内存占用 3.258 MB<br />
版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>
</body>
</html>