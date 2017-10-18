<?php
return array(
	'DB_TYPE' => 'mysql',
	'DB_HOST' => '127.0.0.1',
	'DB_NAME' => 'finajd',
	'DB_USER' => 'root',
	'DB_PWD' => 'ttang',
	'DB_PREFIX' => 'jd_',
	'DEFAULT_MODULE'        =>  'Layout',  // 默认模块
	'DEFAULT_CONTROLLER'    =>  'Layout', // 默认控制器名称
	'DEFAULT_ACTION'        =>  'index', // 默认操作名称
	// 接收到的数据使用函数过滤一下，应用于I函数
	'DEFAULT_FILTER' => 'trim,removeXSS',
	// 图片相关配置（自己加的TP中没有）
	'MAX_UPLOAD_FILESIZE' => '3M',
	'ALLOW_UPLOAD_FILETYPE' => array('gif','png','jpg','jpeg','ejpeg','bmp'),
	'rootPath' =>     './Public/Uploads/',// 图片在硬盘上的路径 ==》php程序用
	'viewPath' =>     '/Public/Uploads/',// 图片在网站中的路径==》html浏览器用的
	
	'URL_MODEL'             =>  2,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式
    'URL_PATHINFO_DEPR'     =>  '-',	// PATHINFO模式下，各参数之间的分割符号
	/*************************** md5加密的密钥 *********************************/
	'MD5_KEY' => '!343!129fd$fd_fds=+43>?lg',
);