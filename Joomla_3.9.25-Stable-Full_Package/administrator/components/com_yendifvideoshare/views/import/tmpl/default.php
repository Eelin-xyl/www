<?php

/*
 * @version		$Id: default.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.formvalidation');
JHtml::_('jquery.framework');

$document = JFactory::getDocument();
$document->addScript( YendifVideoShareUtils::prepareURL('media/yendifvideoshare/assets/admin/js/ytembed.js') );
$document->addScript( YendifVideoShareUtils::prepareURL('media/yendifvideoshare/assets/admin/js/yendifvideoshare.js') );
$document->addScriptDeclaration("
	if( typeof( yendif ) === 'undefined' ) {
    	var yendif = {};
	};

	yendif.msg = [];
	yendif.msg['yt_search_placeholder'] = '".JText::_('YENDIF_VIDEO_SHARE_YT_SEARCH_FIELD_PLACEHOLDER_TEXT')."';
	yendif.msg['yt_search_placeholder_channel'] = '".JText::_('YENDIF_VIDEO_SHARE_YT_SEARCH_FIELD_PLACEHOLDER_TEXT_CHANNEL')."';
	yendif.msg['yt_search_placeholder_playlist'] = '".JText::_('YENDIF_VIDEO_SHARE_YT_SEARCH_FIELD_PLACEHOLDER_TEXT_PLAYIST')."';
	yendif.msg['yt_search_placeholder_user'] = '".JText::_('YENDIF_VIDEO_SHARE_YT_SEARCH_FIELD_PLACEHOLDER_TEXT_USER')."';
");
?>

<div class="yendif-video-share import">
	<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">
    	<input type="hidden" name="boxchecked" value="1" />
    	<input type="hidden" name="option" value="com_yendifvideoshare" />
    	<input type="hidden" name="view" value="import" />
    	<input type="hidden" name="task" value="saveApiKey" />
    	<input type="hidden" name="id" value="1">
        <?php echo JHTML::_( 'form.token' ); ?>
		<div class="alert alert-info">
        	<label for="yt-api-key" class="element-invisible"><?php echo JText::_('YENDIF_VIDEO_SHARE_GOOGLE_DEVELOPER_API_KEY'); ?></label>
           	<input type="text" name="google_api_key" id="yt-api-key" class="required yendif-no-margin" value="<?php echo $this->config->google_api_key; ?>" placeholder="<?php echo JText::_('YENDIF_VIDEO_SHARE_GOOGLE_DEVELOPER_API_KEY'); ?>" />
           	<button type="submit" class="btn validate"><?php echo JText::_('YENDIF_VIDEO_SHARE_STORE_THE_KEY'); ?></button> 
            <?php echo JText::sprintf( 'YENDIF_VIDEO_SHARE_GOOGLE_DEVELOPER_API_KEY_DESCRIPTION', 'https://developers.google.com/youtube/registering_an_application' ); ?>
    	</div>
    </form>
    
	<form action="index.php" method="post" name="yt-search-form" id="yt-search-form" class="form-validate">
    	<div id="filter-bar" class="btn-toolbar well">
        	 <div class="btn-group pull-left">
            	<select id="yt-type" class="input-small">
                	<option value="search">- <?php echo JText::_('YENDIF_VIDEO_SHARE_SELECT_TYPE'); ?> -</option>
                    <option value="search" selected="selected"><?php echo JText::_('YENDIF_VIDEO_SHARE_SEARCH'); ?></option>
                    <option value="playlist"><?php echo JText::_('YENDIF_VIDEO_SHARE_PLAYLIST'); ?></option>
                    <option value="user"><?php echo JText::_('YENDIF_VIDEO_SHARE_USER'); ?></option>
                    <option value="channel"><?php echo JText::_('YENDIF_VIDEO_SHARE_CHANNEL'); ?></option>
                  </select>
            </div>
        	<div class="btn-group pull-left">
            	<label for="yt-keyword" class="element-invisible"><?php echo JText::_('YENDIF_VIDEO_SHARE_YT_SEARCH_FIELD'); ?></label>
            	<input type="text" id="yt-keyword" class="required" placeholder="<?php echo JText::_('YENDIF_VIDEO_SHARE_YT_SEARCH_FIELD_PLACEHOLDER_TEXT'); ?>" />
            </div>
            <div class="btn-group pull-left">
            	<select id="yt-results" class="input-mini">
                	<option value="10">- <?php echo JText::_('YENDIF_VIDEO_SHARE_NUMBER_OF_RESULTS'); ?> -</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="10" selected="selected">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
            </div>
            <div class="btn-group pull-left">
                <button type="submit" class="btn btn-info validate" id="yt-search-btn"><?php echo JText::_('YENDIF_VIDEO_SHARE_SEARCH_YOUTUBE'); ?></button> 
            </div>
            <div class="btn-group pull-left">
                <button type="button" class="btn" id="yt-reset-btn"><?php echo JText::_('YENDIF_VIDEO_SHARE_CLEAR'); ?></button>
            </div>
             
            <div class="btn-group pull-right" id="yt-next-wrapper"></div>
            <div class="btn-group pull-right" id="yt-prev-wrapper"></div>
        </div>
    </form>
        
    <form action="index.php" method="post" name="yt-import-form" id="yt-import-form" class="form-validate">
        <p></p>

		<div id="yt-notes"><?php echo JText::_('YENDIF_VIDEO_SHARE_YT_NOTES'); ?></div>
         
        <div id="yt-videos-list"></div>
        
        <div id="yt-preloader" style="display: none;"><div id="yt-preloader-img"></div></div>
        
        <p></p>
        
        <div class="well text-center">
            <div class="btn-group">
            	<label for="import_category" class="element-invisible"><?php echo JText::_('YENDIF_VIDEO_SHARE_SELECT_CATEGORY'); ?></label>
				<?php echo $this->lists['categories']; ?>
	  		</div> 
            <div class="btn-group">
            	<label for="import_state" class="element-invisible"><?php echo JText::_('YENDIF_VIDEO_SHARE_SELECT_STATUS'); ?></label>
				<?php echo $this->lists['state']; ?>
            </div>
            <div class="btn-group">
            	<button type="submit" class="btn btn-success validate" id="yt-import-btn"><?php echo JText::_('YENDIF_VIDEO_SHARE_IMPORT_VIDEOS'); ?></button>
            </div>
        </div>
    </form>
</div>