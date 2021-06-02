<?php

/*
 * @version		$Id: edit.php 1.2.8 07-03-2019 $
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
	
	Joomla.submitbutton = function( pressbutton ) {
    	if( pressbutton == 'cancel' ) {
        	submitform( pressbutton );
    	} else {
			if( yendif.files > 0 ) {
               	alert('" . JText::_("YENDIF_VIDEO_SHARE_ERROR_UPLOAD_IN_PROGRESS") . "');
				return;
            };
			
			var f = document.adminForm;
			 
			document.formvalidator.setHandler('image', function( value ) {
				var url = value.split('.').pop();
				return /jpg|jpeg|png|gif/.test( url.toLowerCase() );
			});	
			
			if( document.formvalidator.isValid(f) ) {
            	submitform( pressbutton );    
        	} else {
            	alert('" . JText::_("YENDIF_VIDEO_SHARE_ERROR_INVALID_INPUT") . "');
        	};
    	};
	};
");

?>

<div class="yendif-video-share categories edit"> 
	<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">
    	<div class="form-inline form-inline-header">
        	<div class="control-group">
            	<label class="control-label" for="name"><?php echo JText::_('YENDIF_VIDEO_SHARE_NAME'); ?><span class="star">&nbsp;*</span></label>
                <div class="controls">
                	<input type="text" name="name" id="name" class="input-xxlarge input-large-text required" value="<?php echo $this->item->name; ?>" />
            	</div>
        	</div>
            
            <div class="control-group">
            	<label class="control-label" for="alias"><?php echo JText::_('YENDIF_VIDEO_SHARE_ALIAS'); ?></label>
               	<div class="controls">
                   	<input type="text" name="alias" id="alias" placeholder="<?php echo JText::_('JFIELD_ALIAS_PLACEHOLDER'); ?>" value="<?php echo $this->item->alias; ?>" />
               	</div>
        	</div>
        </div>
        
        <div class="yendif-spacer"></div>
        
        <div class="form-horizontal">
    		<ul class="nav nav-tabs">
        		<li class="active"><a href="#general-settings" data-toggle="tab"><?php echo JText::_('YENDIF_VIDEO_SHARE_GENERAL_SETTINGS');?></a></li>
            	<li><a href="#seo-settings" data-toggle="tab"><?php echo JText::_('YENDIF_VIDEO_SHARE_SEO_SETTINGS');?></a></li>
        	</ul>
            
            <div class="tab-content">
        		<div id="general-settings" class="tab-pane active">
                	<div class="row-fluid">        
                        <div class="span7">                
                            <div class="control-group">
                                <label class="control-label" for="parent"><?php echo JText::_('YENDIF_VIDEO_SHARE_PARENT'); ?></label>
                                <div class="controls">
                                    <?php echo $this->categories; ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="image"><?php echo JText::_('YENDIF_VIDEO_SHARE_IMAGE'); ?></label>
                                <div class="controls">
                                    <?php echo YendifVideoShareFields::FileUploader('image', $this->item->image); ?>
                                </div>
                            </div>
                            
                            <p><?php echo JText::_('YENDIF_VIDEO_SHARE_DESCRIPTION'); ?></p>
                            <?php echo JFactory::getEditor()->display('description', $this->item->description, '100%', '400', '20', '20'); ?>
                        </div>
                
                        <div class="span5">
                        	<div class="control-group">
                                <label class="control-label" for="access"><?php echo JText::_('YENDIF_VIDEO_SHARE_ACCESS'); ?></label>
                                <div class="controls">
                                    <?php echo JHtml::_('access.level', 'access', $this->item->access, '', array(JHtml::_('select.option', '', '-- '.JText::_('YENDIF_VIDEO_SHARE_INHERITED').' --'))); ?>
                                    <p class="help-block"><?php echo JText::_('YENDIF_VIDEO_SHARE_ACCESS_CATEGORY_DESCRIPTION'); ?></p>
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label class="control-label" for="published"><?php echo JText::_('YENDIF_VIDEO_SHARE_PUBLISH'); ?></label>
                                <div class="controls">
                                    <?php echo YendifVideoShareFields::ListBoolean('published', $this->item->published); ?>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
                
                <div id="seo-settings" class="tab-pane">
                	<div class="row-fluid">
                    	<div class="control-group">
                            <label class="control-label" for="meta_keywords"><?php echo JText::_('YENDIF_VIDEO_SHARE_META_KEYWORDS'); ?></label>
                            <div class="controls">
                                <textarea name="meta_keywords" id="meta_keywords" placeholder="<?php echo JText::_('YENDIF_VIDEO_SHARE_META_KEYWORDS_DESCRIPTION'); ?>" ><?php echo $this->item->meta_keywords; ?></textarea>
                            </div>
                        </div>
                        
                        <div class="control-group">
                            <label class="control-label" for="meta_description"><?php echo JText::_('YENDIF_VIDEO_SHARE_META_DESCRIPTION'); ?></label>
                            <div class="controls">
                                <textarea name="meta_description" id="meta_description" placeholder="<?php echo JText::_('YENDIF_VIDEO_SHARE_META_KEYWORDS_DESCRIPTION'); ?>" ><?php echo $this->item->meta_description; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
           	</div>
        </div>
    	<input type="hidden" name="boxchecked" value="1">
    	<input type="hidden" name="option" value="com_yendifvideoshare" />
    	<input type="hidden" name="view" id="yendif-insert-view" value="categories" />
    	<input type="hidden" name="task" value="" />
        <input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
        <input type="hidden" id="yendif-insert-id" value="0" />
    	<?php echo JHTML::_( 'form.token' ); ?>
  </form>

  <div class="form-actions text-center muted"><?php echo JText::_('YENDIF_VIDEO_SHARE_COPYRIGHTS'); ?></div>
  
  <div id="yendif-media-uploader" class="hide"></div>
</div>