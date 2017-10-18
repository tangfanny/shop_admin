<?php
	namespace Goods\Model;
	use		Think\Model;
	class GoodsTypeModel extends Model{
		protected $insertFields = array('type_name');
		protected $updateFields = array('id','type_name');
		
		protected $_validate = array(
			array('type_name','require','类型名称不能为空！',1,'regex',1)
		);
		//翻页
		public function search(){
			//取出总的记录数
			$count = $this->count();
			//生成翻页类的对象
			$page = new \Think\Page($count,4);			
			//配置翻页的样式
			$page->setConfig('prev','上一页');
			$page->setConfig('next','下一页');
			//生成翻页的字符串
			$PageStr = $page->show();
			$data = $this->field('a.*,COUNT(b.id) attr_count')->alias('a')->
			join('LEFT JOIN jd_attribute b ON a.id=b.type_id')
			->limit($page->firstRow.','.$page->listRows)->group('a.id')->select();
			return array(
				'data'=>$data,
				'page'=>$PageStr
			);
					}
		protected function _before_delete($options){
			//把类型下的属性也删除
			$model = M('Attribute');
			$model->where('type_id='.$options['where']['id'])->delete();
		}
	}

	
	