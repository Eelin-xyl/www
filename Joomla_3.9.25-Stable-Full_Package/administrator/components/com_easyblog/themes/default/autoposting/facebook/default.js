EasyBlog.require()
.done(function($) {

    var oauthURIinput = $('[data-oauthuri-input]');
    var oauthURIbutton = $('[data-oauthuri-button]');

    oauthURIbutton.on('click', function() {

		// change tooltip display word
		$(this).attr('data-original-title', '<?php echo JText::_('COM_EB_COPIED_TOOLTIP')?>').tooltip('show');

    	// retrieve the input id
		var oauthInputId = $(this).siblings().attr('id');
		var selectedText = document.getElementById(oauthInputId);

		selectedText.select();
		document.execCommand("Copy");
    });

    // change back orginal value after mouse out
    oauthURIbutton.on('mouseout', function() {

		// change tooltip display word
		$(this).attr('data-original-title', '<?php echo JText::_('COM_EB_COPY_TOOLTIP')?>').tooltip('show');
    });

	$('#integrations_facebook_introtext_message').on('click', function() {
		var checked = $(this).is(':checked');

		if (checked) {
			$('[data-oauth-contentSource]').removeClass('hidden');
			$('[data-oauth-contentLength]').removeClass('hidden');
		} else {
			$('[data-oauth-contentSource]').addClass('hidden');
			$('[data-oauth-contentLength]').addClass('hidden');
		}
	});
});
