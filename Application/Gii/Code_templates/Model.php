namespace <?php echo $config['moduleName']; ?>\Model;
use Think\Model;
class <?php echo $tpName; ?>Model extends Model 
{
	protected $insertFields = <?php echo $config['insertFields']; ?>;
	protected $updateFields = <?php echo $config['updateFields']; ?>;
	protected $_validate = array(<?php echo $config['validate']; ?>);
	public function search()
	{
		/**************************************** 搜索 ****************************************/
		$where = array();
<?php foreach ($config['search'] as $k => $v): ?>
<?php if($v[1] == 'normal'): ?>
		if($<?php echo $v[0]; ?> = I('get.<?php echo $v[0]; ?>'))
<?php if($v[3] == 'like'): ?>
			$where['<?php echo $v[0]; ?>'] = array('like', "%$<?php echo $v[0]; ?>%");
<?php else : ?>
			$where['<?php echo $v[0]; ?>'] = array('<?php echo $v[3]; ?>', $<?php echo $v[0]; ?>);
<?php endif; ?>
<?php elseif($v[1] == 'in'): $_arr = str_replace(',', "','", $v[2]); ?>
		if(($<?php echo $v[0]; ?> = I('get.<?php echo $v[0]; ?>')) && in_array($<?php echo $v[0]; ?>, array('<?php echo $_arr; ?>')))
			$where['<?php echo $v[0]; ?>'] = array('eq', $<?php echo $v[0]; ?>);
<?php elseif(strpos($v[1], 'between') === 0) : 
				$_arr = explode(',', $v[2]);
				foreach ($_arr as $k1 => $v1): ?>
		if($<?php echo $v1; ?> = I('get.<?php echo $v1; ?>'))
			$where['<?php echo $v[0]; ?>'] = array('<?php echo $k1 == 0 ? 'gt' : 'lt'; ?>', "$<?php echo $v1; ?><?php if($v[1] == 'betweenTime' && $k1 == 1) echo ' 23:59:59'; ?>");
<?php endforeach;endif;endforeach; ?>
		/****************************************** 排序 ***********************************/
		$orderBy = 'a.<?php echo $config['pk']; ?>';
		$orderWay = 'DESC';
		if($odby = I('get.odby'))
		{
			if($odby == '<?php echo $config['pk']; ?>_asc')
			{
				$orderBy = 'a.<?php echo $config['pk']; ?>';
				$orderWay = 'ASC';
			}
			if($odby == '<?php echo $config['pk']; ?>_desc')
			{
				$orderBy = 'a.<?php echo $config['pk']; ?>';
				$orderWay = 'DESC';
			}
		}
		/************************************* 翻页 ****************************************/
		$count = $this->alias('a')->where($where)->count();
		$page = new \Think\Page($count,4);
		// 配置翻页的样式
		$page->setConfig('prev', '上一页');
		$page->setConfig('next', '下一页');
		$data['page'] = $page->show();
		/************************************** 取数据 ******************************************/
		$data['data'] = $this->alias('a')->where($where)->group('a.<?php echo $config['pk']; ?>')->order("$orderBy $orderWay")->limit($page->firstRow.','.$page->listRows)->select();
		return $data;
	}
	// 添加前
	protected function _before_insert(&$data, $option)
	{
<?php foreach ($config['_before_insert'] as $k => $v): ?>
		$data['<?php echo $k; ?>'] = <?php echo rtrim($v, ';'); ?>;
<?php endforeach; ?>
<?php foreach ($config['fields'] as $k => $v):if($v['type'] == 'file'): ?>
		if(isset($_FILES['<?php echo $k; ?>']) && $_FILES['<?php echo $k; ?>']['error'] == 0)
		{
			$ret = uploadImage('<?php echo $k; ?>', '<?php echo $config['moduleName']; ?>', array(
<?php foreach ($v['thumbs'] as $k1 => $v1): ?>
				array(<?php echo $v1[0]; ?>, <?php echo $v1[1]; ?>, <?php echo $v1[2]; ?>),
<?php endforeach; ?>
			));
			if($ret['ok'] == 1)
			{
				$data['<?php echo $v['save_fields'][0]; ?>'] = $ret['images'][0];
<?php unset($v['save_fields'][0]);foreach ($v['save_fields'] as $k1 => $v1): ?>
				$data['<?php echo $v1; ?>'] = $ret['images'][<?php echo $k1; ?>];
<?php endforeach; ?>
			}
			else 
			{
				$this->error = $ret['error'];
				return FALSE;
			}
		}
<?php endif;endforeach; ?>
	}
	// 修改前
	protected function _before_update(&$data, $option)
	{
<?php foreach ($config['fields'] as $k => $v):if($v['type'] == 'file'): ?>
		if(isset($_FILES['<?php echo $k; ?>']) && $_FILES['<?php echo $k; ?>']['error'] == 0)
		{
			$ret = uploadImage('<?php echo $k; ?>', '<?php echo $config['moduleName']; ?>', array(
<?php foreach ($v['thumbs'] as $k1 => $v1): ?>
				array(<?php echo $v1[0]; ?>, <?php echo $v1[1]; ?>, <?php echo $v1[2]; ?>),
<?php endforeach; ?>
			));
			if($ret['ok'] == 1)
			{
				$data['<?php echo $v['save_fields'][0]; ?>'] = $ret['images'][0];
<?php foreach ($v['save_fields'] as $k1 => $v1):if($k1==0) continue; ?>
				$data['<?php echo $v1; ?>'] = $ret['images'][<?php echo $k1; ?>];
<?php endforeach; ?>
			}
			else 
			{
				$this->error = $ret['error'];
				return FALSE;
			}
<?php foreach ($v['save_fields'] as $k1 => $v1): ?>
			deleteImage(array(I('post.old_<?php echo $v1; ?>')));
<?php endforeach; ?>			
		}
<?php endif;endforeach; ?>
	}
	// 删除前
	protected function _before_delete($option)
	{
		if(is_array($option['where']['<?php echo $config['pk']; ?>']))
		{
			$this->error = '不支持批量删除';
			return FALSE;
		}
<?php if($config['digui'] == 1): ?>
		$_count = $this->where('parent_id='.$option['where']['<?php echo $config['pk']; ?>'])->count();
		if($_count >= 1)
		{
			$this->error = '有子级数据，无法删除';
			return FALSE;
		}
<?php endif; ?>
<?php foreach ($config['fields'] as $k => $v):if($v['type'] == 'file'): ?>
		$images = $this->field('<?php echo implode(',', $v['save_fields']); ?>')->find($option['where']['<?php echo $config['pk']; ?>']);
		deleteImage($images);
<?php endif;endforeach; ?>
	}
<?php if($config['digui'] == 1): ?>
	/************************************* 递归相关方法 *************************************/
	public function getTree()
	{
		$data = $this->order('order_num ASC')->select();
		return $this->_reSort($data);
	}
	private function _reSort($data, $parent_id=0, $level=0, $isClear=TRUE)
	{
		static $ret = array();
		if($isClear)
			$ret = array();
		foreach ($data as $k => $v)
		{
			if($v['parent_id'] == $parent_id)
			{
				$v['level'] = $level;
				$ret[] = $v;
				$this->_reSort($data, $v['<?php echo $config['pk']; ?>'], $level+1, FALSE);
			}
		}
		return $ret;
	}
	public function getChildren($<?php echo $config['pk']; ?>)
	{
		$data = $this->select();
		return $this->_children($data, $<?php echo $config['pk']; ?>);
	}
	private function _children($data, $parent_id=0, $isClear=TRUE)
	{
		static $ret = array();
		if($isClear)
			$ret = array();
		foreach ($data as $k => $v)
		{
			if($v['parent_id'] == $parent_id)
			{
				$ret[] = $v['<?php echo $config['pk']; ?>'];
				$this->_reSort($data, $v['<?php echo $config['pk']; ?>'], FALSE);
			}
		}
		return $ret;
	}
<?php endif; ?>
	/************************************ 其他方法 ********************************************/
}