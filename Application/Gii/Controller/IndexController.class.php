<?php
namespace Gii\Controller;
use Think\Controller;
define('GII_PATH', APP_PATH.'Gii/');
define('GII_CONFIG_PATH', GII_PATH.'Table_configs/');
define('GII_TEMPLATE_PATH', GII_PATH.'Code_templates/');
class IndexController extends Controller 
{
	public function index()
	{
		if(IS_POST)
		{
			/********************************** 生成表的配置文件 **********************/
			$type = I('post.type');
			if($type == 2)
			{
				$this->makeConfigFile();
				exit;
			}
			/********************************** 生成代码 ***************************/
			$configName = I('post.config_name');
			if(!$configName)
				$this->error('配置文件名称不能为空！');
			if(!file_exists(GII_CONFIG_PATH.$configName))
				$this->error('配置文件不存在！');
			$config = include(GII_CONFIG_PATH.$configName);
			// 表名转成tp中的名字
			$tpName = $this->_dbName2TpName($config['tableName']);
			// 1.生成对应模块的目录结构
			$moduleDir = APP_PATH.$config['moduleName'];
			$cDir = APP_PATH.$config['moduleName'].'/Controller';
			$mDir = APP_PATH.$config['moduleName'].'/Model';
			$vDir = APP_PATH.$config['moduleName'].'/View';
			$v1Dir = APP_PATH.$config['moduleName'].'/View/'.$tpName;
			if(!is_dir($moduleDir))
				mkdir($moduleDir, 0777);
			if(!is_dir($cDir))
				mkdir($cDir, 0777);
			if(!is_dir($mDir))
				mkdir($mDir, 0777);
			if(!is_dir($vDir))
				mkdir($vDir, 0777);
			if(!is_dir($v1Dir))
				mkdir($v1Dir, 0777);
			// 2. 在view目录下生成对应的表单add.html
			ob_start();
			include(GII_TEMPLATE_PATH . 'add.html');
			$str = ob_get_clean();
			file_put_contents($v1Dir.'/add.html', $str);
			// 3. 生成控制器文件
			ob_start();
			include(GII_TEMPLATE_PATH.'Controller.php');
			$str = ob_get_clean();
			file_put_contents($cDir.'/'.$tpName.'Controller.class.php', "<?php\r\n".$str);
			// 4. 生成模型文件
			ob_start();
			include(GII_TEMPLATE_PATH.'Model.php');
			$str = ob_get_clean();
			file_put_contents($mDir.'/'.$tpName.'Model.class.php', "<?php\r\n".$str);
			// 5. 生成lst.html页面
			ob_start();
			include(GII_TEMPLATE_PATH.'lst.html');
			$str = ob_get_clean();
			file_put_contents($v1Dir.'/lst.html', $str);
			// 6. 生成edit.html页面
			ob_start();
			include(GII_TEMPLATE_PATH.'save.html');
			$str = ob_get_clean();
			file_put_contents($v1Dir.'/save.html', $str);
			if($config['with_privileges'])
			{
				/*************************************** 添加权限相关数据 ******************************/
				$nodeModel = M('Privilege');
				$node = $nodeModel->field('id')->where("pri_name='{$config['moduleCnName']}模块' AND parent_id=0")->find();
				if(!$node)
				{
					$node['id'] = $nodeModel->add(array(
						'pri_name' => $config['moduleCnName'].'模块',
						'module_name' => 'null',
						'controller_name' => 'null',
						'action_name' => 'null',
						'sort_num' => 100,
						'parent_id' => 0,
					));
				}
				$lstNodeId = $nodeModel->add(array(
					'pri_name' => $config['tableCnName'].'列表',
					'module_name' => $config['moduleName'],
					'controller_name' => $tpName,
					'action_name' => 'lst',
					'sort_num' => 100,
					'parent_id' => $node['id'],
				));
				$newNodeId = $nodeModel->add(array(
					'pri_name' => '添加新'.$config['tableCnName'],
					'module_name' => $config['moduleName'],
					'controller_name' => $tpName,
					'action_name' => 'add',
					'sort_num' => 100,
					'parent_id' => $lstNodeId,
				));
				$nodeModel->add(array(
					'pri_name' => '修改'.$config['tableCnName'],
					'module_name' => $config['moduleName'],
					'controller_name' => $tpName,
					'action_name' => 'save',
					'sort_num' => 100,
					'parent_id' => $lstNodeId,
				));
				$nodeModel->add(array(
					'pri_name' => '删除'.$config['tableCnName'],
					'module_name' => $config['moduleName'],
					'controller_name' => $tpName,
					'action_name' => 'delete',
					'sort_num' => 100,
					'parent_id' => $lstNodeId,
				));
				/********************************* 字段唯一的ajax验证 ***************************************/
				foreach ($config['fields'] as $k => $v)
				{
					if(isset($v['validate']['unique']))
					{
						$nodeModel->add(array(
							'pri_name' => '检查 '.$k.' 字段值是否唯一',
							'module_name' => $config['moduleName'],
							'controller_name' => $tpName,
							'action_name' => '/ajax_chk_'.$k.'_unique',
							'sort_num' => 100,
							'parent_id' => $newNodeId,
						));
					}
				}
			}
			$this->success('代码生成成功！');
			exit;
		}
		$this->display();
	}
	private function _dbName2TpName($tableName)
	{
		$tableName = explode('_', $tableName);
		unset($tableName[0]);
		$tableName = array_map('ucfirst', $tableName);
		return implode($tableName);
	}
	public function makeConfigFile()
	{
		$db = M();
		$tableName = I('post.config_name');
		if($tableName)
		{
			$tableName = explode(',', $tableName);
			foreach ($tableName as $___v)
			{
				// 取出表的信息
				$_tableInfo = $db->query("show table status WHERE Name LIKE '$___v'");
				if($_tableInfo)
				{
					$_tableInfo = $_tableInfo[0];
					// 取出表的字段
					$_tableFields = $db->query("SHOW FULL FIELDS FROM $___v");
					$tpName = $this->_dbName2TpName($___v);
					ob_start();
					include(GII_TEMPLATE_PATH . 'config.php');
					$str = ob_get_clean();
					file_put_contents(GII_CONFIG_PATH.$___v.'.php', "<?php\r\n".$str);
				}
				else 
					$this->error('表不存在！');
			}
			$this->success('成功！');
			exit;
		}
		else 
			$this->error('请输入表名！');
	}
}