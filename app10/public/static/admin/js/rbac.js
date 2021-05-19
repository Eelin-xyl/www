$(function(){
	$('input[level=1]').click(function() {
		var inputs = $(this).parents('.app').find('input');
		$(this).attr('checked') ? inputs.attr('checked', true) : inputs.removeAttr('checked');
		checkobj = $(this).parents('form').find('.app p input:checked');
		if (checkobj.length == 0){
			$('#checkbox11').removeAttr('checked');
		}
	});
	$('input[level=2]').click(function() {
		var appobj = $(this).parents('.app');
        var inputs = $(this).parents('dl').find('input');
        $(this).attr('checked') ? inputs.attr('checked', true) : inputs.removeAttr('checked');
		appCheckbox(appobj);
	});
	function appCheckbox(appobj){
		checkobj = appobj.find('dl dt input:checked');
		if (checkobj.length == 0){
			appobj.find('p input').removeAttr('checked');
		} else {
			appobj.find('p input').attr('checked', true);
		}
	}
	$('.app dd input').click(function(){
		var dlobj = $(this).parent().parent();
		var appobj = $(this).parents('.app');
		checkobj = dlobj.find('dd input:checked');
		if (checkobj.length == 0){
			dlobj.find('dt input').removeAttr('checked');
			appCheckbox(appobj);
		} else {
			dlobj.find('dt input').attr('checked', true);
			appobj.find('p input').attr('checked', true);
		}
	});
	$('.add_role').click(function() {
		var obj =$(this).parents('#first').clone();
		obj.find('.add_role').remove();
		$('#last').before(obj);
	});
	$('#searchOk').click(function() {
		var value = $('#searchName').val();
		location.href="searchUser/key/"+value;
	});
	
});