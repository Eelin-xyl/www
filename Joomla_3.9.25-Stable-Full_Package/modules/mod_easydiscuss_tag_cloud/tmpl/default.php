<?php
/**
* @package		EasyDiscuss
* @copyright	Copyright (C) Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyDiscuss is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<div id="ed" class="ed-mod ed-mod--tagcloud <?php echo $lib->getModuleWrapperClass();?>">
	<div class="ed-mod-card">
		<div class="ed-mod-card__body">
			<?php if ($tagcloud) { ?>
				<?php foreach($tagcloud as $tag) { ?>
					<a href="<?php echo EDR::getTagRoute($tag->id . ':' . $tag->alias);?>" class="tag-cloud"
						style="font-size: <?php echo floor($tag->fontsize);?>px;"
						>
						<?php echo ED::string()->escape($tag->title); ?>
					</a>
				<?php } ?>
			<?php } else { ?>
				<?php echo JText::_('MOD_EASYDISCUSSTAGCLOUD_NO_TAG'); ?>
			<?php } ?>
		</div>
	</div>
</div>