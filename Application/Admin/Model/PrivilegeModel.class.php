<?php
	namespace  Admin\Model;
	use		Think\Model;
	class PrivilegeModel extends Model{
		/****************验证规则**************************/
		protected $insertFields = array('pri_name','module_name','controller_name','action_name','parent_id','sort_num');
		protected $updateFields = array('id','pri_name','module_name','controller_name','action_name','parent_id','sort_num');
		/*******************打印无限级的显示结构********************/
		public function getTree(){
			//取出所有权限
			$data = $this->order('sort_num ASC')->select();
			//使用递归对这些权限重新排序
			return $this->_reSort($data);
		}
		public function _reSort($data,$parent_id=0,$level=0){
			static $r = array();
			foreach ($data as $k=>$v){
				if($v['parent_id']==$parent_id){
					$v['level'] = $level;
					$r[] = $v;
					//再找这个权限的子权限
					$this->_reSort($data,$v['id'],$level+1);
				}
			}
			return $r;
		}
	/*******************根据一个权限的id取出这个权限所有的子id*************************/
		public function getChildren($priId){
			$data = $this->select();
			return $this->_getChildren($data,$priId);
		}
		private function _getChildren($data,$priId,$isClear=TRUE){
			static $r=array();
			if($isClear)
				$r = array();
			foreach($data as $k=>$v){
				if($v['parent_id']==$priId){
					$r[] = $v['id'];
					$this->_getChildren($data,$v['id'],FALSE);
				}
			}
			return $r;
		}
		protected function _before_delete($options){
			//先判断是否有子权限
			$r = $this->getChildren($options['where']['id']);
			if($r){
				$this->error ='有子权限，无法删除！';
				return FALSE;
			}
		}
	}

	
	
	
	
	
	
	
	
	
	
	
	
	