<?php

/*
 * @version		$Id: default.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access'); 
JHTML::_('behavior.colorpicker');
?>

<fieldset class="form-horizontal">
    
    <div class="control-group">
    	<label class="control-label" for="ratio"><?php echo JText::_('YENDIF_VIDEO_SHARE_RATIO'); ?></label>
    	<div class="controls">
        	<input type="text" name="ratio" id="ratio" value="<?php echo $this->item->ratio; ?>" />
      		<p class="help-block"><?php echo JText::_('YENDIF_VIDEO_SHARE_RATIO_DESCRIPTION'); ?></p>
      		<ul>
        		<li><strong>0.5625</strong> - <?php echo JText::_('YENDIF_VIDEO_SHARE_WIDE_SCREEN_TV'); ?></li>
        		<li><strong>0.625</strong> - <?php echo JText::_('YENDIF_VIDEO_SHARE_MONITOR_SCREENS'); ?></li>
        		<li><strong>0.75</strong> - <?php echo JText::_('YENDIF_VIDEO_SHARE_CLASSIC_TV'); ?></li>
        		<li><strong>0.67</strong> - <?php echo JText::_('YENDIF_VIDEO_SHARE_PHOTO_CAMERA'); ?></li>
        		<li><strong>1</strong> - <?php echo JText::_('YENDIF_VIDEO_SHARE_SQUARE'); ?></li>
        		<li><strong>0.417</strong> - <?php echo JText::_('YENDIF_VIDEO_SHARE_CINEMASCOPE'); ?></li>
      		</ul>
    	</div>
  	</div>
    
    <div class="control-group">
        <label class="control-label" for="analytics"><?php echo JText::_('YENDIF_VIDEO_SHARE_ANALYTICS_CODE'); ?></label>
        <div class="controls">
			<input type="text" name="analytics" id="analytics" value="<?php echo $this->item->analytics; ?>" disabled />
            <span class="help-inline" style="color: red;">PRO Only</span>
        </div>
    </div>

</fieldset>

<fieldset class="form-horizontal">
	<legend><?php echo JText::_('YENDIF_VIDEO_SHARE_SKIN_SETTINGS'); ?></legend>
    
    <div class="control-group">
    	<label class="control-label" for="theme"><?php echo JText::_('YENDIF_VIDEO_SHARE_THEME'); ?></label>
    	<div class="controls">
        	<?php
            	echo YendifVideoShareFields::ListItems(
					'theme',
					array(
						'black' => JText::_('YENDIF_VIDEO_SHARE_BLACK'),
						'white' => JText::_('YENDIF_VIDEO_SHARE_WHITE')
					),							
					$this->item->theme
				);
			?>
        	<span class="help-inline"><?php echo JText::_('YENDIF_VIDEO_SHARE_THEME_DESCRIPTION'); ?></span>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="controlbar"><?php echo JText::_('YENDIF_VIDEO_SHARE_PROGRESS_BAR_COLOR'); ?></label>
    	<div class="controls">
        	<input type="text" data-position="bottom right" name="progress_bar_color" id="position-bottom-right" class="minicolors minicolors-input jomcl-xmini" value="<?php echo $this->item->progress_bar_color;  ?>" />
        	
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="controlbar"><?php echo JText::_('YENDIF_VIDEO_SHARE_CONTROLBAR'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListBoolean('controlbar', $this->item->controlbar); ?>
        	<span class="help-inline"><?php echo JText::_('YENDIF_VIDEO_SHARE_CONTROLBAR_DESCRIPTION'); ?></span>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="playbtn"><?php echo JText::_('YENDIF_VIDEO_SHARE_PLAY_BUTTON'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListBoolean('playbtn', $this->item->playbtn); ?>
        	<span class="help-inline"><?php echo JText::_('YENDIF_VIDEO_SHARE_PLAY_BUTTON_DESCRIPTION'); ?></span>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="playpause"><?php echo JText::_('YENDIF_VIDEO_SHARE_PLAYPAUSE'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListBoolean('playpause', $this->item->playpause); ?>
        	<span class="help-inline"><?php echo JText::_('YENDIF_VIDEO_SHARE_PLAYPAUSE_DESCRIPTION'); ?></span>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="currenttime"><?php echo JText::_('YENDIF_VIDEO_SHARE_CURRENTTIME'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListBoolean('currenttime', $this->item->currenttime); ?>
        	<span class="help-inline"><?php echo JText::_('YENDIF_VIDEO_SHARE_CURRENTTIME_DESCRIPTION'); ?></span>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="progress"><?php echo JText::_('YENDIF_VIDEO_SHARE_PROGRESS'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListBoolean('progress', $this->item->progress); ?>
        	<span class="help-inline"><?php echo JText::_('YENDIF_VIDEO_SHARE_PROGRESS_DESCRIPTION'); ?></span>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="duration"><?php echo JText::_('YENDIF_VIDEO_SHARE_DURATION'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListBoolean('duration', $this->item->duration); ?>
        	<span class="help-inline"><?php echo JText::_('YENDIF_VIDEO_SHARE_CONTROLBAR_DURATION_DESCRIPTION'); ?></span>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="volumebtn"><?php echo JText::_('YENDIF_VIDEO_SHARE_VOLUME_BUTTON'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListBoolean('volumebtn', $this->item->volumebtn); ?>
        	<span class="help-inline"><?php echo JText::_('YENDIF_VIDEO_SHARE_VOLUME_BUTTON_DESCRIPTION'); ?></span>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="fullscreen"><?php echo JText::_('YENDIF_VIDEO_SHARE_FULLSCREEN'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListBoolean('fullscreen', $this->item->fullscreen); ?>
        	<span class="help-inline"><?php echo JText::_('YENDIF_VIDEO_SHARE_FULLSCREEN_DESCRIPTION'); ?></span>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="embed"><?php echo JText::_('YENDIF_VIDEO_SHARE_EMBED'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListBoolean('embed', $this->item->embed); ?>
        	<span class="help-inline"><?php echo JText::_('YENDIF_VIDEO_SHARE_EMBED_DESCRIPTION'); ?></span>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="share"><?php echo JText::_('YENDIF_VIDEO_SHARE_SOCIAL_SHARE'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListBoolean('share', $this->item->share, 'disabled'); ?>
        	<span class="help-inline" style="color: red;">PRO Only</span>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="download"><?php echo JText::_('YENDIF_VIDEO_SHARE_DOWNLOAD'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListBoolean('download', $this->item->download, 'disabled'); ?>
        	<span class="help-inline" style="color: red;">PRO Only</span>
    	</div>
  	</div>
</fieldset>

<fieldset class="form-horizontal">
	<legend><?php echo JText::_('YENDIF_VIDEO_SHARE_PLAYBACK_SETTINGS'); ?></legend>
    
    <div class="control-group">
    	<label class="control-label" for="volume"><?php echo JText::_('YENDIF_VIDEO_SHARE_VOLUME'); ?></label>
    	<div class="controls">
        	<input type="text" name="volume" id="volume" value="<?php echo $this->item->volume; ?>" />
        	<span class="help-inline"><?php echo JText::_('YENDIF_VIDEO_SHARE_VOLUME_DESCRIPTION'); ?></span>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="autoplay"><?php echo JText::_('YENDIF_VIDEO_SHARE_AUTOPLAY'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListBoolean('autoplay', $this->item->autoplay); ?>
        	<span class="help-inline"><?php echo JText::_('YENDIF_VIDEO_SHARE_AUTOPLAY_DESCRIPTION'); ?></span>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="loop"><?php echo JText::_('YENDIF_VIDEO_SHARE_LOOP'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListBoolean('loop', $this->item->loop); ?>
        	<span class="help-inline"><?php echo JText::_('YENDIF_VIDEO_SHARE_LOOP_DESCRIPTION'); ?></span>
    	</div>
  	</div>
</fieldset>

<fieldset class="form-horizontal">
	<legend><?php echo JText::_('YENDIF_VIDEO_SHARE_PLAYLIST_SETTINGS'); ?></legend>
    
    <div class="control-group">
    	<label class="control-label" for="playlist_position"><?php echo JText::_('YENDIF_VIDEO_SHARE_PLAYLIST_POSITION'); ?></label>
    	<div class="controls">
        	<?php
            	echo YendifVideoShareFields::ListItems(
					'playlist_position',
					array(
						'right'  => JText::_('YENDIF_VIDEO_SHARE_RIGHT'),
						'bottom' => JText::_('YENDIF_VIDEO_SHARE_BOTTOM')
					),							
					$this->item->playlist_position
				);
			?>
        	<span class="help-inline"><?php echo JText::_('YENDIF_VIDEO_SHARE_PLAYLIST_POSITION_DESCRIPTION'); ?></span>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="playlist_width"><?php echo JText::_('YENDIF_VIDEO_SHARE_PLAYLIST_WIDTH'); ?></label>
    	<div class="controls">
        	<input type="text" name="playlist_width" id="playlist_width" value="<?php echo $this->item->playlist_width; ?>" />
        	<span class="help-inline"><?php echo JText::_('YENDIF_VIDEO_SHARE_PLAYLIST_WIDTH_DESCRIPTION'); ?></span>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="playlist_height"><?php echo JText::_('YENDIF_VIDEO_SHARE_PLAYLIST_HEIGHT'); ?></label>
    	<div class="controls">
        	<input type="text" name="playlist_height" id="playlist_height" value="<?php echo $this->item->playlist_height; ?>" />
        	<span class="help-inline"><?php echo JText::_('YENDIF_VIDEO_SHARE_PLAYLIST_HEIGHT_DESCRIPTION'); ?></span>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="playlist_title_limit"><?php echo JText::_('YENDIF_VIDEO_SHARE_TITLE_LENGH'); ?></label>
    	<div class="controls">
        	<input type="text" name="playlist_title_limit" id="playlist_title_limit" value="<?php echo $this->item->playlist_title_limit; ?>" />
        	<span class="help-inline"><?php echo JText::_('YENDIF_VIDEO_SHARE_NO_OF_CHARACTERS'); ?></span>
    	</div>
  	</div>
    
    <div class="control-group">
    	<label class="control-label" for="autoplaylist"><?php echo JText::_('YENDIF_VIDEO_SHARE_AUTOPLAYLIST'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListBoolean('autoplaylist', $this->item->autoplaylist); ?>
        	<span class="help-inline"><?php echo JText::_('YENDIF_VIDEO_SHARE_AUTOPLAYLIST_DESCRIPTION'); ?></span>
    	</div>
  	</div>
</fieldset>

<fieldset class="form-horizontal">
	<legend><?php echo JText::_('YENDIF_VIDEO_SHARE_ADVERTISEMENTS'); ?></legend>
    
    <div class="control-group">
        <label class="control-label" for="enable_adverts"><?php echo JText::_('AD_TYPE'); ?></label>
        <div class="controls">
        	<?php
			echo YendifVideoShareFields::ListAdsType(
				'ad_engine',
				  array('custom'=>JText::_( 'YENDIF_VIDEO_SHARE_CUSTOM' ),
						'vast'=>JText::_( 'YENDIF_VIDEO_SHARE_VAST_ADS' )),							
				  $this->item->ad_engine
			  );
			
			?>
            <span class="help-inline" style="color: red;">PRO Only</span>
       </div>
    </div>
    <!-- ad Custom --> 
    <div id="yendif-advert-custom" class="yendif-advert-custom">
        <div class="control-group">
            <label class="control-label" for="enable_adverts"><?php echo JText::_('YENDIF_VIDEO_SHARE_ENABLE_ADVERTS'); ?></label>
            <div class="controls">
                <?php
                    echo YendifVideoShareFields::ListItems(
                        'enable_adverts',
                        array(
                            'none'          => '-- '.JText::_('YENDIF_VIDEO_SHARE_NONE').' --',				      
                            'preroll_only'  => JText::_('YENDIF_VIDEO_SHARE_PREROLL_ONLY'),
                            'postroll_only' => JText::_('YENDIF_VIDEO_SHARE_POSTROLL_ONLY'),
                            'both'          => JText::_('YENDIF_VIDEO_SHARE_BOTH')
                        ),							
                        $this->item->enable_adverts,
						'disabled'
                    );
                ?>
                <span class="help-inline" style="color: red;">PRO Only</span>
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="show_adverts_timeframe"><?php echo JText::_('YENDIF_VIDEO_SHARE_SHOW_ADVERTS_TIMEFRAME'); ?></label>
            <div class="controls">
                <?php echo YendifVideoShareFields::ListBoolean('show_adverts_timeframe', $this->item->show_adverts_timeframe,'disabled'); ?>
                <span class="help-inline" style="color: red;">PRO Only</span>
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="can_skip_adverts"><?php echo JText::_('YENDIF_VIDEO_SHARE_CAN_SKIP_ADVERTS'); ?></label>
            <div class="controls">
                <?php echo YendifVideoShareFields::ListBoolean('can_skip_adverts', $this->item->can_skip_adverts, 'disabled'); ?>
                <span class="help-inline" style="color: red;">PRO Only</span>
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="show_skip_adverts_on"><?php echo JText::_('YENDIF_VIDEO_SHARE_SHOW_SKIP_ADVERTS_ON'); ?></label>
            <div class="controls">
                <input type="text" name="show_skip_adverts_on" id="show_skip_adverts_on" value="<?php echo $this->item->show_skip_adverts_on; ?>" disabled/>
                <span class="help-inline" style="color: red;">PRO Only</span>
            </div>
        </div>
   </div>
   
   <!-- ad ima adTagURL --> 
   <div id="yendif-advert-vast" class="yendif-advert-vast">   
        <div class="control-group">
            <label class="control-label ima-advert-label" for="vasturl"><?php echo JText::_('YENDIF_VIDEO_SHARE_IMA_TAGS'); ?></label>
            <div class="controls">
                <textarea  name="vasturl" class="ima-max-textarea" rows="6" cols="100" aria-invalid="false" disabled><?php echo  $this->item->vasturl; ?></textarea>
                <span class="help-inline" style="color: red;">PRO Only</span>
            </div>
        </div>
   </div>
</fieldset>

<fieldset class="form-horizontal">
	<legend><?php echo JText::_('YENDIF_VIDEO_SHARE_PRIVACY_SETTINGS'); ?></legend>
    
    <div class="control-group">
    	<label class="control-label" for="analytics"><?php echo JText::_('YENDIF_VIDEO_SHARE_SHOW_CONSENT'); ?></label>
    	<div class="controls">
        	<?php echo YendifVideoShareFields::ListBoolean('show_consent', $this->item->show_consent); ?>
      		<span class="help-inline"><?php echo JText::_('YENDIF_VIDEO_SHARE_SHOW_CONSENT_DESCRIPTION'); ?></span>
    	</div>
  	</div>
</fieldset>

<script type="text/javascript">	
	jQuery(document).ready(function () {
		yendifChangeAdsType('<?php echo $this->item->ad_engine; ?>');
	 }); 
	 
	  function yendifChangeAdsType(type) {	

		switch(type) {
			case 'custom' :
				document.getElementById('yendif-advert-custom').style.display = "";
				document.getElementById('yendif-advert-vast').style.display  = "none";						
				break;		
			case 'vast' :	
				document.getElementById('yendif-advert-custom').style.display = "none";
				document.getElementById('yendif-advert-vast').style.display  = "";			
				break;
		};
	};

</script>