<?php

/*
 * @version		$Id: default_security.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access'); ?>

<fieldset class="form-horizontal">
    <div class="control-group">
    	<label class="control-label" for="max_upload_size"><?php echo JText::_('YENDIF_VIDEO_SHARE_MAXIMUM_UPLOAD_SIZE'); ?></label>
    	<div class="controls">
        	<input type="text" name="max_upload_size" id="max_upload_size" value="<?php echo $this->item->max_upload_size; ?>" />
      		<span class="help-inline"><?php echo JText::_('YENDIF_VIDEO_SHARE_BYTES'); ?></span>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="allowed_extensions"><?php echo JText::_('YENDIF_VIDEO_SHARE_ALLOWED_EXTENSIONS'); ?></label>
    	<div class="controls">
        	<textarea name="allowed_extensions" rows="3" cols="50"><?php echo $this->item->allowed_extensions; ?></textarea>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="ignored_extensions"><?php echo JText::_('YENDIF_VIDEO_SHARE_IGNORED_EXTENSIONS'); ?></label>
    	<div class="controls">
        	<textarea name="ignored_extensions" rows="3" cols="50"><?php echo $this->item->ignored_extensions; ?></textarea>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="legal_mime_types"><?php echo JText::_('YENDIF_VIDEO_SHARE_LEGAL_MIME_TYPES'); ?></label>
    	<div class="controls">
        	<textarea name="legal_mime_types" rows="3" cols="50"><?php echo $this->item->legal_mime_types; ?></textarea>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="illegal_mime_types"><?php echo JText::_('YENDIF_VIDEO_SHARE_ILLEGAL_MIME_TYPES'); ?></label>
    	<div class="controls">
        	<textarea name="illegal_mime_types" rows="3" cols="50"><?php echo $this->item->illegal_mime_types; ?></textarea>
    	</div>
  	</div>
</fieldset>