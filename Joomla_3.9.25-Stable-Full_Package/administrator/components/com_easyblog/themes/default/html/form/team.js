EasyBlog.ready(function($) {

	window.insertTeam = function(id, name) {
		$('#<?php echo $id;?>-placeholder').val(name);
		$('#<?php echo $id;?>').val(id);

		EasyBlog.dialog().close();
	}

	$('[data-form-remove-team]').on('click', function() {
		var button = $(this);
		var parent = button.parents('[data-form-team-wrapper]');

		// Reset the form
		parent.find('input[type=hidden]').val('');
		parent.find('input[type=text]').val('');
	});

	$('[data-form-browse-team]').on('click', function() {
		EasyBlog.dialog({
			content: EasyBlog.ajax('admin/views/teamblogs/browse')
		});
	});

});
