<?php

/*
 * @version		$Id: default.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access'); ?>

<fieldset class="form-horizontal">
	<div class="control-group">
    	<label class="control-label" for="license"><?php echo JText::_('YENDIF_VIDEO_SHARE_LICENSE_KEY'); ?></label>
    	<div class="controls">
        	<input type="text" name="license" id="license" value="<?php echo $this->item->license; ?>" />
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="logo"><?php echo JText::_('YENDIF_VIDEO_SHARE_LOGO'); ?></label>
        <div class="controls">
           	<?php echo YendifVideoShareFields::FileUploader('logo', $this->item->logo); ?>
        </div>
	</div>
</fieldset>