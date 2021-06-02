<?php
/**
* @package      EasyBlog
* @copyright    Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<div class="eb-composer-placeholder eb-composer-link-placeholder text-center" data-instagram-form>
	<i class="eb-composer-placeholder-icon fa fa-instagram"></i>
	<b class="eb-composer-placeholder-title"><?php echo JText::_('COM_EASYBLOG_COMPOSER_BLOCKS_INSTAGRAM_SHARE_PHOTO');?></b>
	<p class="eb-composer-placeholder-brief"><?php echo JText::_('COM_EASYBLOG_COMPOSER_BLOCKS_INSTAGRAM_SHARE_PHOTO_DESC');?></p>
	<p class="eb-composer-placeholder-error t-text--danger hide" data-instagram-error><?php echo JText::_('COM_EASYBLOG_COMPOSER_BLOCKS_INSTAGRAM_EMPTY'); ?></p>
	<div class="o-input-group o-input-group--sm" style="width: 70%; margin: 0 auto;">
		<input type="text" class="o-form-control" type="text" value="" data-instagram-source placeholder="<?php echo JText::_('COM_EASYBLOG_COMPOSER_BLOCKS_INSTAGRAM_PLACEHOLDER', true);?>" />
		<span class="o-input-group__btn">
			<a href="javascript:void(0);" class="btn btn-eb-primary" data-instagram-insert><?php echo JText::_('COM_EASYBLOG_BLOCKS_INSTAGRAM_SHARE_PHOTO_BUTTON');?></a>
		</span>
	</div>
</div>

<div class="eb-composer-placeholder eb-composer-link-placeholder-preview hidden" data-instagram-preview>
</div>

