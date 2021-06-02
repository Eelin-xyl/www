EasyBlog.ready(function($) {

	var joomlaClass = "<?php echo EB::isJoomla4() ? 'is-joomla-4' : 'is-joomla-3'; ?>";

	$('body').addClass('com_easyblog ' + joomlaClass);

	// Hide joomla's sidebar wrapper
	var sidebar = $('#eb [data-sidebar]');
	var sidebarHtml = sidebar.html();

	var joomlaSidebar = $('#sidebarmenu');
	var joomlaSidebarNav = joomlaSidebar.find('> nav');


	var joomlaMenu = joomlaSidebarNav.find('ul.main-nav');

	joomlaMenu.hide();

	var joomlaSidebarTemplate = $('[data-j4-sidebar]').html();

	joomlaMenu.prepend(joomlaSidebarTemplate);

	// Append our own sidebar
	joomlaSidebarNav.append(sidebarHtml);

	var easyblogMenu = joomlaSidebarNav.find('ul.app-sidebar-nav');

	$(document).on('click.back.joomla', '[data-back-joomla]', function() {
		joomlaMenu.show();
		easyblogMenu.hide();
	});

	$(document).on('click.back.easyblog', '[data-back-easyblog]', function() {
		joomlaMenu.hide();
		easyblogMenu.show();
	});
});
