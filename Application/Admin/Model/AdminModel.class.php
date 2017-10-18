<?php
	namespace Admin\Model;
	use  Think\Model;
	class AdminModel extends  Model{
		protected $insertFields = array('username', 'password', 'cpassword', 'checkcode');
		protected $updateFields = array('id', 'username', 'password', 'cpassword', 'checkcode');
		/********************验证规则*************************/
		protected  $_validate=array(
			//添加和修改时使用的规则
			array('username','require','用户名不能为空！',1,'regex'),
			array('password','require','密码不能为空！',1,'regex',1),
			array('cpassword','password','两次输入密码不一致',1,'confirm'),
			array('username','','用户名已经存在！',1,'unique')
		);
		
		 public $_login_validate=array(
			//表单登录时的验证规则
			array('username','require','用户名不能为空！',1,'regex'),
			array('password','require','密码不能为空！',1,'regex'),
			array('checkcode','require','验证码不能为空！',1,'regex'),
			array('checkcode','checkcode','验证码不正确！',1,'callback'),
		); 
		public function checkcode($code){
			$ver = new \Think\Verify();
			return  $ver->check($code);
		}
		/**************取出这个管理员有权访问的按钮*********************/
		public function getBtns(){
			//先根据ID取出前两级权限
			$adminId = session('adminId');
			
			//如果是超级管理员就取出所有的权限
			If($adminId == 1){
				$sql = "SELECT * FROM jd_privilege WHERE parent_id =0 order by sort_num ASC";
				$pri = $this->query($sql);
				
				//循环每个顶级的权限取出二级权限
				foreach ($pri as $k=>$v){
					$sql = 'SELECT * FROM jd_privilege WHERE parent_id ='.$v['id'].' order by sort_num ASC';
					$pri[$k]['children'] = $this->query($sql);
				}
			}else{
				//如果不是超级管理员
				//先取出这个管理员所拥有的顶级权限
				$sql = "SELECT c.id,c.pri_name,c.module_name,c.controller_name,c.action_name FROM
						       jd_admin_role a,
								jd_role_pri b,
								jd_privilege c
						WHERE a.role_id = b.role_id AND
								b.pri_id = c.id AND
								a.admin_id = '.$adminId' AND
								c.parent_id = 0 order by sort_num ASC
								";
				$pri = $this->query($sql);
				//循环每个顶级的权限取出二级权限
				foreach ($pri as $k=>$v){
					$sql = "SELECT c.pri_name,c.module_name,c.controller_name,c.action_name FROM
								jd_admin_role a,
								jd_role_pri b,
								jd_privilege c WHERE
							a.role_id = b.role_id AND
							b.pri_id = c.id AND
							a.admin_id = '.$adminId' AND
							c.parent_id =".$v['id'].' order by sort_num ASC';
					//为每个顶级权限增加一个children字段存子权限的
					$pri[$k]['children'] = $this->query($sql);
				}
			}
			//返回这个四维数组
			return  $pri;
		}
		
		/************************验证管理员登录***********************/
			public  function login(){
				//根据用户名查询数据库
				$username = I('post.username');
				$password = I('post.password');
				
				$user = $this->where(array(
					'username'=>array('eq', $username)
				))->find();
				if($user){
					if($user['password'] == md5(C('MD5_KEY').$password)){
						//如果登录成功就把会员ID 会员名称存到session里面
						session('adminId',$user['id']);
						session('username',$user['username']);
						return  TRUE;
					}else{
						$this->error = '密码不正确';
						return FALSE;
					}
				}else{
					$this->error = '用户名不存在';
					return FALSE;
				}
			}
			public function logout(){
				//清空session
				session(null);
			}
			

			// 翻页
			public function search()
			{
				/************ 翻页 *********************/
				// 先取出总的记录数
				$count = $this->count();
				// 生成翻页类的对象
				$Page = new \Think\Page($count, 4);
				// 配置翻页的样式
				$Page->setConfig('prev', '上一页');
				$Page->setConfig('next', '下一页');
				// 生成翻页的字符串
				$pageStr = $Page->show();
				// 取出当前页的数据
				/***
					SELECT a.*,GROUP_CONCAT(c.role_name) as role_name 
					FROM jd_admin as a LEFT JOIN jd_admin_role as b ON a.id=b.admin_id
					LEFT JOIN jd_role as c ON b.role_id=c.id GROUP BY b.admin_id;
				 */
				$data = $this->field('a.*,GROUP_CONCAT(c.role_name) role_name')->alias('a')->join('LEFT JOIN jd_admin_role as b ON a.id=b.admin_id
					LEFT JOIN jd_role as c ON b.role_id=c.id')->limit($Page->firstRow.','.$Page->listRows)->group('b.admin_id')->select();
				return array(
						'data' => $data,
						'page' => $pageStr,
				);
			}
			
			protected function _before_insert(&$data, $options){
				$data['password']=md5(C('MD5_KEY').$data['password']);
			}
			protected function _after_insert(&$data, $options){
				$id = I('post.role_id');
				$adminId = $data['id'];
				$model = M('AdminRole');
				foreach ($id as $k=>$v){
					$model->add(array(
						'role_id'=>$v,
						'admin_id'=>$adminId
					));
				}
			}
			
			protected function _before_update(&$data, $options){

				if(!empty($data['password'])){
					$data['password'] = md5(C('MD5_KEY').$data['password']);
				}else{
					unset($data['password']);
				}
				$adminId = I('post.id');
				$model = D('AdminRole');
				$model->where('admin_id='.$adminId)->delete();
				
				$roleId = I('post.role_id');
				if($roleId){
					foreach ($roleId as $k=>$v){
						$model->add(array(
							'role_id'=>$v,
							'admin_id'=>$adminId
						));
					}
				}
			} 
			
			protected function _before_delete($options){
				$adminId = I('get.id');
				if($adminId==1){
					$this->error='超级管理员不能被删除！';
					return FALSE;
				}
				$model = D('AdminRole');
				//delete from jd_admin_role where admin_id = 4;
				$model->where('admin_id='.$adminId)->delete();
			}
}

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	