<?php
	namespace Layout\Controller;
	
	class LayoutController extends \Admin\Controller\AdminController{
		public function index(){
			$this->display();
		}
		public function top(){
			$this->display();
		}
		public function menu(){
			//取出当前管理员有权访问的权限
			$model = D('Admin/Admin');
			$btns = $model->getBtns();
			$this->assign('btns',$btns);
			$this->display();
		}
		public function main(){
			$this->display();
		}
	}