<?php
return array(
	'with_privileges' => true,
	'tableName' => 'jd_goods',
	'tableCnName' => '商品',
	'moduleName' => 'test',
	'moduleCnName' => '测试',
	'digui' => 0,
	'diguiName' => '',
	'pk' => 'id',
	'insertFields' => "array('goods_name','market_price','shop_price','is_on_sale','goods_desc','goods_number')",
	'updateFields' => "array('id','goods_name','market_price','shop_price','is_on_sale','goods_desc','goods_number')",
	'validate' => "
		array('goods_name', 'require', '商品名称不能为空！', 1, 'regex', 3),
		array('goods_name', '1,100', '商品名称的值最长不能超过 100 个字符！', 1, 'length', 3),
		array('market_price', 'require', '商品市场价格不能为空！', 1, 'regex', 3),
		array('market_price', 'currency', '商品市场价格必须是一个数字！', 1, 'regex', 3),
		array('shop_price', 'require', '商品本店价格不能为空！', 1, 'regex', 3),
		array('shop_price', 'currency', '商品本店价格必须是一个数字！', 1, 'regex', 3),
		array('is_on_sale', '是,否', \"是否上架的值只能是在 '是,否' 中的一个值！\", 2, 'in', 3),
		array('goods_number', 'number', '库存量必须是一个整数！', 2, 'regex', 3),
	",
	'fields' => array(
		'logo' => array(
			'text' => '商品logo',
			'type' => 'file',
			'thumbs' => array(
				array(350, 350, 2),
				array(150, 150, 2),
				array(50, 50, 2),
			),
			'save_fields' => array('logo', 'big_logo', 'mid_logo', 'sm_logo'),
			'default' => '',
			'validate' => array(
				'maxlength' => 150,
			),
		),
		'sm_logo' => array(
			'text' => '商品小logo',
			'type' => 'file',
			'thumbs' => array(
				array(350, 350, 2),
				array(150, 150, 2),
				array(50, 50, 2),
			),
			'save_fields' => array('sm_logo', 'big_sm_logo', 'mid_sm_logo', 'sm_sm_logo'),
			'default' => '',
			'validate' => array(
				'maxlength' => 150,
			),
		),
		'goods_name' => array(
			'text' => '商品名称',
			'type' => 'text',
			'default' => '',
			'validate' => array(
				'required' => TRUE,
				'maxlength' => 100,
			),
		),
		'market_price' => array(
			'text' => '商品市场价格',
			'type' => 'text',
			'default' => '',
			'validate' => array(
				'required' => TRUE,
				'number' => TRUE,
			),
		),
		'shop_price' => array(
			'text' => '商品本店价格',
			'type' => 'text',
			'default' => '',
			'validate' => array(
				'required' => TRUE,
				'number' => TRUE,
			),
		),
		'is_on_sale' => array(
			'text' => '是否上架',
			'type' => 'radio',
			'values' => array(
				'是' => '是',
				'否' => '否',
			),
			'default' => '是',
			'validate' => array(
				'required' => TRUE,
			),
		),
		'goods_desc' => array(
			'text' => '商品描述',
			'type' => 'html',
			'default' => '',
			'validate' => array(
			),
		),
		'goods_number' => array(
			'text' => '库存量',
			'type' => 'text',
			'default' => '0',
			'validate' => array(
				'digits' => TRUE,
			),
		),
	),
	'search' => array(
		array('goods_name', 'normal', '', 'like', '商品名称'),
		array('market_price', 'between', 'market_pricefrom,market_priceto', '', '商品市场价格'),
		array('shop_price', 'between', 'shop_pricefrom,shop_priceto', '', '商品本店价格'),
		array('is_on_sale', 'in', '是,否', '', '是否上架'),
		array('goods_desc', 'normal', '', 'eq', '商品描述'),
		array('goods_number', 'normal', '', 'eq', '库存量'),
	),
);