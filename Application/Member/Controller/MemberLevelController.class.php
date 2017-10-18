<?php
namespace Member\Controller;
use \Admin\Controller\AdminController;
class MemberLevelController extends AdminController 
{
    public function add()
    {
    	if(IS_POST)
    	{
    		$model = D('Member/MemberLevel');
    		if($model->create(I('post.'), 1))
    		{
    			if($id = $model->add())
    			{
    				$this->success('添加成功！', U('lst?p='.I('get.p')));
    				exit;
    			}
    		}
    		$this->error($model->getError());
    	}
		$this->display();
    }
    public function save()
    {
    	$id = I('get.id');
    	if(IS_POST)
    	{
    		$model = D('Member/MemberLevel');
    		if($model->create(I('post.'), 2))
    		{
    			if($model->save() !== FALSE)
    			{
    				$this->success('修改成功！', U('lst', array('p' => I('get.p', 1))));
    				exit;
    			}
    		}
    		$this->error($model->getError());
    	}
    	$model = M('MemberLevel');
    	$data = $model->find($id);
    	$this->assign('data', $data);
		$this->display();
    }
    public function delete()
    {
    	$model = D('Member/MemberLevel');
    	if($model->delete(I('get.id', 0)) !== FALSE)
    	{
    		$this->success('删除成功！', U('lst', array('p' => I('get.p', 1))));
    		exit;
    	}
    	else 
    	{
    		$this->error($model->getError());
    	}
    }
    public function lst()
    {
    	$model = D('Member/MemberLevel');
    	$data = $model->search();
    	$this->assign(array(
    		'data' => $data['data'],
    		'page' => $data['page']
    	));
    	$this->display();
    }
    public function ajax_chk_level_name_unique()
    {
    	$level_name = I('get.level_name');
    	$id = I('get.id', '');
    	if($id)
    		$id = "id <> $id AND ";
		$model = M('MemberLevel');
		$count = $model->where("$id level_name = '$level_name'")->count();
		if($count == 0)
			echo 'true';
		else 
			echo 'false';
    }
}