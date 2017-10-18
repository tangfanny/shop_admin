<?php
namespace Member\Model;
use Think\Model;
class MemberLevelModel extends Model 
{
	protected $insertFields = array('level_name','num_bottom','num_top');
	protected $updateFields = array('id','level_name','num_bottom','num_top');
	protected $_validate = array(
		array('level_name', 'require', '属性名称不能为空！', 1, 'regex', 3),
		array('level_name', '1,32', '属性名称的值最长不能超过 32 个字符！', 1, 'length', 3),
		array('num_bottom', 'require', '积分上限不能为空！', 1, 'regex', 3),
		array('num_bottom', 'number', '积分上限必须是一个整数！', 1, 'regex', 3),
		array('num_top', 'require', '积分下限不能为空！', 1, 'regex', 3),
		array('num_top', 'number', '积分下限必须是一个整数！', 1, 'regex', 3),
		array('level_name', '', '属性名称的值已经存在，不能重复添加！', 1, 'unique', 3),
	);
	public function search()
	{
		/**************************************** 搜索 ****************************************/
		$where = array();
		if($level_name = I('get.level_name'))
			$where['level_name'] = array('like', "%$level_name%");
		if($num_bottom = I('get.num_bottom'))
			$where['num_bottom'] = array('eq', $num_bottom);
		if($num_top = I('get.num_top'))
			$where['num_top'] = array('eq', $num_top);
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