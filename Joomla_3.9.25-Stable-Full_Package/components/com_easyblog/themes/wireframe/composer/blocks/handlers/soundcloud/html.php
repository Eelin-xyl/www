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
<div class="eb-composer-placeholder eb-composer-link-placeholder text-center" data-soundcloud-form>
	<i class="eb-composer-placeholder-icon fa fa-soundcloud"></i>
	<b class="eb-composer-placeholder-title"><?php echo JText::_('COM_EASYBLOG_COMPOSER_BLOCKS_SOUNDCLOUD');?></b>
	<p class="eb-composer-placeholder-brief"><?php echo JText::_('COM_EASYBLOG_COMPOSER_BLOCKS_SOUNDCLOUD_DESC');?></p>
	<p class="eb-composer-placeholder-error t-text--danger hide" data-soundcloud-error><?php echo JText::_('COM_EASYBLOG_COMPOSER_BLOCKS_SOUNDCLOUD_EMPTY'); ?></p>
	<div class="o-input-group o-input-group--sm" style="width: 70%; margin: 0 auto;">
		<input type="text" class="o-form-control" type="text" value="" data-soundcloud-source placeholder="<?php echo JText::_('COM_EASYBLOG_COMPOSER_BLOCKS_SOUNDCLOUD_PLACEHOLDER', true);?>" />
		<span class="o-input-group__btn">
			<a href="javascript:void(0);" class="btn btn-eb-primary" data-soundcloud-insert><?php echo JText::_('COM_EASYBLOG_BLOCKS_SOUNDCLOUD_EMBED_BUTTON');?></a>
		</span>
	</div>
</div>

<div class="eb-composer-placeholder eb-composer-video-placeholder text-center hidden" data-soundcloud-loader>
	<i class="fa fa-refresh fa-spin mr-5"></i> <?php echo JText::_('COM_EASYBLOG_COMPOSER_BLOCKS_SOUNDCLOUD_RETRIEVING_EMBEDDED_CODES');?>
</div>

<div class="eb-composer-placeholder eb-composer-link-placeholder-preview hidden" data-soundcloud-preview>
</div>

