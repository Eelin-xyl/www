
EasyBlog.require()
.script('admin/grid')
.done(function($)
{
	$('[data-grid-eb]').implement(EasyBlog.Controller.Grid);

	$.Joomla("submitbutton", function(task) {
		if (task == 'savekey') {
			$.Joomla('submitform', ['settings.saveApi']);
		}

		$.Joomla('submitform', [task]);
	});

});
