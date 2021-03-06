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
<div class="eb-composer-placeholder eb-composer-link-placeholder text-center" data-gist-form>
	<i class="eb-composer-placeholder-icon fa fa-github-alt"></i>
	<b class="eb-composer-placeholder-title"><?php echo JText::_('COM_EASYBLOG_COMPOSER_BLOCKS_GIST_SHARE_GIST');?></b>
	<p class="eb-composer-placeholder-brief"><?php echo JText::_('COM_EASYBLOG_COMPOSER_BLOCKS_GIST_SHARE_GIST_NOTE');?></p>
	<p class="eb-composer-placeholder-error t-text--danger hide" data-gist-error><?php echo JText::_('COM_EASYBLOG_COMPOSER_BLOCKS_GIST_EMPTY'); ?></p>
	<div class="o-input-group o-input-group--sm" style="width: 70%; margin: 0 auto;">
		<input type="text" class="o-form-control" type="text" value="" data-gist-source placeholder="<?php echo JText::_('COM_EASYBLOG_COMPOSER_BLOCKS_GIST_PLACEHOLDER', true);?>" />
		<span class="o-input-group__btn">
			<a href="javascript:void(0);" class="btn btn-eb-primary" data-gist-insert><?php echo JText::_('COM_EASYBLOG_BLOCKS_GIST_ADD_GIST');?></a>
		</span>
	</div>
</div>

<div class="eb-composer-placeholder eb-composer-link-placeholder-preview hidden" data-gist-preview>
</div>

