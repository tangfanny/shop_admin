$(function(){
	$("#listDiv table tr").mouseover(function(){
		$(this).find('td').css("backgroundColor",'#1AFD9C');
	});
	$("#listDiv table tr").mouseout(function(){
		$(this).find("td").css('backgroundColor','#FFF');
	});
});