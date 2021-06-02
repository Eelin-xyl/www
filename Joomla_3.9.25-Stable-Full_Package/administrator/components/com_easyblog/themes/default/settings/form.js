EasyBlog.require()
.library('scrollTo')
.done(function($) {

	$.Joomla("submitbutton", function(task) {

		if (task == 'export') {
			window.location.href = '<?php echo JURI::root();?>administrator/index.php?option=com_easyblog&view=settings&format=raw&layout=export&tmpl=component';
			return;
		}

		if (task == 'import') {
			EasyBlog.dialog({
				"content": EasyBlog.ajax('admin/views/settings/import')
			});

			return;
		}

		$.Joomla("submitform", [task]);
	});

	window.switchFBPosition = function() {
		if( $('#main_facebook_like_position').val() == '1' )
		{
			$('#fb-likes-standard').hide();
			if( $('#standard').attr('checked') == true)
				$('#button_count').attr('checked', true);
		}
		else
		{
			$('#fb-likes-standard').show();
		}
	}

	<?php if ($activeTab) { ?>
		$('[data-form-tabs][href=#<?php echo $activeTab;?>]')
			.click();
	<?php } ?>

	// Append the settings search to the toolbar
	var searchWrapper = $('[data-search-wrapper]');
	var searchResult = $('[data-search-result]');
	var searchInput = $('[data-settings-search]');

	searchWrapper
		.appendTo('#toolbar')
		.removeClass('hidden');


	searchInput.on('keyup', $.debounce(function() {
		var search = $(this).val();

		if (search === "") {
			searchResult.addClass('hidden');

			return;
		}

		EasyBlog.ajax('admin/views/settings/search', {
			'text': search
		}).done(function(output) {
			searchResult
				.html(output)
				.removeClass('hidden');
		});

	}, 250));

	$('body').on('click', function(event) {
		var target = $(event.target);

		if (target.is(searchInput) || target.is(searchResult) || target.is(searchWrapper) || target.parents().is(searchResult)) {
			return;
		}

		searchResult.addClass('hidden');
	});

	<?php if ($goto) { ?>
	var element = $('#<?php echo $goto;?>');
	var wrapper = element.parents('.form-group');

	wrapper.css({
		'background': '#fff9c4',
		'transition': 'background 1.0s ease-in-out'
	});

	var resetBackground = function() {
		wrapper.css({
			'background': '#fff'
		});
	};

	setInterval(function() {
		resetBackground();
	}, 5000);

	$.scrollTo(element);
	<?php } ?>
});