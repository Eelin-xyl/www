<?php

/*
 * @version		$Id: default_layout.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access'); ?>

<fieldset class="form-horizontal">
	<div class="control-group">
    	<label class="control-label" for="jquery"><?php echo JText::_('YENDIF_VIDEO_SHARE_BOOTSTRAP_VERSION'); ?></label>
    	<div class="controls">
        	<?php
            	echo YendifVideoShareFields::ListItems(
					'bootstrap_version',
					array(
						'2' => 'No',
						'3' => 'Yes'
					),							
					$this->item->bootstrap_version
				);
			?>
        	
    	</div>
  	</div>
    
	<div class="control-group">
    	<label class="control-label" for="default_image"><?php echo JText::_('YENDIF_VIDEO_SHARE_DEFAULT_IMAGE'); ?></label>
    	<div class="controls">
            <?php echo YendifVideoShareFields::FileUploader('default_image', $this->item->default_image); ?>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="show_views"><?php echo JText::_('YENDIF_VIDEO_SHARE_SHOW_VIEWS'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListDisplay('show_views', $this->item->show_views); ?>
    	</div>
  	</div>
    
    
    <div class="control-group">
    	<label class="control-label" for="show_rating"><?php echo JText::_('YENDIF_VIDEO_SHARE_SHOW_RATING'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListDisplay('show_rating', $this->item->show_rating); ?>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="allow_guest_rating"><?php echo JText::_('YENDIF_VIDEO_SHARE_ALLOW_GUEST_USER_TO_RATING'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListBoolean('allow_guest_rating', $this->item->allow_guest_rating); ?>
    	</div>
  	</div>

    
    <div class="control-group">
    	<label class="control-label" for="show_likes"><?php echo JText::_('YENDIF_VIDEO_SHARE_SHOW_LIKES'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListDisplay('show_likes', $this->item->show_likes); ?>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="allow_guest_like"><?php echo JText::_('YENDIF_VIDEO_SHARE_ALLOW_GUEST_USER_TO_LIKE_DISLIKE'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListBoolean('allow_guest_like', $this->item->allow_guest_like); ?>
    	</div>
  	</div>
</fieldset>

<fieldset class="form-horizontal">
	<legend><?php echo JText::_('YENDIF_VIDEO_SHARE_GALLERY_SETTINGS'); ?></legend>
    
    <div class="control-group">
    	<label class="control-label" for="no_of_rows"><?php echo JText::_('YENDIF_VIDEO_SHARE_NO_OF_ROWS'); ?></label>
    	<div class="controls">
        	<input type="text" name="no_of_rows" value="<?php echo $this->item->no_of_rows; ?>" />
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="no_of_cols"><?php echo JText::_('YENDIF_VIDEO_SHARE_NO_OF_COLS'); ?></label>
    	<div class="controls">
        	<input type="text" name="no_of_cols" value="<?php echo $this->item->no_of_cols; ?>" />
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="show_excerpt"><?php echo JText::_('YENDIF_VIDEO_SHARE_SHOW_DESCRIPTION'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListDisplay('show_excerpt', $this->item->show_excerpt); ?>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="playlist_desc_limit"><?php echo JText::_('YENDIF_VIDEO_SHARE_DESCRIPTION_LENGTH'); ?></label>
    	<div class="controls">
        	<input type="text" name="playlist_desc_limit" id="playlist_desc_limit" value="<?php echo $this->item->playlist_desc_limit; ?>" />
        	<span class="help-inline"><?php echo JText::_('YENDIF_VIDEO_SHARE_NO_OF_CHARACTERS'); ?></span>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="show_media_count"><?php echo JText::_('YENDIF_VIDEO_SHARE_SHOW_MEDIA_COUNT'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListDisplay('show_media_count', $this->item->show_media_count); ?>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="enable_popup"><?php echo JText::_('YENDIF_VIDEOSHARE_SHOW_VIDEOS_POPUP'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListBoolean('enable_popup', $this->item->enable_popup , 'disabled' ); ?>
            <span class="help-inline" style="color: red;">PRO Only</span>
    	</div>
  	</div>
</fieldset>

<fieldset class="form-horizontal">
	<legend><?php echo JText::_('YENDIF_VIDEO_SHARE_VIDEO_PAGE_SETTINGS'); ?></legend>
    
    <div class="control-group">
    	<label class="control-label" for="show_title"><?php echo JText::_('YENDIF_VIDEO_SHARE_VIDEO_TITLE'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListDisplay('show_title', $this->item->show_title); ?>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="show_description"><?php echo JText::_('YENDIF_VIDEO_SHARE_VIDEO_DESCRIPTION'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListDisplay('show_description', $this->item->show_description); ?>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="show_related"><?php echo JText::_('YENDIF_VIDEO_SHARE_RELATED_VIDEOS'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListDisplay('show_related', $this->item->show_related); ?>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="show_category"><?php echo JText::_('YENDIF_VIDEO_SHARE_CATEGORY_NAME'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListDisplay('show_category', $this->item->show_category); ?>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="show_user"><?php echo JText::_('YENDIF_VIDEO_SHARE_SHOW_USER'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListDisplay('show_user', $this->item->show_user); ?>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="show_date"><?php echo JText::_('YENDIF_VIDEO_SHARE_SHOW_DATE_ADDED'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListDisplay('show_date', $this->item->show_date); ?>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="show_date"><?php echo JText::_('YENDIF_VIDEO_SHARE_SEARCH_BOX'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListDisplay('show_search', $this->item->show_search); ?>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="share_script"><?php echo JText::_('YENDIF_VIDEO_SHARE_SOCIAL_SHARE_SCRIPT'); ?></label>
    	<div class="controls">
        	<textarea name="share_script" id="share_script" placeholder="<?php echo JText::_('YENDIF_VIDEO_SHARE_SOCIAL_SHARE_SCRIPT_DESCRIPTION'); ?>"><?php echo $this->item->share_script; ?></textarea>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="comments"><?php echo JText::_('YENDIF_VIDEO_SHARE_COMMENTS_TYPE'); ?></label>
    	<div class="controls">
        	<?php
            	echo YendifVideoShareFields::ListItems(
					'comments',
					array(
						'none'      => JText::_('YENDIF_VIDEO_SHARE_NONE'),
					  	'facebook'  => JText::_('YENDIF_VIDEO_SHARE_FACEBOOK_COMMENTS'),
					  	'jcomments' => JText::_('YENDIF_VIDEO_SHARE_JCOMMENTS'),
					  	'komento'   => JText::_('YENDIF_VIDEO_SHARE_KOMENTO')),							
					$this->item->comments
				);
			?>
        	<span id="help-inline">
            	<a href="https://yendifplayer.com/joomla-video-share-third-party-integration.html" target="_blank">
					<?php echo JText::_('YENDIF_VIDEO_SHARE_JCOMMENTS_HELP'); ?>
            	</a>
        	</span>
    	</div>
  	</div>
    
    <div class="yendif-facebook-options">
    	<div class="control-group">
    		<label class="control-label" for="fb_app_id"><?php echo JText::_('YENDIF_VIDEO_SHARE_FACEBOOK_APP_ID'); ?></label>
    		<div class="controls">
        		<input type="text" name="fb_app_id" id="fb_app_id" value="<?php echo $this->item->fb_app_id; ?>" />
    		</div>
  		</div>
    
    	<div class="control-group">
    		<label class="control-label" for="fb_post_count"><?php echo JText::_('YENDIF_VIDEO_SHARE_NO_OF_POSTS'); ?></label>
    		<div class="controls">
        		<input type="text" name="fb_post_count" id="fb_post_count" value="<?php echo $this->item->fb_post_count; ?>" />
    		</div>
  		</div>
    
    	<div class="control-group">
    		<label class="control-label" for="fb_color_scheme"><?php echo JText::_('YENDIF_VIDEO_SHARE_COLOR_SCHEME'); ?></label>
    		<div class="controls">
        		<?php
            		echo YendifVideoShareFields::ListItems(
						'fb_color_scheme',
						array(
							'dark'  => JText::_('YENDIF_VIDEO_SHARE_DARK'),
							'light' => JText::_('YENDIF_VIDEO_SHARE_LIGHT')
						),							
						$this->item->fb_color_scheme
					);
				?>
    		</div>
  		</div>
    </div>
</fieldset>


<fieldset class="form-horizontal">
	<legend><?php echo JText::_('YENDIF_VIDEO_SHARE_RSS_FEED_SETTING'); ?></legend>
    
    <div class="control-group">
    	<label class="control-label" for="show_feed"><?php echo JText::_('YENDIF_VIDEO_SHARE_SHOW_RSS_FEED'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListDisplay('show_feed', $this->item->show_feed); ?>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="feed_icon"><?php echo JText::_('YENDIF_VIDEO_SHARE_RSS_FEED_ICON'); ?></label>
    	<div class="controls">
            <?php echo YendifVideoShareFields::FileUploader('feed_icon', $this->item->feed_icon); ?>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="feed_limit"><?php echo JText::_('YENDIF_VIDEO_SHARE_RSS_FEED_LIMIT'); ?></label>
    	<div class="controls">
        	<input type="text" name="feed_limit" id="feed_limit" value="<?php echo $this->item->feed_limit; ?>" />
            
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="feed_search"><?php echo JText::_('YENDIF_VIDEO_SHARE_RSS_FEED_IN_SEARCH'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListDisplay('feed_search', $this->item->feed_search); ?>
    	</div>
  	</div>
</fieldset>