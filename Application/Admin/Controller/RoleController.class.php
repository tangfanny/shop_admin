<?php
	namespace Admin\Controller;
	use    Admin\Controller\AdminController;
	class RoleController extends AdminController{
		
		public function add(){
			if(IS_POST){
				$model = D('Role');
				if($model->create(I('post.'),1)){
					if($model->add()){
						$this->success('操作成功',U('lst'));
						exit();
					}
				}
				$error = $model->getError();
				$this->error($error);
			}
			//取出所有的权限
			$model = D('Privilege');
			$data = $model->getTree();
			$this->assign('data',$data);
			$this->assign('tilte','角色添加');
			$this->display();
		}
		public function save(){
			
			if(IS_POST){
				//var_dump($_POST);exit();
				$model = D('Role');
				if($model->create(I('post.',2))){
					if($model->save() !== FALSE){
						$this->success('修改成功',U('lst'));
						exit();
					}
				}
				$error = $model->getError();
				$this->error($error);
			}
			$id = I('get.id');
			$Rmodel = D('Role');
			$info = $Rmodel->where('id='.$id)->find();
			$this->assign('info',$info);
			
			//取出所有的权限
			$model = D('Privilege');
			$data = $model->getTree();
			//取出这个角色所有权限的id的字符串
			$rpModel = D('RolePri');
			$priId = $rpModel->field('GROUP_CONCAT(pri_id) pri_id')->where('role_id='.$id)->find();
			$this->assign('data',$data);
			
			$this->assign('priId',$priId['pri_id']);
			$this->assign('tilte','角色修改');
			
			$this->display();
		}
		public function delete(){
			$id = I('get.id');
			$model = D('Role');
			if($model->delete($id)){
				$this->success('删除成功',U('lst'));
				exit();
			}else{
				$this->error($model->getError());
			}
			$this->assign('tilte','角色删除');
		}
		
		public function lst(){
			$model = D('Role');
			$data = $model->search();
			$this->assign(array(
				'data'=>$data['data'],
				'page'=>$data['page'],
				'title'=>'角色列表'
			));
			$this->display();
		}
	}
