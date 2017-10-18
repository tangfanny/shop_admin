<?php
namespace test\Controller;
use \Admin\Controller\AdminController;
class GoodsTypeController extends AdminController 
{
    public function add()
    {
    	if(IS_POST)
    	{
    		$model = D('GoodsType');
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
    		$model = D('test/GoodsType');
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
    	$model = M('GoodsType');
    	$data = $model->find($id);
    	$this->assign('data', $data);
		$this->display();
    }
    public function delete()
    {
    	$model = D('test/GoodsType');
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
    	$model = D('GoodsType');
    	$data = $model->search();
    	$this->assign(array(
    		'data' => $data['data'],
    		'page' => $data['page']
    	));
    	$this->display();
    }
}