<?php
return array(
	'with_privileges' => TRUE,
	'tableName' => 'jd_member_level',
	'tableCnName' => '会员级别表',
	'moduleName' => 'Member',
	'moduleCnName' => '会员级别',
	'digui' => 0,
	'diguiName' => '',
	'pk' => 'id',
	'insertFields' => "array('level_name','num_bottom','num_top')",
	'updateFields' => "array('id','level_name','num_bottom','num_top')",
	'validate' => "
		array('level_name', 'require', '属性名称不能为空！', 1, 'regex', 3),
		array('level_name', '1,32', '属性名称的值最长不能超过 32 个字符！', 1, 'length', 3),
		array('num_bottom', 'require', '积分上限不能为空！', 1, 'regex', 3),
		array('num_bottom', 'number', '积分上限必须是一个整数！', 1, 'regex', 3),
		array('num_top', 'require', '积分下限不能为空！', 1, 'regex', 3),
		array('num_top', 'number', '积分下限必须是一个整数！', 1, 'regex', 3),
		array('level_name', '', '属性名称的值已经存在，不能重复添加！', 1, 'unique', 3),
	",
	'fields' => array(
		'level_name' => array(
			'text' => '属性名称',
			'type' => 'text',
			'default' => '',
			'validate' => array(
				'required' => TRUE,
				'maxlength' => 32,
				'unique' => TRUE,
			),
		),
		'num_bottom' => array(
			'text' => '积分上限',
			'type' => 'text',
			'default' => '',
			'validate' => array(
				'required' => TRUE,
				'digits' => TRUE,
			),
		),
		'num_top' => array(
			'text' => '积分下限',
			'type' => 'text',
			'default' => '',
			'validate' => array(
				'required' => TRUE,
				'digits' => TRUE,
			),
		),
	),
	'search' => array(
		array('level_name', 'normal', '', 'like', '属性名称'),
		array('num_bottom', 'normal', '', 'eq', '积分上限'),
		array('num_top', 'normal', '', 'eq', '积分下限'),
	),
);