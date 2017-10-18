<?php
	namespace  Admin\Controller;
	use Admin\Controller\AdminController;
	class PrivilegeController extends  AdminController{
			public  function  add(){
				//2，处理表单
				if(IS_POST){
					
					//3.创建商品模型
					$model = D('Privilege');
					// 4,接受表单并且验证
					$tt = I('post.');
					
					if($model->create(I('post.'),1)){
						// 5,验证成功添加到数据库
						if($model->add()){
							// 6,提示成功信息并且跳转到当前控制器的list方法
							$this->success('操作成功！',U('lst'));
							// 添加成功就停止代码
							exit;
						}
					}
					// 7,如果表单验证失败或者数据添加失败都获取错误信息
					$error = $model->getError();
					// 8,显示错误信息
					$this->error($error);
				}
				//取出所有的权限并做下拉框
				$priModel = D('Privilege');
				$data = $priModel->getTree();
				$this->assign('data',$data);
				//1,显示表单
				$this->display();
			}
			
			/***
			 * 
			 *  权限修改
			 * 
			 */
			public  function  save(){
				if(IS_POST){
					
					$priModel = D('Privilege');
					if($priModel->create(I('post.'),2)){
						if(FALSE !== $priModel->save()){
							$this->success('修改成功',U('lst?p='.I('get.p')));
							exit;
						}
					}
					$error = $priModel->getError();
					$this->error($error);
				}
				$id = I('get.id');
				$priModel = D('Privilege');
				$info = $priModel->find($id);
				$this->assign('info',$info);
				//取出所有的权限并制作下拉框
				$data = $priModel->getTree();
				$this->assign('data',$data);
				//为了去掉自己和子权限，先找出子权限
				$children = $priModel->getChildren($id);
				
				//把当前权限id和子权限放在一起
				$children[] = $id;
				//var_dump($children);exit();
				$this->assign('children',$children);
				$this->assign('title','修改权限');
				$this->display();
			}
			public  function  lst(){
				$model=D('Privilege');
				$data = $model->getTree();
				$this->assign('title','权限列表');
				$this->assign('data',$data);
				$this->display();
			}
			
			//权限的删除
			public  function  delete(){
				$priId = I('get.id');
				$model = D('Privilege');
				if($model->delete($priId) !== FALSE){
					$this->success('删除成功',U('lst'));
					die;
				}else{
					$this->error($model->getError());
				}
			}
		}
		
		
		
		
		
		
		
		
		