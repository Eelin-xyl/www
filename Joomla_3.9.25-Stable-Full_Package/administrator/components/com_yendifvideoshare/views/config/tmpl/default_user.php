<?php

/*
 * @version		$Id: default_user.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access'); ?>


<fieldset class="form-horizontal">
	<div class="control-group">
    	<label class="control-label" for="allow_upload"><?php echo JText::_('YENDIF_VIDEO_SHARE_RESTRICT_UPLOAD'); ?></label>
        <div class="controls">
        	<div class="yendif-allow-users">
            	<input type="hidden" name="allow_upload" value="0" />
    			<input type="checkbox" name="allow_upload" value="<?php echo $this->item->allow_upload;?>"<?php echo ($this->item->allow_upload == 1) ? 'checked' : ''; ?>  />
                <label class="help-inline"><?php echo JText::_('YENDIF_VIDEO_SHARE_RESTRICT_GEN_UPLOAD'); ?></label>
            </div>
            
            <div class="yendif-allow-users">
            	<input type="hidden" name="allow_youtube_upload" value="0" />
    			<input type="checkbox" name="allow_youtube_upload" value="<?php echo $this->item->allow_youtube_upload; ?>" <?php echo ($this->item->allow_youtube_upload == 1) ? 'checked' : ''; ?>>
                <label class="help-inline"><?php echo JText::_('YENDIF_VIDEO_SHARE_RESTRICT_YOUTUBE_UPLOAD'); ?></label>
            </div>
            
            <div class="yendif-allow-users">
            	<input type="hidden" name="allow_rtmp_upload" value="0" />
    			<input type="checkbox" name="allow_rtmp_upload" value="<?php echo $this->item->allow_rtmp_upload; ?>"<?php echo ($this->item->allow_rtmp_upload == 1) ? 'checked' : ''; ?>>
                <label class="help-inline"><?php echo JText::_('YENDIF_VIDEO_SHARE_RESTRICT_RTMP_UPLOAD'); ?></label>
            </div>
                        
            <div class="yendif-allow-users">
            	<input type="hidden" name="allow_subtitle" value="0" />
    			<input type="checkbox" name="allow_subtitle" value="<?php echo $this->item->allow_subtitle;?>" <?php echo ($this->item->allow_subtitle == 1) ? 'checked' : ''; ?>>
                <label class="help-inline"><?php echo JText::_('YENDIF_VIDEO_SHARE_RESTRICT_SUBTITLE'); ?></label>
            </div>
            
            <div class="yendif-allow-users">
            	<input type="hidden" name="schedule_video_publishing" value="0" />
    			<input type="checkbox" name="schedule_video_publishing" value="<?php echo $this->item->schedule_video_publishing; ?>" <?php echo ($this->item->schedule_video_publishing == 1) ? 'checked' : ''; ?>>
                <label class="help-inline"><?php echo JText::_('YENDIF_VIDEO_SHARE_ADD_START_END_PUBLISHING'); ?></label>
                <span class="help-inline"><?php echo JText::_('YENDIF_VIDEO_SHARE_ADD_START_END_PUBLISHING_DESC'); ?></span>
            </div>
        </div>
    </div>
	<div class="control-group">
    	<label class="control-label" for="autopublish"><?php echo JText::_('YENDIF_VIDEO_SHARE_AUTOPUBLISH'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListBoolean('autopublish', $this->item->autopublish); ?> 
        	<span class="help-inline"><?php echo JText::_('YENDIF_VIDEO_SHARE_AUTOPUBLISH_DESCRIPTION'); ?></span>
    	</div>
  	</div>
</fieldset>