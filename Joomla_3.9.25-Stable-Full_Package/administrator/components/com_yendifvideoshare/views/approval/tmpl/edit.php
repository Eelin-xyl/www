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
JHTML::_('behavior.calendar');	

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
    	if (pressbutton == 'cancel') {
        	submitform(pressbutton);
    	} else {
			if( yendif.files > 0 ) {
               	alert('" . JText::_("YENDIF_VIDEO_SHARE_ERROR_UPLOAD_IN_PROGRESS") . "');
				return;
            };
			
			var f = document.adminForm;			
			var type = f.type.value;
			
			document.formvalidator.setHandler('list', function( value ) {
        		return (value != -1);
			});
			
			document.formvalidator.setHandler('mp4', function( value ) {
				if( type == 'video' ) {
					var url = value.split('.').pop();
					return /mp4|m4v|mov|flv/.test( url.toLowerCase() );
				};
				
				return true;
			});
			
			document.formvalidator.setHandler('webm', function( value ) {
				if( type == 'video' && value != '' ) {
					var url = value.split('.').pop();
					return /webm/.test( url.toLowerCase() );
				};
				
				return true;
			});	
			
			document.formvalidator.setHandler('ogg', function( value ) {
				if( type == 'video' && value != '' ) {
					var url = value.split('.').pop();
					return /ogg|ogv/.test( url.toLowerCase() );
				};
				
				return true;
			});		
			
			document.formvalidator.setHandler('youtube', function( value ) {
				if( type == 'youtube' ) {
					return (value != '');
				}
				
				return true;
			});
			
			document.formvalidator.setHandler('rtmp', function( value ) {
				if( type == 'rtmp' ) {
					return (value != '');
				}
				
				return true;
			});	
			
			document.formvalidator.setHandler('flash', function( value ) {
				if( type == 'rtmp' ) {
					return (value != '');
				}
				
				return true;
			});
			
			document.formvalidator.setHandler('mobile', function( value ) {
				if( type == 'rtmp' && value != '' ) {
					var url = value.split('.').pop();
					return /mp4|m4v|mov|m3u8|rtsp/.test( url.toLowerCase() );
				};
				
				return true;
			});
			
			document.formvalidator.setHandler('thirdparty', function( value ) {
				if( type == 'thirdparty' ) {
					return (value != '');
				}
				
				return true;
			});		
			
			document.formvalidator.setHandler('image', function( value ) {
				if( value != '' ) {
					var url = value.split('.').pop();
					return /jpg|jpeg|png|gif/.test( url.toLowerCase() );
				};
				
				return true;
			});	
			
			document.formvalidator.setHandler('captions', function( value ) {
				if( value != '' ) {
					var url = value.split('.').pop();
					return /vtt|srt/.test( url.toLowerCase() );
				};
				
				return true;
			});		
			
        	if( document.formvalidator.isValid(f) ) {
            	submitform(pressbutton);    
        	} else {
            	alert('" . JText::_("YENDIF_VIDEO_SHARE_ERROR_INVALID_INPUT") . "');
        	};
    	};  
	};
");
?>

