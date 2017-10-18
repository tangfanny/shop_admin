<?php
	/***************删除图片********************/
	function  deleteImages($logo){
		static $pre = null;
		if($pre === null)
			$pre = C('rootPath');
		foreach ($logo as $k=>$v){
			@unlink($pre.$v);
		}
	}
	/***********显示图片***************/
	function showImage($logo,$width='',$height=''){
		static $pre = null;
		if($pre === null)
			$pre = C('viewPath');
		if($width)
			$width = "width='$width'";
		if($height)
			$height = "height='$height'";
		echo "<img $width $height src='$pre$logo'/>";
	}
	/**
	 * 防止XSS攻击
	 */
	function removeXSS($val){
		static $obj = null;
		if ($obj===null) {
			
			require './HTMLPurifier/HTMLPurifier.includes.php';
			$obj = new HTMLPurifier();
		}
		return $obj->purify($val);
	}
	/**
	 * 上传一张图片并可以生成多张缩略图
	 * 
	 */
	function  uploads($imageNm,$savePath,$thumb=array(),$multiple =false){
		/****************上传图片**********************/
		//从配置文件中读取图片保存的根目录的路径
		$rootpath = C('rootPath');
		//从配置文件中读出图片尺寸的配置
		$max = (int)C('MAX_UPLOAD_FILESIZE');
		$type = C('ALLOW_UPLOAD_FILETYPE');
		//图片中最大尺寸不能大于php.ini中的配置
		$phpmuf = (int)ini_get('MAX_UPLOAD_FILESIZE');
		$max = min($max,$phpmuf);
		//将取出的配置整合到数组中
		$config = array(
				'maxSize'   =>    $max * 1024 * 1024 ,// 设置附件上传大小
				'exts'      =>    $type,// 设置附件上传类型
				'rootPath' =>     $rootpath,// 设置附件上传根目录   
				'savePath'  =>    $savePath.'/' // 设置附件上传子目录
		);
		$upload = new \Think\Upload($config);// 实例化上传类
		// 上传图片
		$info   =   $upload->upload(array(
				"$imageNm"=>$_FILES["$imageNm"]
		));
		/*********生成缩略图**************/
		if($info){
			//定义一个数组保存多张图片的信息
			$pics = array();
			/************批量上传图片相册**************/
			//判断是否上传多张图片
			if($multiple){
				$images = new \Think\Image();
				//通过遍历取出每一张图片的信息
				foreach ($info as $k=>$v){
					$one = array();
					//取得每一张图的原名
					$one[0] = $v['savepath'].$v['savename'];
					//判读是否制作缩略图
					if($thumb){
						//循环生成缩略图
						foreach ($thumb as $k1=>$v1){
							//打开原图
							$images->open($rootpath.$one[0]);
							//配置缩略图的名字
							$thumbName =  $v['savepath'].'thum_'.$k1.'_'.$v['savename'];
							$images->thumb($v1[0], $v1[1],$v1[2])->save($rootpath.$thumbName);
							//把生成的缩略图放到数组中
							$one[] = $thumbName;
						}
					}
					//把一个图片的数组放到总的数组中
					$pics[] = $one;
				}
				return array(
					'ok'=>1,
					'image'=>$pics
				);
			}else{
			$images = new \Think\Image();
			//上传图片后原图片的路径
			$data[0] = $info["$imageNm"]['savepath'].$info["$imageNm"]['savename'];
			//如果传了第三个参数就生成缩略图
			if($thumb){
				//循环遍历生成缩略图
				foreach ($thumb as $k=>$v){
				//循环图片的名称
				$thumbName = $info["$imageNm"]['savepath'].'thum_'.$k.'_'.$info["$imageNm"]['savename'];
				//打开原图
				$images->open($rootpath.$data[0]); 
				//制作缩略图
				$images->thumb($v[0],$v[1],$v[2])->save($rootpath.$thumbName); 
				//把缩略图名字存到数据库
				$data[] = $thumbName; 
				}
			}
			return array(
				//返回图片和缩略图制作成功，1表示返回的是图片的地址信息
				'ok'=> 1,
				'image'=>$data
			);
			}
		} else{
			//如果图片上传失败
			$error = $upload->getError(); // 获取失败原因
			return array(
				'ok'=> 0 ,
				'error'=>$error
			);
		}
	}
	/****
	 * imgName是上传图片表单中input框中name属性的值
	 * 判断是否上传图片
	 * 上传返回true 否则返回false
	 */
	function hasImg($imgName){
		if(isset($_FILES[$imgName])){
			foreach ($_FILES[$imgName]['error'] as $value){
				if($value == 0)
					return  true;
			}
		}
		return  false;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	