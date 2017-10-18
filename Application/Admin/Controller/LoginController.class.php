<?php
	namespace Admin\Controller;
	class LoginController extends  \Admin\Controller\AdminController{
		/*********************生成验证码***************/
		public  function  checkcodeImg(){
			$ver = new \Think\Verify(array(
					'length' => 4,
					'useNoise' => FALSE,
					'bg'        =>  array(254,251),  // 背景颜色
					'imageH'    =>  34,               // 验证码图片高度
					'imageW'    => 160,               // 验证码图片宽度
					'fontSize'  =>  18,              // 验证码字体大小(px)
			));
			$ver->entry();
		}
		/***************登录验证表单************************/
		public function login(){
			if(IS_POST){
				
				$model = D('Admin');
				//接收表单并用登录的规则来验证表单
				if($model->validate($model->_login_validate)->create()){
					
					if($model->login()){
						redirect(U('Layout/Layout/index'));
					}
				}else {
					//如果验证失败或登录失败，显示错误信息并打印
					$this->error($model->getError());
				}
			}
			
			$this->display();
		}
		
		public function logout(){
			$model = D('Admin');
			$model->logout();
			redirect(U('login'));
		}
	}
