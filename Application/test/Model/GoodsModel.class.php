<?php
namespace test\Model;
use Think\Model;
class GoodsModel extends Model 
{
	protected $insertFields = array('goods_name','market_price','shop_price','is_on_sale','goods_desc','goods_number');
	protected $updateFields = array('id','goods_name','market_price','shop_price','is_on_sale','goods_desc','goods_number');
	protected $_validate = array(
		array('goods_name', 'require', '商品名称不能为空！', 1, 'regex', 3),
		array('goods_name', '1,100', '商品名称的值最长不能超过 100 个字符！', 1, 'length', 3),
		array('market_price', 'require', '商品市场价格不能为空！', 1, 'regex', 3),
		array('market_price', 'currency', '商品市场价格必须是一个数字！', 1, 'regex', 3),
		array('shop_price', 'require', '商品本店价格不能为空！', 1, 'regex', 3),
		array('shop_price', 'currency', '商品本店价格必须是一个数字！', 1, 'regex', 3),
		array('is_on_sale', '是,否', "是否上架的值只能是在 '是,否' 中的一个值！", 2, 'in', 3),
		array('goods_number', 'number', '库存量必须是一个整数！', 2, 'regex', 3),
	);
	public function search()
	{
		/**************************************** 搜索 ****************************************/
		$where = array();
		if($goods_name = I('get.goods_name'))
			$where['goods_name'] = array('like', "%$goods_name%");
		if($market_pricefrom = I('get.market_pricefrom'))
			$where['market_price'] = array('gt', "$market_pricefrom");
		if($market_priceto = I('get.market_priceto'))
			$where['market_price'] = array('lt', "$market_priceto");
		if($shop_pricefrom = I('get.shop_pricefrom'))
			$where['shop_price'] = array('gt', "$shop_pricefrom");
		if($shop_priceto = I('get.shop_priceto'))
			$where['shop_price'] = array('lt', "$shop_priceto");
		if(($is_on_sale = I('get.is_on_sale')) && in_array($is_on_sale, array('是','否')))
			$where['is_on_sale'] = array('eq', $is_on_sale);
		if($goods_desc = I('get.goods_desc'))
			$where['goods_desc'] = array('eq', $goods_desc);
		if($goods_number = I('get.goods_number'))
			$where['goods_number'] = array('eq', $goods_number);
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
		if(isset($_FILES['logo']) && $_FILES['logo']['error'] == 0)
		{
			$ret = uploadImage('logo', 'test', array(
				array(350, 350, 2),
				array(150, 150, 2),
				array(50, 50, 2),
			));
			if($ret['ok'] == 1)
			{
				$data['logo'] = $ret['images'][0];
				$data['big_logo'] = $ret['images'][1];
				$data['mid_logo'] = $ret['images'][2];
				$data['sm_logo'] = $ret['images'][3];
			}
			else 
			{
				$this->error = $ret['error'];
				return FALSE;
			}
		}
		if(isset($_FILES['sm_logo']) && $_FILES['sm_logo']['error'] == 0)
		{
			$ret = uploadImage('sm_logo', 'test', array(
				array(350, 350, 2),
				array(150, 150, 2),
				array(50, 50, 2),
			));
			if($ret['ok'] == 1)
			{
				$data['sm_logo'] = $ret['images'][0];
				$data['big_sm_logo'] = $ret['images'][1];
				$data['mid_sm_logo'] = $ret['images'][2];
				$data['sm_sm_logo'] = $ret['images'][3];
			}
			else 
			{
				$this->error = $ret['error'];
				return FALSE;
			}
		}
	}
	// 修改前
	protected function _before_update(&$data, $option)
	{
		if(isset($_FILES['logo']) && $_FILES['logo']['error'] == 0)
		{
			$ret = uploadImage('logo', 'test', array(
				array(350, 350, 2),
				array(150, 150, 2),
				array(50, 50, 2),
			));
			if($ret['ok'] == 1)
			{
				$data['logo'] = $ret['images'][0];
				$data['big_logo'] = $ret['images'][1];
				$data['mid_logo'] = $ret['images'][2];
				$data['sm_logo'] = $ret['images'][3];
			}
			else 
			{
				$this->error = $ret['error'];
				return FALSE;
			}
			deleteImage(array(I('post.old_logo')));
			deleteImage(array(I('post.old_big_logo')));
			deleteImage(array(I('post.old_mid_logo')));
			deleteImage(array(I('post.old_sm_logo')));
			
		}
		if(isset($_FILES['sm_logo']) && $_FILES['sm_logo']['error'] == 0)
		{
			$ret = uploadImage('sm_logo', 'test', array(
				array(350, 350, 2),
				array(150, 150, 2),
				array(50, 50, 2),
			));
			if($ret['ok'] == 1)
			{
				$data['sm_logo'] = $ret['images'][0];
				$data['big_sm_logo'] = $ret['images'][1];
				$data['mid_sm_logo'] = $ret['images'][2];
				$data['sm_sm_logo'] = $ret['images'][3];
			}
			else 
			{
				$this->error = $ret['error'];
				return FALSE;
			}
			deleteImage(array(I('post.old_sm_logo')));
			deleteImage(array(I('post.old_big_sm_logo')));
			deleteImage(array(I('post.old_mid_sm_logo')));
			deleteImage(array(I('post.old_sm_sm_logo')));
			
		}
	}
	// 删除前
	protected function _before_delete($option)
	{
		if(is_array($option['where']['id']))
		{
			$this->error = '不支持批量删除';
			return FALSE;
		}
		$images = $this->field('logo,big_logo,mid_logo,sm_logo')->find($option['where']['id']);
		deleteImage($images);
		$images = $this->field('sm_logo,big_sm_logo,mid_sm_logo,sm_sm_logo')->find($option['where']['id']);
		deleteImage($images);
	}
	/************************************ 其他方法 ********************************************/
}