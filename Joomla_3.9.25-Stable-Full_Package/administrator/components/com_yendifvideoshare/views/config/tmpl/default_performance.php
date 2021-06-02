<?php

/*
 * @version		$Id: default_performance.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access'); ?>

<fieldset class="form-horizontal">
	<legend><?php echo JText::_('YENDIF_VIDEO_SHARE_PERFORMANCE_SETTINGS_HEADER'); ?></legend>
    <p class="muted"><?php echo JText::_('YENDIF_VIDEO_SHARE_PERFORMANCE_SETTINGS_DESCRIPTION'); ?></p>
    
    <div class="control-group">
    	<label class="control-label"><?php echo JText::_('YENDIF_VIDEO_SHARE_RESIZE_METHOD'); ?></label>
    	<div class="controls">
        	<?php echo $this->resize_methods; ?>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label"><?php echo JText::_('YENDIF_VIDEO_SHARE_GALLERY_IMAGES'); ?></label>
    	<div class="controls">
        	<input type="text" name="gallery_thumb_width" class="input-mini center" value="<?php echo $this->item->gallery_thumb_width; ?>" />&nbsp;x
        	<input type="text" name="gallery_thumb_height" class="input-mini center" value="<?php echo $this->item->gallery_thumb_height; ?>" />
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label"><?php echo JText::_('YENDIF_VIDEO_SHARE_POSTER_IMAGES'); ?></label>
    	<div class="controls">
        	<input type="text" name="poster_image_width" class="input-mini center" value="<?php echo $this->item->poster_image_width; ?>" />&nbsp;x
        	<input type="text" name="poster_image_height" class="input-mini center" value="<?php echo $this->item->poster_image_height; ?>" />
    	</div>
  	</div>
</fieldset>