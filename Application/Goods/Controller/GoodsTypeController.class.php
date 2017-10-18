<?php
	namespace Goods\Controller;
	use 	Admin\Controller\AdminController;
	class GoodsTypeController extends AdminController{
		public function add(){
			if(IS_POST){
				$model = D('GoodsType');
				if($model->create(I('post.'),1) ){
					if($model->add()){
						$this->success('添加成功',U('lst'));
						exit();
					}
				}
				$this->error($model->getError());
			}
			$this->display();
		}
		
		public function save(){
			if(IS_POST){
				$model = D('GoodsType');
				if($model->create(I('post.'),2)){
					if($model->save()!==FALSE){
						$this->success('修改成功',U('lst'));
						exit();
					}
				}
				$this->error($model->getError());
			}
			$id = I('get.id');
			$model = D('GoodsType');
			$data = $model->where('id='.$id)->find();
			$this->assign('data',$data);
			$this->display();
		}
		public function delete(){
			$model = D('GoodsType');
			$id = I('get.id');
			if($model->delete($id)!==FALSE){			
				$this->success('删除成功',U('lst'));
				exit;
			}else{
				$this->error($model->getError());
			}
		}
		public function lst(){
			$model = D('GoodsType');
			$data = $model->select();
			$this->assign('data',$data);
			$this->assign('title','类型列表');
			$this->display();
		}
	}
