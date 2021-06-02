<?php

/*
 * @version		$Id: default_sef.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access'); ?>

<fieldset class="form-horizontal">
	<div class="control-group">
    	<label class="control-label" for="sef_cat"><?php echo JText::_('YENDIF_VIDEO_SHARE_SEF_PREFIX_CATEGORY'); ?></label>
    	<div class="controls">
        	<input type="text" name="sef_cat" id="sef_cat" value="<?php echo $this->item->sef_cat; ?>" />
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="sef_video"><?php echo JText::_('YENDIF_VIDEO_SHARE_SEF_PREFIX_VIDEO'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListSefOptions( 'sef_video', $this->item->sef_video ); ?>                 
     		<input type="text" name="sef_video_prefix" id="sef_video_prefix" class="hide" value="<?php echo $this->item->sef_video_prefix; ?>" />
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="sef_sptr"><?php echo JText::_('YENDIF_VIDEO_SHARE_SEF_SEPARATOR'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListSefOptions('sef_sptr', $this->item->sef_sptr); ?>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="sef_position"><?php echo JText::_('YENDIF_VIDEO_SHARE_SEF_POSITION'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListSefOptions('sef_position', $this->item->sef_position); ?>
    	</div>
  	</div>
</fieldset>