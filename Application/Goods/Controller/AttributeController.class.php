<?php
	namespace  Goods\Controller;
	use   Admin\Controller\AdminController;
	class AttributeController extends AdminController{
		public function add(){
			if(IS_POST){
				$model = D('Attribute');
				if($model->create(I('post.'),1)){
					if($model->add()){
						$this->success('添加成功',U('lst'));
						exit();
					}
				}
				$this->error($model->getError());
			}
			$model = D('GoodsType');
			$data = $model->select();
			$this->assign('data',$data);
			$this->assign('title','添加属性');
			$this->display();
		}
		public function save(){
			if(IS_POST){
				$model = D('Attribute');
				if($model->create(I('post.'),2)){
					if($model->save()){
						$this->success('修改成功',U('lst'));
						exit();
					}
				}
				$this->error($model->getError());
			}
			$id = I('get.id');
			$model = D('Attribute');
			$info = $model->where('id='.$id)->find();
			$this->assign('info',$info);
			
			$model = D('GoodsType');
			$data = $model->select();
			$this->assign('data',$data);
			$this->assign('title','修改属性');
			$this->display();
		}
		public function delete(){
			$id = I('get.id');
			$model = D('Attribute');
			if( $model->where('id='.$id)->delete()){
				$this->success('删除成功',U('lst'));
				exit();
			}else{
				$this->error($model->getError());
			}
			$this->assign('title','删除属性');
			$this->display();
		}
		public function lst(){
			$model = D('Attribute');
			$data = $model->search();
			$this->assign(array(
				'title'=>'属性列表',
				'data'=>$data['data'],
				'page'=>$page['page']
			));
			//取出所有的类型
			$tyModel = M('GoodsType');
			$tyData = $tyModel->select();
			$this->assign('tyData',$tyData);
			$this->display();
		}
	}
