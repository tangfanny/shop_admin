function U(url){
	//根据php中的配置文件生成不同的URL地址
	if(URL_MODEL==0){
		return url;
	}else{
		url = url.replace(/[&=]/g,'-');
		return url;
	}
}
