<?php
	namespace  Admin\Controller;
	use Think\Controller;
	/*****************权限验证*************/
	class AdminController extends Controller{
		public function __construct(){
			parent::__construct();
			return true;
			//在session中取出当前管理员的ID，并判断有没有登录 。 如果没有说明没有登录，我们会在登陆成功后把ID存到session中
			if($adminId = session('adminId')){
				//先取出当前管理员要访问的地址
				$url = MODULE_NAME.'-'.CONTROLLER_NAME.'-'.ACTION_NAME;
				//查询数据库看检查这个用户有没有访问这个地址的权限
				$sql = "SELECT COUNT(a.adminId n) FROM 
						 jd_admin_role a,
						jd_role_pri b,
						jd_pri c,
						WHERE 
						a.role_id = b.role_id AND
						b.pri_id = c.id AND
						CONCAT(c.module_name,'-',c.controller_name,'-',c.action_name".$url." AND
								 a.admin_id = ".session('id');
				$db = M();
				$r = $db->query($sql);
				if($r[0]['n']<=0)
					$this->error('无权访问');
			}else{
				//如果没有权限就跳转到登录页面
				$this->redirect(U('Admin/login/login'));
			}
		}
		public function add(){
			
			if(IS_POST){
				$model = D('Admin');
				if($model->create(I('post.'),1)){
					if($model->add()){
						$this->success('添加成功',U('lst'));
						exit();
					}
				}
				$this->error($model->getError());
			}
			
			//取出所有角色
			$model = D('Role');
			$data = $model->select();
			$this->assign('data',$data);
			$this->assign('title','管理员添加');
			$this->display();
		}
		public function save(){
			if(IS_POST){
				$model = D('Admin');
				
				if($result = $model->create(I('post.'),2)){
					if($model->save() !== FALSE){
						$this->success('修改成功',U('lst'));
						exit();
					}
				}
				$this->error($model->getError());
			}
			
			$this->assign('title','管理员修改');
			//取出所有角色
			$model = D('Role');
			$data = $model->select();
			$this->assign('data',$data);
			
			$id = I('get.id');
			$aModel = D('Admin');
			$info = $aModel->find($id);
			$this->assign('info',$info);
			
			//取出当前管理员所拥有的角色
			$arModel = D('AdminRole');
			$roleId = $arModel->field('GROUP_CONCAT(role_id) role_id')->where('admin_id='.$id)->find();
			$this->assign('roleId',$roleId['role_id']);
			//$sql = $model->getLastSql();
			$this->display();
		}
		public function delete(){
			$id = I('get.id');
			$model = D('Admin');
			if($model->where('id='.$id)->delete()){
				$this->success('删除成功',U('lst'));
				exit();
			}else{
				$this->error($model->getError());
			}
			$this->assign('title','管理员删除');
			$this->display();
		}
		public function lst(){
			$model = D('Admin');
			$data = $model->search();
			$this->assign(array(
				'data'=>$data['data'],
				'page'=>$data['page'],
				'title'=>'管理员列表'
			));
			$this->display();
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	