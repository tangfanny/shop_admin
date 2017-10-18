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
    <span class="action-span"><a href="<?php echo U('add?type_id='.I('get.type_id'));?>">添加属性</a></span>
    <span class="action-span1"><a href="#">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 属性列表 </span>
    <div style="clear:both"></div>
</h1>
<div class="form-div">
</div>
<p>
	当前类型为：
<select onchange="location.href='+U('lst&type_id='+this.value)';">
<?php  $type_id = I('get.type_id'); foreach($tyData as $k=>$v): if($type_id == $v['id']) $select = 'selected = "selected"'; else $select = ''; ?>
	<option <?php echo $select; ?>  value="<?php echo $v['id']; ?>"><?php echo $v['type_name']; ?></option>
	<?php endforeach; ?>
	</select>
</p>
<form method="post" action="" name="listForm">
    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th>属性名称</th>
                <th>属性类型</th>
                <th>属性可选值</th>
                <th>操作</th>
            </tr>
			<?php foreach ($data as $k=>$v):?>
            <tr>
                <td class="first-cell">
                    <span><?php echo $v['attr_name'] ;?></span>
                </td>
                <td class="first-cell">
                    <span><?php echo $v['attr_type'] ;?></span>
                </td>
                <td class="first-cell">
                    <span><?php echo $v['attr_option_values'] ;?></span>
                </td>
				<td>
                <a  href="<?php echo U('save?id='.$v['id'].'&p='.I('get.p',1)).'&type_id='.I('get.type_id');?>" title="编辑"><img src="/Public/Images/icon_edit.gif" width="16" height="16" border="0" /></a>
                <a  onclick="return confirm('确定要删除么？');" href="<?php echo U('delete?id='.$v['id']);?>" title="删除"><img src="/Public/Images/icon_drop.gif" width="16" height="16" border="0" /></a>
                <a href="__GROUP__/Goods/goodsTrash?goods_id=<<?php echo ($val["goods_id"]); ?>>" onclick="" title="回收站"><img src="/Public/Images/icon_trash.gif" width="16" height="16" border="0" /></a></td>

            </tr>
			<?php endForeach;?>
           
        </table>
    </div>
	
</form>
 <tr>
                <?php echo $page;?>
            </tr>

<div id="footer">
共执行 9 个查询，用时 0.025161 秒，Gzip 已禁用，内存占用 3.258 MB<br />
版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>
</body>
</html>