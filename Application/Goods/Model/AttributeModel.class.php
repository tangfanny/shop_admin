<?php
	namespace Goods\Model;
	use       Think\Model;
	class  AttributeModel extends Model{
		public $insertFields = array('type_id','attr_name','attr_type','attr_option_values');
		public $updateFields = array('id','type_id','attr_name','attr_type','attr_option_values');
		public $_validate = array(
			array('type_id','/^\d+$/','type_id必须是一个整数',1),
			array('attr_type','require','属性类型不能为空！',1,'regex',1),
			array('attr_type',array('唯一','单选'),'属性类型必须是"唯一""单选"两个值',1,'in'),
			array('attr_name','require','属性名不能为空',1,'regex',1),
		);
		//翻页
		public function search(){
			//根据类型id取出类型下的属性
			$type_id = I('get.type_id');
			$where['type_id'] = array('eq',$type_id);
			//取出总的记录数
			$count = $this->count();
			//生成翻页类的对象
			$page = new \Think\Page($count,4);
			//配置翻页的样式
			$page->setConfig('prev','上一页');
			$page->setConfig('next','下一页');
			//生成翻页的字符串
			$PageStr = $page->show();
			$data = $this->where($where)->limit($page->firstRow.','.$page->listRows)->select();
			return array(
					'data'=>$data,
					'page'=>$PageStr
			);
		}
		protected function _before_insert(&$data, $options){
			if($data['attr_option_values'])
				$data['attr_option_values'] = str_replace('，',',',$data['attr_option_values']);
		}
		protected function _before_update(&$data, $options)
		{
			if($data['attr_option_values'])
				$data['attr_option_values'] = str_replace('，',',',$data['attr_option_values']);
		}
	}

	
	
	
	