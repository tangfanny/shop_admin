<?php
	namespace  Admin\Model;
	use     Think\Model;
	class RoleModel extends  Model{
		protected $insertFields = array('role_name') ;
		protected $updateFields = array('id','role_name') ;
		//添加和修改时都使用的规则
		protected $_validate =array(
				array('role_name','require','角色名称不能为空',1,'regex')
		) ;
		
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
			// SELECT a.*,GROUP_CONCAT(c.pri_name) pri_name FROM sh_role a LEFT JOIN sh_role_privilege b ON a.id=b.role_id LEFT JOIN sh_privilege c ON b.pri_id=c.id GROUP BY a.id;
			$data = $this->field('a.*,GROUP_CONCAT(c.pri_name) pri_name')->alias('a')->join('LEFT JOIN jd_role_pri b ON a.id=b.role_id LEFT JOIN jd_privilege c ON b.pri_id=c.id')->limit($Page->firstRow.','.$Page->listRows)->group('a.id')->select();
			//var_dump($count);exit();
			return array(
					'data' => $data,
					'page' => $pageStr,
			);
		}
		protected function _before_delete($options){
			//先取出这个角色的管理员
			$arModel = M('AdminRole');
			$r = $arModel->where('role_id='.$options['where']['id'])->count();
			if($r>0){
				$this->error='有管理员属于这个角色，无法删除！';
				return FALSE;
			}
			//如果可以删除，就先删除这个角色所拥有的权限的记录
			$rpModel = M('RolePri');
			$rpModel->where('role_id='.$options['where']['id'])->delete();
		}
		protected function _after_insert($data,$options){
			$priId = I('post.pri_id');
			//先处理角色所拥有的权限
			if($priId){
				$rpModel = D('RolePri');
				foreach ($priId as $k=>$v){
					//将权限id存到角色权限表中
					$rpModel->add(array(
						'pri_id'	=>	$v,
						'role_id'	=>	$data['id']
					));
				}
			}
		}
		protected function _before_update(&$data, $options){
			$roleId = I('post.id');
			
			$rpModel = D('RolePri');
			//先删除原来的，再重新添加
			$rpModel->where('role_id='. $roleId)->delete();
			$priId = I('post.pri_id');
			if($priId){
				foreach($priId as $k => $v){
					$rpModel->add(array(
						'pri_id'	=>	$v,
						'role_id'	=>	$roleId
					));
				}
			}
		}
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	