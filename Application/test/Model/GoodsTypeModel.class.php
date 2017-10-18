<?php
namespace test\Model;
use Think\Model;
class GoodsTypeModel extends Model 
{
	protected $insertFields = array('type_name');
	protected $updateFields = array('id','type_name');
	protected $_validate = array(
		array('type_name', 'require', '商品类型名称不能为空！', 1, 'regex', 3),
		array('type_name', '1,55', '商品类型名称的值最长不能超过 55 个字符！', 1, 'length', 3),
	);
	public function search()
	{
		/**************************************** 搜索 ****************************************/
		$where = array();
		if($type_name = I('get.type_name'))
			$where['type_name'] = array('like', "%$type_name%");
		/****************************************** 排序 ***********************************/
		$orderBy = 'a.id';
		$orderWay = 'DESC';
		if($odby = I('get.odby'))
		{
			if($odby == 'id_asc')
			{
				$orderBy = 'a.id';
				$orderWay = 'ASC';
			}
			if($odby == 'id_desc')
			{
				$orderBy = 'a.id';
				$orderWay = 'DESC';
			}
		}
		/************************************* 翻页 ****************************************/
		$count = $this->alias('a')->where($where)->count();
		$page = new \Think\Page($count,4);
		// 配置翻页的样式
		$page->setConfig('prev', '上一页');
		$page->setConfig('next', '下一页');
		$data['page'] = $page->show();
		/************************************** 取数据 ******************************************/
		$data['data'] = $this->alias('a')->where($where)->group('a.id')->order("$orderBy $orderWay")->limit($page->firstRow.','.$page->listRows)->select();
		return $data;
	}
	// 添加前
	protected function _before_insert(&$data, $option)
	{
	}
	// 修改前
	protected function _before_update(&$data, $option)
	{
	}
	// 删除前
	protected function _before_delete($option)
	{
		if(is_array($option['where']['id']))
		{
			$this->error = '不支持批量删除';
			return FALSE;
		}
	}
	/************************************ 其他方法 ********************************************/
}