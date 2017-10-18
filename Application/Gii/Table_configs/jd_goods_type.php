<?php
return array(
	'with_privileges' => true,
	'tableName' => 'jd_goods_type',
	'tableCnName' => '商品类型表',
	'moduleName' => 'test',
	'moduleCnName' => '测试',
	'digui' => 0,
	'diguiName' => '',
	'pk' => 'id',
	'insertFields' => "array('type_name')",
	'updateFields' => "array('id','type_name')",
	'validate' => "
		array('type_name', 'require', '商品类型名称不能为空！', 1, 'regex', 3),
		array('type_name', '1,55', '商品类型名称的值最长不能超过 55 个字符！', 1, 'length', 3),
	",
	'fields' => array(
		'type_name' => array(
			'text' => '商品类型名称',
			'type' => 'text',
			'default' => '',
			'validate' => array(
				'required' => TRUE,
				'maxlength' => 55,
			),
		),
	),
	'search' => array(
		array('type_name', 'normal', '', 'like', '商品类型名称'),
	),
);