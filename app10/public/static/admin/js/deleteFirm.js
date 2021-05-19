$(document).ready(function(){
	//返回上一页
	$("#return").click(function(){
		history.go(-1);
		return false;
	});
	//删除确认
	$(".deleteaffirm").click(function(){
		if(!confirm('确认删除吗？'))
			return false;
	});
});