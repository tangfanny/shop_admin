<?php
	namespace Goods\Model;
	use Think\Model;
	class GoodsModel extends  Model{
		//添加表单时只允许接收的字段
		protected $insertFields = array('goods_name','goods_number','market_price','shop_price','is_on_sale','goods_desc','type_id');
		//修改表单时只允许接收的字段
		protected $updateFields = array('id','goods_name','goods_number','market_price','shop_price','is_on_sale','goods_desc','type_id');
		// 定义控制器中create方法服务器端表单验证的规则
		// 第一个参数：表单中字段的名称
		// 第二个参数：搭配第五个参数使用，如：如果第五个参数是regex,第二个参数就是正则表达式
		// 第四个参数：有三个值：0（表单存在就验证-默认）；1（必须验证）；2（表单存在并且不为空时就验证）
		// 第五个参数：这个规则如何验证（regex是正则的默认验证）
		// 第六个参数：有三个值：1（添加时验证）；2（修改时验证）；3（任何时候都验证-默认）
		protected $_validate = array(
			array('goods_name','require','商品名称不能为空！',1,'regex',1),
			array('goods_name','1,100','商品名称必须是1-100个字段之间！',1,'length',1),
			array('market_price','require','商品市场价格能为空！',1,'regex'),
			array('market_price','currency','商品市场价格格式不正确！',1,'regex'),
			array('shop_price','require','本店价格不能为空！',1,'regex'),
			array('shop_price','currency','本店价格格式不正确！',1,'regex'),
			array('is_on_sale','require','必须选择是否 上架',1,'regex'),
			array('is_on_sale',array('是','否'),'是否上架必须为"是""否"两个值！',1,'in'),
			array('goods_number','/^\d+$/','库存量必须是一个大于0的整数',1,'regex'),
			array('goods_name','','商品名称已经存在',1,'unique'),
		);
		
		/**
		 * 在调用add方法时会自动调用的方法，再插入数据库之前自动被调用，如果返回false则添加失败
		 * $data:将要插入到数据库中的数据的数组
		 * @see \Think\Model::_before_insert()
		 */
		protected function _before_insert(&$data, $options){
			$data[' attr_option_values'] = str_replace('
					，', ',',$data['attr_option_values']);
			 //上传图片并生成缩略图，缩略图的形式是按比例缩放
				$result = uploads('logo','goods',array(
					array(150,150,1),
				));
				if($result['ok'] == 1){
					//如果上传成功，保存原图的数据信息到数据库
					$data['logo'] = $result['image'][0]; 
					//如果上传成功，保存缩略图的数据信息到数据库
					$data['sm_logo'] = $result['image'][1];
				}elseif($result['ok']==0){
					//如果失败，显示失败原因
					$error = $result['error'];
					$this->error = $error;
					return false;
				}
		}
		/***************钩子函数判断上传的多张图片是否上传并处理******************/
		protected function _after_insert(&$data, $options){
			//判断用户是否上传多张图片
			if(hasImg('pics')){
				$result = uploads('pics','Goods',array(
						array(150,150,1),
				),true);
				//如果上传失败
				if($result['ok'] == 0){
					$this->error=$result['error'];
					return false;
				}
				//如果上传成功就把图片存到数据库的商品相册中
				$picModel = M('GoodsPics');
				//循环每张图片插入到相册表中
				foreach ($result['image'] as $k=>$v ){
					$picModel->add(array(
						'pic'=>$v[0],
						'sm_pic'=>$v[1],
						'goods_id'=>$data['id']
					));
				}
			}
			
			/**
			 * 
			 * 处理商品属性的代码
			 * ***/
			$goodsAttr = I('post.goodsattr');
			$gaModel = M('GoodsAttr');
			foreach ($goodsAttr as $k=>$v){
				//如果这个属性是一个属性说明有多个值
				if(is_array($v)){
					foreach ($v as $k1=>$v1){
						$gaModel->add(array(
							'attr_id'=>$k,
							'attr_value'=>$v1,
							'goods_id'=>$data['id']
						));
					}
				}else{
					$gaModel->add(array(
						'attr_id'=>$k,
						'attr_value'=>$v,
						'goods_id'=>$data['id']
					));
				}
			}
		}
		/*** 搜索  分页    查询* ** **/
		public  function  search(){
			/**************搜索*****/
			//价格搜索
			$where = array();
			$sp = I('get.sp');
			$ep = I('get.ep');
			if($sp && $ep){
				$where['shop_price'] = array('between',array($sp,$ep));
			}elseif($sp)
				$where['shop_price'] = array('egt',$sp);
			elseif($ep)
				$where['shop_price'] = array('elt',$ep);
			//商品名称搜索
			if($gn = I('get.gn'))
				$where['goods_name'] = array('like','%'.$gn.'%');
			
			/****************分页******************/
			//取出总的记录数
			$total = $this->where($where)->count();
			//生成分页类的对象
			$page = new \Think\Page($total,4);

			//配置分页类的样式
			$page->setConfig('prev','上一页');
			$page->setConfig('next','下一页');
			//生成翻页的字符串
			$pageStr = $page->show();
			/************************排序****************/
			$orderby = 'id';
			$orderway = 'asc';
			if(($ob=I('get.ob')) && in_array($ob,array('id','shop_price')))
				$orderby = $ob;
			if(($ow = I('get.ow')) && in_array($ow,array('asc','desc')));
				$orderway = $ow;	
						
			//取出当前页的数据
			$data = $this->field('a.*,count(b.id) piccount')->alias('a')->join('LEFT JOIN jd_goods_pics b ON a.id=b.goods_id')->where($where)->order("$orderby $orderway")->limit($page->firstRow.','.$page->listRows)->group('a.id')->select();
			
			return  array(
				'data'=> $data,
				'page'=> $pageStr
			);
		}
		protected function _before_update(&$data,$option){
			//判断是否上传了新的图片
			if($_FILES['logo']['error'] == 0){
				$result = uploads('logo','goods',array(
						array(150,150,1),
				));
			if($result['ok']==0){
				//如果失败，显示失败原因
				$error = $result['error'];
				$this->error = $error;
				return false;
			} else{
				//如果上传成功，保存原图的数据信息到数据库
				$data['logo'] = $result['image'][0];
				//如果上传成功，保存缩略图的数据信息到数据库
				$data['sm_logo'] = $result['image'][1];
			
			}
			//如果上传图片成功，删除原图
			$logo = $this->field('logo,sm_logo')->find($option['where']['id']);
			deleteImages($logo);
		
		}
		if(hasImg('pics')){
			$result = uploads('pics','Goods',array(
					array(150,150,1),
			),true);
			if($result['ok'] == 0){
				$this->error=$result['error'];
				return false;
			}
			$picId  = I('post.id');
			$picModel = M('GoodsPics');
			foreach ($result['image'] as $k=>$v ){
				$picModel->add(array(
						'pic'=>$v[0],
						'sm_pic'=>$v[1],
						'goods_id'=>$picId
				));
			}
		}
	}
		protected function _before_delete($options){
			/**************删除商品中的一张logo图片**********************/
			//根据ID取出这张图片
			$logo = $this->field('logo,sm_logo')->find($options['where']['id']);
			//删除硬盘中的图片
			deleteImages($logo);
			/***************删除商品相册中的多张图片************/
			$picModel = M('GoodsPics');
			//从数据库中取出所有要删除的图片相册的路径
			$pics = $picModel->field('pic,sm_pic')->where('goods_id='.$options['where']['id'])->select();
			//遍历删除每一张图片
			foreach($pics as $k=>$v){
				//删除硬盘中的图片
				deleteImages($v);
				//删除数据库中的相册图片
				$picModel->where('goods_id='.$options['where']['id'])->delete();
			}
		}
	
	}
	
	
	
	
	