<?php
/**
* @package      EasyBlog
* @copyright    Copyright (C) 2010 - 2017 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<dialog>
	<width>400</width>
	<height>120</height>
	<selectors type="json">
	{
		"{closeButton}" : "[data-close-button]",
		"{form}" : "[data-form-response]",
		"{deleteButton}" : "[data-delete-button]"
	}
	</selectors>
	<bindings type="javascript">
	{
		"{closeButton} click": function() {
			this.parent.close();
		}
	}
	</bindings>
	<title><?php echo JText::_($title); ?></title>
	<content>
		<p class="mt-5">
			<?php echo JText::_($content);?>
		</p>
	</content>
	<buttons>
		<button type="button" class="btn btn-default btn-sm" data-close-button><?php echo JText::_('COM_EASYBLOG_CANCEL_BUTTON');?></button>
		<button type="button" class="btn btn-danger btn-sm" data-delete-button><?php echo JText::_('COM_EASYBLOG_DELETE_BUTTON');?></button>
	</buttons>
</dialog>
