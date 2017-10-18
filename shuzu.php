<?php
	$arr = array(
	
			array('name' => 'Tom', 'age' => 10, 'kc' => 'php'),
			array('name' => 'Aom', 'age' => 23, 'kc' => 'php'),
			array('name' => 'Com', 'age' => 32, 'kc' => 'java'),
			array('name' => 'Jom', 'age' => 23, 'kc' => 'ios'),
			array('name' => 'Oom', 'age' => 3, 	'kc' => 'php'),
			array('name' => 'Wom', 'age' => 2, 	'kc' => 'java'),
			array('name' => 'Rom', 'age' => 76, 'kc' => 'php'),
	);
	
	function arr($r,$kc){
		$arrs = array();
		foreach ($r as $k=>$v){
			$arrs[$v[$kc]][] = $v; 
		}	
		return $arrs;
	}	
	
	$result = arr($arr,'kc');
	var_dump($result);
