<?php

/*
 * @version		$Id: add.php 1.2.8 07-03-2019 $
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
    	if( pressbutton == 'cancel' ) {
        	submitform( pressbutton );
    	} else {
			if( yendif.files > 0 ) {
               	alert('" . JText::_("YENDIF_VIDEO_SHARE_ERROR_UPLOAD_IN_PROGRESS") . "');
				return;
            };
			
			var f = document.adminForm;			
			
			document.formvalidator.setHandler('list', function( value ) {
        		return (value != -1);
			});
			
			document.formvalidator.setHandler('mp4', function( value ) {
				var url = value.split('.').pop();
				return /mp4|m4v|mov|flv/.test( url.toLowerCase() );
			});
			
        	if( document.formvalidator.isValid(f) ) {
            	submitform( pressbutton );    
        	} else {
            	alert('" . JText::_("YENDIF_VIDEO_SHARE_ERROR_INVALID_INPUT") . "');
        	};
    	};  
	};
");

$id = YendifVideoShareUtils::getAdvertInsertId();
?>

<div class="yendif-video-share adverts add">
	<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-horizontal form-validate">
    	<div class="control-group">
        	<label class="control-label" for="title"><?php echo JText::_('YENDIF_VIDEO_SHARE_TITLE'); ?><span class="star">&nbsp;*</span></label>
        	<div class="controls">
          		<input type="text" id="title" name="title" class="required" />
        	</div>
      	</div> 
        
        <div class="control-group">
        	<label class="control-label" for="type"><?php echo JText::_('YENDIF_VIDEO_SHARE_TYPE'); ?></label>
        	<div class="controls">
          		<?php
                	echo YendifVideoShareFields::ListItems(
						'type',
						array(
							'preroll'  => JText::_('YENDIF_VIDEO_SHARE_PREROLL'),
					  		'postroll' => JText::_('YENDIF_VIDEO_SHARE_POSTROLL'),
					  		'both'     => JText::_('YENDIF_VIDEO_SHARE_BOTH')
						),							
						'both'
					);
				?>
        	</div>
      	</div> 
        
        <div class="control-group">
        	<label class="control-label" for="mp4">MP4 | M4V | FLV<span class="star">&nbsp;*</span></label>
            <div class="controls">
            	<?php echo YendifVideoShareFields::FileUploader('mp4'); ?>
            </div>
       	</div> 
         
        <div class="control-group">
        	<label class="control-label" for="link"><?php echo JText::_('YENDIF_VIDEO_SHARE_TARGET'); ?></label>
            <div class="controls">
            	<input type="text" name="link" id="link" />
            </div>
       	</div>
        
        <div class="control-group">
        	<label class="control-label" for="impressions"><?php echo JText::_('YENDIF_VIDEO_SHARE_IMPRESSIONS'); ?></label>
            <div class="controls">
            	<input type="text" name="impressions" id="impressions" />
            </div>
       	</div>
        
        <div class="control-group">
        	<label class="control-label" for="clicks"><?php echo JText::_('YENDIF_VIDEO_SHARE_CLICKS'); ?></label>
            <div class="controls">
            	<input type="text" name="clicks" id="clicks" />
            </div>
       	</div>
        
        <div class="control-group">
        	<label class="control-label" for="published"><?php echo JText::_('YENDIF_VIDEO_SHARE_PUBLISH'); ?></label>
            <div class="controls">
            	<?php echo YendifVideoShareFields::ListBoolean('published'); ?>
            </div>
        </div>   
        
        <input type="hidden" name="boxchecked" value="1" />
    	<input type="hidden" name="option" value="com_yendifvideoshare" />
    	<input type="hidden" name="view" id="yendif-insert-view" value="adverts" />
    	<input type="hidden" name="task" value="" />  
        <input type="hidden" name="id" id="yendif-insert-id" value="<?php echo $id; ?>" />    
    	<?php echo JHTML::_( 'form.token' ); ?>       
    </form>
    
    <div class="form-actions text-center muted"><?php echo JText::_('YENDIF_VIDEO_SHARE_COPYRIGHTS'); ?></div>
    
    <div id="yendif-media-uploader" class="hide"></div>
</div>