<div class="yendif-video-share approval edit">
	<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">
    	<div class="form-inline form-inline-header">
        	<div class="control-group">
            	<label class="control-label" for="title"><?php echo JText::_('YENDIF_VIDEO_SHARE_TITLE'); ?><span class="star">&nbsp;*</span></label>
                <div class="controls">
                	<input type="text" name="title" id="title" class="input-xxlarge input-large-text required" value="<?php echo $this->item->title; ?>" />
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
            	<li><a href="#advertisements" data-toggle="tab"><?php echo JText::_('YENDIF_VIDEO_SHARE_ADVERTISEMENTS');?></a></li>
            	<li><a href="#seo-settings" data-toggle="tab"><?php echo JText::_('YENDIF_VIDEO_SHARE_SEO_SETTINGS');?></a></li>
        	</ul>
        
        	<div class="tab-content">
        		<div id="general-settings" class="tab-pane active">
             		<div class="row-fluid">
     					<div class="span7">
                    		<div class="control-group">
                  				<label class="control-label" for="catid"><?php echo JText::_('YENDIF_VIDEO_SHARE_SELECT_CATEGORY'); ?><span class="star">&nbsp;*</span></label>
                  				<div class="controls">
                    				<?php echo YendifVideoShareFields::ListCategories('catid', $this->catids, $this->item->catid, 'class="required validate-list"'); ?>
                  				</div>
                			</div>
                    
                    		<div class="control-group">
                  				<label class="control-label" for="type"><?php echo JText::_('YENDIF_VIDEO_SHARE_TYPE'); ?></label>
                  				<div class="controls yendif-media-types">
                    				<?php
                            			echo YendifVideoShareFields::MediaTypes(
	    	  								'type',
			  								array(
												'video'      => JText::_('YENDIF_VIDEO_SHARE_GENERAL_VIDEO'),
			        							'youtube'    => JText::_('YENDIF_VIDEO_SHARE_YOUTUBE'),
				    							'rtmp'       => JText::_('YENDIF_VIDEO_SHARE_RTMP'),
				    							'thirdparty' => JText::_('YENDIF_VIDEO_SHARE_THIRD_PARTY')
											),							
				    						$this->item->type
										);
									?> 
                  				</div>
                			</div>
                    
                    		<div class="control-group yendif-media-fields yendif-type-video">
                  				<label class="control-label" for="mp4">MP4 | M4V | FLV<span class="star">&nbsp;*</span></label>
                  				<div class="controls">
                        			<?php echo YendifVideoShareFields::FileUploader('mp4', $this->item->mp4); ?>
                    				<a id="yendif-more-formats" class="btn btn-link">
										<span id="yendif-more-text">[+] <?php echo JText::_('YENDIF_VIDEO_SHARE_MORE_OPTIONS'); ?></span>
                               			<span id="yendif-less-text" class="hide">[-] <?php echo JText::_('YENDIF_VIDEO_SHARE_LESS_OPTIONS'); ?></span>
                            		</a>
                  				</div>
                			</div>
                    
                    		<div id="yendif-more-formats-container" class="hide">
                    			<div class="control-group yendif-media-fields yendif-type-video">
                  					<label class="control-label" for="webm">WEBM <?php echo JText::_('YENDIF_VIDEO_SHARE_OPTIONAL'); ?></label>
                  					<div class="controls">
                        				<?php echo YendifVideoShareFields::FileUploader('webm', $this->item->webm); ?>
                  					</div>
                				</div>
                    
                    			<div class="control-group yendif-media-fields yendif-type-video">
                  					<label class="control-label" for="ogg">OGG <?php echo JText::_('YENDIF_VIDEO_SHARE_OPTIONAL'); ?></label>
                  					<div class="controls">
                        				<?php echo YendifVideoShareFields::FileUploader('ogg', $this->item->ogg); ?>
                  					</div>
                				</div>
                    		</div>
                    
                    		<div class="control-group yendif-media-fields yendif-type-youtube">
                  				<label class="control-label" for="youtube"><?php echo JText::_('YENDIF_VIDEO_SHARE_YOUTUBE_URL'); ?><span class="star">&nbsp;*</span></label>
                  				<div class="controls">
                        			<input type="text" name="youtube" id="youtube" class="yendif-media-required" value="<?php $this->item->youtube; ?>" />
                  				</div>
                			</div>
                    
                    		<div class="control-group yendif-media-fields yendif-type-rtmp">
                  				<label class="control-label" for="rtmp"><?php echo JText::_('YENDIF_VIDEO_SHARE_RTMP_SERVER'); ?><span class="star">&nbsp;*</span></label>
                  				<div class="controls">
                        			<input type="text" name="rtmp" id="rtmp" class="yendif-media-required" value="<?php echo $this->item->rtmp; ?>" />
                  				</div>
                			</div>
                    
                    		<div class="control-group yendif-media-fields yendif-type-rtmp">
                  				<label class="control-label" for="flash"><?php echo JText::_('YENDIF_VIDEO_SHARE_STREAM_NAME'); ?><span class="star">&nbsp;*</span></label>
                  				<div class="controls">
                        			<input type="text" name="flash" id="flash" class="yendif-media-required" value="<?php echo $this->item->flash; ?>" />
                  				</div>
                			</div>
                    
                    		<div class="control-group yendif-media-fields yendif-type-rtmp">
                  				<label class="control-label" for="mobile">
									<?php echo JText::_('YENDIF_VIDEO_SHARE_MOBILE_FALLBACK').' '.JText::_('YENDIF_VIDEO_SHARE_OPTIONAL'); ?>
                                </label>
                                <div class="controls">
                        			<?php echo YendifVideoShareFields::FileUploader('mobile', $this->item->mobile); ?>
                  				</div>
                			</div>
                    
                    		<div class="control-group yendif-media-fields yendif-type-thirdparty">
                  				<label class="control-label" for="thirdparty"><?php echo JText::_('YENDIF_VIDEO_SHARE_THIRD_PARTY'); ?><span class="star">&nbsp;*</span></label>
                  				<div class="controls">
                        			<textarea name="thirdparty" id="thirdparty" class="yendif-media-required"><?php echo $this->item->thirdparty; ?></textarea>
                  				</div>
                			</div>
                    
                    		<div class="control-group">
                  				<label class="control-label" for="image"><?php echo JText::_('YENDIF_VIDEO_SHARE_IMAGE'); ?></label>
                  				<div class="controls">
                        			<?php echo YendifVideoShareFields::FileUploader('image', $this->item->image); ?>
                  				</div>
                			</div>
                    
                    		<div class="control-group yendif-media-fields yendif-type-video yendif-type-youtube yendif-type-rtmp">
                  				<label class="control-label" for="captions">
									<?php echo JText::_('YENDIF_VIDEO_SHARE_SUBTITLE').' '.JText::_('YENDIF_VIDEO_SHARE_OPTIONAL'); ?>
                                </label>
               					<div class="controls">
                       				<?php echo YendifVideoShareFields::FileUploader('captions', $this->item->captions); ?>
               					</div>
               				</div>
                    
                   			<div class="control-group">
               					<label class="control-label" for="duration">
									<?php echo JText::_('YENDIF_VIDEO_SHARE_DURATION').' '.JText::_('YENDIF_VIDEO_SHARE_OPTIONAL'); ?>
                                </label>
               					<div class="controls">
                       				<input type="text" name="duration" id="duration" class="input-mini center" placeholder="00:00" value="<?php echo $this->item->duration; ?>" />
       								<span class="help-inline"><?php echo JText::_('YENDIF_VIDEO_SHARE_DURATION_DESCRIPTION'); ?></span>
               					</div>
               				</div>

                            <p><?php echo JText::_('YENDIF_VIDEO_SHARE_DESCRIPTION'); ?></p>
                            <?php echo JFactory::getEditor()->display('description', $this->item->description, '100%', '400', '20', '20'); ?>
                        </div>
                    
                    	<div class="span5">
                    		<div class="control-group">
                  				<label class="control-label" for="userid"><?php echo JText::_('YENDIF_VIDEO_SHARE_SELECT_USER'); ?></label>
                  				<div class="controls">
                        			<?php echo $this->userids; ?>
                  				</div>
                			</div>
                    
                    		<div class="control-group">
                  				<label class="control-label" for="access"><?php echo JText::_('YENDIF_VIDEO_SHARE_ACCESS'); ?></label>
                  				<div class="controls">
                        			<?php echo JHtml::_('access.level', 'access', $this->item->access, '', array(JHtml::_('select.option', '', '-- '.JText::_('YENDIF_VIDEO_SHARE_INHERITED').' --'))); ?>
        							<p class="muted"><?php echo JText::_('YENDIF_VIDEO_SHARE_ACCESS_VIDEO_DESCRIPTION'); ?></p>
                  				</div>
                			</div>
                    
                    		<div class="control-group">
                  				<label class="control-label" for="featured"><?php echo JText::_('YENDIF_VIDEO_SHARE_FEATURED'); ?></label>
                  				<div class="controls">
                        			<?php echo YendifVideoShareFields::ListBoolean('featured', $this->item->featured); ?>
                  				</div>
                			</div>
                    
                    		<div id="yendifPublishedUp" class="control-group">
                  				<label class="control-label" for="published_up"><?php echo JText::_('YENDIF_VIDEO_SHARE_START_PUBLISHING'); ?></label>
                  				<div class="controls">
                        			<?php echo JHTML::calendar($this->item->published_up, 'published_up', 'published_up', "%Y-%m-%d %H:%M:%S", ''); ?>
                  				</div>
                			</div>
                    
                    		<div id="yendifPublishedDown" class="control-group">
                  				<label class="control-label" for="published_down"><?php echo JText::_('YENDIF_VIDEO_SHARE_FINISH_PUBLISHING'); ?></label>
                  				<div class="controls">
                        			<?php echo JHTML::calendar($this->item->published_down, 'published_down', 'published_down', "%Y-%m-%d %H:%M:%S", ''); ?>
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
             
             	<div id="advertisements" class="tab-pane">
             		<div class="row-fluid">
                		<div class="control-group">
                  			<label class="control-label" for="preroll"><?php echo JText::_('YENDIF_VIDEO_SHARE_PREROLL'); ?></label>
                  			<div class="controls">
                        		<?php echo YendifVideoShareFields::ListPreroll('preroll', $this->item->preroll); ?>
                  			</div>
                		</div>
                    
                    	<div class="control-group">
                  			<label class="control-label" for="postroll"><?php echo JText::_('YENDIF_VIDEO_SHARE_POSTROLL'); ?></label>
                  			<div class="controls">
                        		<?php echo YendifVideoShareFields::ListPostroll('postroll', $this->item->postroll); ?>
                  			</div>
                		</div>
                	</div>
             	</div>
             
             	<div id="seo-settings" class="tab-pane">
             		<div class="row-fluid">
                		<div class="control-group">
                  			<label class="control-label" for="meta_keywords"><?php echo JText::_('YENDIF_VIDEO_SHARE_META_KEYWORDS'); ?></label>
                  			<div class="controls">
                        		<textarea name="meta_keywords" id="meta_keywords" placeholder="<?php echo JText::_('YENDIF_VIDEO_SHARE_META_KEYWORDS_DESCRIPTION'); ?>"><?php echo $this->item->meta_keywords; ?></textarea>
                  			</div>
                		</div>
                    
                    	<div class="control-group">
                  			<label class="control-label" for="meta_description"><?php echo JText::_('YENDIF_VIDEO_SHARE_META_DESCRIPTION'); ?></label>
                  			<div class="controls">
                        		<textarea name="meta_description" id="meta_description"><?php echo $this->item->meta_description; ?></textarea>
                  			</div>
                		</div>
                	</div>
             	</div>
        	</div>
        </div>

    	<input type="hidden" name="boxchecked" value="1" />
    	<input type="hidden" name="option" value="com_yendifvideoshare" />
    	<input type="hidden" name="view" id="yendif-insert-view" value="approval" />
    	<input type="hidden" name="task" value="" /> 
        <input type="hidden" name="id" id="yendif-video-id" value="<?php echo $this->item->id; ?>">   
    	<?php echo JHTML::_( 'form.token' ); ?>
  	</form>
    
  	<div class="form-actions text-center muted"><?php echo JText::_('YENDIF_VIDEO_SHARE_COPYRIGHTS'); ?></div>
    
    <div id="yendif-media-uploader" class="hide"></div>
</div>