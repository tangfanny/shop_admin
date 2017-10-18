<?php
	namespace  Goods\Controller;
	use Admin\Controller\AdminController;
	class GoodsController extends  AdminController{
			public  function  add(){
				//2，处理表单
				if(IS_POST){
					//3.创建商品模型
					$goodsmodel = D('Goods');
					// 4,接受表单并且验证
					$tt = I('post.');
					if($goodsmodel->create(I('post.'),1)){
						// 5,验证成功添加到数据库
						if($goodsmodel->add()){
							// 6,提示成功信息并且跳转到当前控制器的list方法
							$this->success('操作成功！',U('lst'));
							// 添加成功就停止代码
							exit;
						}
					}
					// 7,如果表单验证失败或者数据添加失败都获取错误信息
					$error = $goodsmodel->getError();
					// 8,显示错误信息
					$this->error($error);
				}
				$this->assign('title','商品添加');
				
				$model = M('GoodsType');
				$tydata = $model->select();
				$this->assign('tydata',$tydata);
				//1,显示表单
				$this->display();
			}
			/***
			 * 
			 *  商品修改
			 * 
			 */
			public  function  save(){
				if(IS_POST){
					
					$goodsModel = D('Goods');
					if($goodsModel->create(I('post.'),2)){
						if(FALSE !== $goodsModel->save()){
							$this->success('修改成功',U('lst?p='.I('get.p')));
							exit;
						}
					}
					$error = $goodsModel->getError();
					$this->error($error);
				}
				$id = I('get.id');
				$goodsModel = M('Goods');
				$info = $goodsModel->find($id);
				//取出这件商品现有的相册图片
				$picModel = M('GoodsPics');
				$data = $picModel->where('goods_id='.$id)->select();
				$this->assign(array(
						'data'=>$data['data'],
						'info'=>$info['info'],
						'tilte'=>'商品修改'
				));
				$this->display();
			}
			/*****商品分页 查询 排序**/
			public  function  lst(){
				$goodsModel = D('Goods');
				$data = $goodsModel->search();
				$this->assign(array(
					'data'=>$data['data'],
					'page'=>$data['page'],
					'tilte'=>'商品列表'
				));
				$this->display();
			}
			public  function  delete(){
				$goods_id = I('get.id');
				$goodsModel = D('Goods');
				if($goodsModel->delete($goods_id) !== FALSE){
					$this->success('删除成功',U('lst'));
					exit();
				}else{
					$this->error($goodsModel->getError());
				}
			}
			public  function  ajaxChkGoodsName()
			{
				//接受ajax传过来的商品名称
				$goods_id = I('get.id');
				$goodsName = I('get.goods_name');
				//
				$goodsModel = M('Goods');
				$where['goods_name'] = array('eq',$goods_name); 
				if($goods_id){
					$where['id'] = array('neq',$goods_id);
				}
				$c = $goodsModel->where($where)->count();
				if ($c >= 1){
					echo "false";
				}else {
					echo "true";
				}
			}
			
			public  function ajaxDelImage(){
				$picId = I('get.id');
				$picModel = M('GoodsPics');
				$pics = $picModel->field('pic,sm_pic')->find($picId);
				deleteImages($pics);
				$picModel->where('id='.$picId)->delete();
			}
			
			public function ajaxGetAttr()
			{
				$typeid = I('get.typeid');
				$model = D('Attribute');
				$data = $model->where(array('type_id'=>array('eq',$typeid)))->select();
				echo json_encode($data);
			}
		}
		
		
		
		
		
		
		
		
		