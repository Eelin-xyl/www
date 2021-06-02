<?php

/*
 * @version		$Id: default.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.formvalidation');
JHtml::_('jquery.framework');

$document = JFactory::getDocument();
$document->addScript( YendifVideoShareUtils::prepareURL('media/yendifvideoshare/assets/admin/js/yendifvideoshare.js') );
$document->addScriptDeclaration("
	if( typeof( yendif ) === 'undefined' ) {
    	var yendif = {};
	};

	yendif.msg = [];
	yendif.msg['cancel'] = '".JText::_('YENDIF_VIDEO_SHARE_UPLOAD_CANCEL')."';
	yendif.msg['success'] = '".JText::_('YENDIF_VIDEO_SHARE_UPLOAD_SUCCCESS')."';	
	yendif.msg['invalid_file_type'] = '".JText::_('YENDIF_VIDEO_SHARE_UPLOAD_ERROR_INVALID_FILE_TYPE')."';
	yendif.msg['invalid_file_size'] = '".JText::_('YENDIF_VIDEO_SHARE_UPLOAD_ERROR_INVALID_FILE_SIZE')."';
	yendif.msg['invalid_mime_type'] = '".JText::_('YENDIF_VIDEO_SHARE_UPLOAD_ERROR_INVALID_MIME_TYPE')."';
	yendif.msg['error_moving_file'] = '".JText::_('YENDIF_VIDEO_SHARE_UPLOAD_ERROR_MOVING_FILE')."';
	yendif.msg['unknown_error'] = '".JText::_('YENDIF_VIDEO_SHARE_UPLOAD_ERROR_UNKNOWN_ERROR')."';	
	yendif.msg['retry'] = '".JText::_('YENDIF_VIDEO_SHARE_UPLOAD_RETRY')."';	
	yendif.msg['reset'] = '".JText::_('YENDIF_VIDEO_SHARE_UPLOAD_RESET')."';
	
	Joomla.submitbutton = function(pressbutton) {
		if( yendif.files > 0) {
        	alert('" . JText::_("YENDIF_VIDEO_SHARE_ERROR_UPLOAD_IN_PROGRESS") . "');
			return;
        };
			
		var f = document.adminForm;
			
		document.formvalidator.setHandler('logo', function( value ) {
			var url = value.split('.').pop();
			return /jpg|jpeg|png|gif/.test( url.toLowerCase() );
		});	
			 
		if( document.formvalidator.isValid(f) ) {
           	submitform( pressbutton );    
        } else {
           	alert('" . JText::_("YENDIF_VIDEO_SHARE_ERROR_INVALID_INPUT") . "');
        };
	};
");

?>

<div class="yendif-video-share config">
  <form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">
  	<ul class="nav nav-tabs">
    	<li class="active"><a href="#player-settings" data-toggle="tab"><?php echo JText::_('YENDIF_VIDEO_SHARE_PLAYER_SETTINGS');?></a></li>
        <li><a href="#layout-settings" data-toggle="tab"><?php echo JText::_('YENDIF_VIDEO_SHARE_FRONTEND_LAYOUT_SETTINGS');?></a></li>
        <li><a href="#performance-settings" data-toggle="tab"><?php echo JText::_('YENDIF_VIDEO_SHARE_PERFORMANCE_SETTINGS');?></a></li>
        <li><a href="#security-settings" data-toggle="tab"><?php echo JText::_('YENDIF_VIDEO_SHARE_SECURITY_SETTINGS');?></a></li>
        <li><a href="#user-settings" data-toggle="tab"><?php echo JText::_('YENDIF_VIDEO_SHARE_USER_SETTINGS');?></a></li>        
        <li><a href="#responsive-settings" data-toggle="tab"><?php echo JText::_('YENDIF_VIDEO_SHARE_RESPONSIVE_CSS');?></a></li>
        <li><a href="#sef-settings" data-toggle="tab"><?php echo JText::_('YENDIF_VIDEO_SHARE_SEF_SETTINGS');?></a></li>
        <li><a href="#licensing-settings" data-toggle="tab"><?php echo JText::_('YENDIF_VIDEO_SHARE_LICENSING');?></a></li>
    </ul>
    
    <div class="tab-content">
    	<div id="player-settings" class="tab-pane active"><?php echo $this->loadTemplate('player'); ?></div>
        <div id="layout-settings" class="tab-pane"><?php echo $this->loadTemplate('layout'); ?></div>
        <div id="performance-settings" class="tab-pane"><?php echo $this->loadTemplate('performance'); ?></div>
        <div id="security-settings" class="tab-pane"><?php echo $this->loadTemplate('security'); ?></div>
        <div id="user-settings" class="tab-pane"><?php echo $this->loadTemplate('user'); ?></div>
        <div id="responsive-settings" class="tab-pane"><?php echo $this->loadTemplate('responsive'); ?></div>
        <div id="sef-settings" class="tab-pane"><?php echo $this->loadTemplate('sef'); ?></div>
        <div id="licensing-settings" class="tab-pane"><?php echo $this->loadTemplate('licensing'); ?></div>
    </div>
    <input type="hidden" name="boxchecked" value="1" />
    <input type="hidden" name="option" value="com_yendifvideoshare" />
    <input type="hidden" name="view" id="yendif-insert-view" value="config" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="id" id="yendif-insert-id" value="1">
    <?php echo JHTML::_( 'form.token' ); ?>
  </form>

  <div class="form-actions text-center muted"><?php echo JText::_('YENDIF_VIDEO_SHARE_COPYRIGHTS'); ?></div>
  
  <div id="yendif-media-uploader" class="hide"></div>
</div>