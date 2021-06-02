<?php 

/*
 * @version		$Id: add.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.formvalidation');

$app = JFactory::getApplication();	
$document = JFactory::getDocument();

$document->addScriptDeclaration("
	if( typeof( yendif ) === 'undefined' ) {
    	var yendif = {};
	};

	yendif.base = '".JURI::root()."';
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
	
	
	function valYendifAddForm() {
		f = document.yendif_form;
		var type = f.type.value;
		
			if( 'rtmp' ==  type ) {
	
				var rtmp = jQuery( '#rtmp' ).val();
				var dash = jQuery( '#dash' ).val();
				var hls = jQuery( '#hls' ).val();
				var flash = jQuery( '#flash' ).val();

				if( hls ) {
					jQuery( '#rtmp' ).removeClass( 'required' );
					jQuery( '#flash' ).removeClass( 'required' );
					jQuery( '#dash' ).removeClass( 'required' );
				}else {
					jQuery( '#hls' ).addClass( 'required' );
				} 
							
				
				if( dash !='' || hls != '' || rtmp != ''){
					jQuery( '#hls' ).removeClass( 'required' );
					jQuery( '#flash' ).removeClass( 'required' );
				
				}
				
				
				if( rtmp == '' && dash == '' && hls == '' ) {
					jQuery( '#hls' ).addClass( 'required' );
					jQuery( '#flash' ).removeClass( 'required' );
				
				}
				
				if( rtmp != '' && flash == '' ) {
					jQuery( '#flash' ).addClass( 'required' );
				}
			
		}
			
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
		
		document.formvalidator.setHandler('hd', function( value ) {
			if( type == 'video' && value != '' ) {
				var url = value.split('.').pop();
				return /mp4|m4v|mov|/.test( url.toLowerCase() );
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
			
			document.formvalidator.setHandler( 'hls', function( value ) {

			  if ( 'rtmp' == type ) {
					var url = value.split('.').pop();
					jQuery( '#rtmp' ).removeClass( 'required' );
					jQuery( '#flash' ).removeClass( 'required' );
					jQuery( '#dash' ).removeClass( 'required' );
					return /m3u8/.test( url.toLowerCase() );
				};
				return true;
			});

			document.formvalidator.setHandler( 'dash', function( value ) {
			
				 if ( 'rtmp' == type ) {
					var url = value.split('.').pop();
					jQuery( '#rtmp' ).removeClass( 'required' );
					jQuery( '#flash' ).removeClass( 'required' );
					jQuery( '#hls' ).removeClass( 'required' );
					return /mpd/.test( url.toLowerCase() );
				};
				
				return true;
			});
			
			document.formvalidator.setHandler('rtmp', function( value ) {

				if( 'rtmp' == type ) {
						jQuery( '#dash' ).removeClass( 'required' );
						jQuery( '#hls' ).removeClass( 'required' );
						return (value != '');
				}
				
				return true;
			});	
			
			document.formvalidator.setHandler('flash', function( value ) {
				 
				if( 'rtmp' == type ) {
					jQuery( '#dash' ).removeClass( 'required' );
					jQuery( '#hls' ).removeClass( 'required' );
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
		
		jQuery( '#yendif_form' ).on( 'submit', function() {
			if( yendif.files > 0 ) {
				alert('" . JText::_("YENDIF_VIDEO_SHARE_ERROR_UPLOAD_IN_PROGRESS") . "');
				return;
			};
		});


	}
	
	
	jQuery(document).ready(function() {
		var f = document.yendif_form;			
		var type = f.type.value;
	});
");

$itemId = $app->input->getInt('Itemid')  ? '&Itemid=' . $app->input->getInt('Itemid') : '';
$id = YendifVideoShareUtils::getVideoInsertId();
?>

<div class="yendif-video-share videos add <?php echo $this->escape( $this->params->get('pageclass_sfx') ); ?>">
	<div class="page-header">
  		<h1><?php echo JText::_('YENDIF_VIDEO_SHARE_ADD_NEW_VIDEO'); ?></h1>
    </div>
    
    <form action="index.php" method="post" name="yendif_form" id="yendif_form" class="form-validate">
    	<div class="row-fluid form-horizontal">
			<div class="control-group">
            	<label class="control-label" for="title"><?php echo JText::_('YENDIF_VIDEO_SHARE_TITLE'); ?><span class="star">&nbsp;*</span></label>
                <div class="controls">
                	<input type="text" name="title" id="title" class="required" />
            	</div>
        	</div>
            
            <div class="control-group">
            	<label class="control-label" for="catid"><?php echo JText::_('YENDIF_VIDEO_SHARE_SELECT_CATEGORY'); ?><span class="star">&nbsp;*</span></label>
                <div class="controls">
                	<?php echo YendifVideoShareFields::ListCategories('catid', $this->catids, -1, 'class="required validate-list"'); ?>
            	</div>
        	</div>
            
            <div class="control-group">
            	<label class="control-label" for="type"><?php echo JText::_('YENDIF_VIDEO_SHARE_TYPE'); ?></label>
                <div class="controls yendif-media-types">
                	<?php
						$types = array( 'video' => JText::_('YENDIF_VIDEO_SHARE_GENERAL_VIDEO') );
						if( $this->config->allow_youtube_upload ) $types['youtube'] = JText::_('YENDIF_VIDEO_SHARE_YOUTUBE');
						if( $this->config->allow_rtmp_upload ) $types['rtmp'] = JText::_('YENDIF_VIDEO_SHARE_RTMP');
						
                    	echo YendifVideoShareFields::MediaTypes( 'type', $types, 'video' );
					?>
            	</div>
        	</div>
            
            <div class="control-group yendif-media-fields yendif-type-video">
            	<label class="control-label" for="mp4">MP4 | M4V <span class="star">&nbsp;*</span></label>
                <div class="controls">
                	<?php echo YendifVideoShareFields::FileUploader('mp4', '', $this->config->allow_upload); ?>
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
                		<?php echo YendifVideoShareFields::FileUploader('webm', '', $this->config->allow_upload); ?>
                	</div>
                </div>
                    
                <div class="control-group yendif-media-fields yendif-type-video">
                	<label class="control-label" for="ogg">OGG <?php echo JText::_('YENDIF_VIDEO_SHARE_OPTIONAL'); ?></label>
                	<div class="controls">
                		<?php echo YendifVideoShareFields::FileUploader('ogg', '', $this->config->allow_upload); ?>
                	</div>
                </div>
        	</div>
            
            <div class="control-group yendif-media-fields yendif-type-youtube">
            	<label class="control-label" for="youtube"><?php echo JText::_('YENDIF_VIDEO_SHARE_YOUTUBE_URL'); ?><span class="star">&nbsp;*</span></label>
                <div class="controls">
                	<input type="text" name="youtube" id="youtube" class="yendif-media-required" />
                </div>
        	</div>
			 <!-- 1.2.8 Add Hls & MPEG-Dash -->
            <div class="control-group yendif-media-fields yendif-type-rtmp">
                <label class="control-label" for="hls"><?php echo JText::_('YENDIF_VIDEO_SHARE_HLS'); ?><span class="star">&nbsp;*</span></label>
                <div class="controls">
                    <input type="text" name="hls" id="hls" class="yendif-media-required validate-hls" />
                </div>
            </div>
             <div class="control-group yendif-media-fields yendif-type-rtmp">
                <label class="control-label" for="dash"><?php echo JText::_('YENDIF_VIDEO_SHARE_MPEG_DASH'); ?></label>
                <div class="controls">
                    <input type="text" name="dash" id="dash" class="validate-dash" />
                </div>
            </div>
           
            <div class="control-group yendif-media-fields yendif-type-rtmp">
            	<label class="control-label" for="rtmp"><?php echo JText::_('YENDIF_VIDEO_SHARE_RTMP_SERVER'); ?></label>
                <div class="controls">
                	<input type="text" name="rtmp" id="rtmp" class="validate-rtmp"  />
                </div>
        	</div>
            
            <div class="control-group yendif-media-fields yendif-type-rtmp">
             	<label class="control-label" for="flash"><?php echo JText::_('YENDIF_VIDEO_SHARE_STREAM_NAME'); ?></label>
                <div class="controls">
                	<input type="text" name="flash" id="flash" class="validate-flash" />
                </div>
            </div>
            
            
            
            
            <div class="control-group yendif-media-fields yendif-type-rtmp">
            	<label class="control-label" for="mobile">
					<?php echo JText::_('YENDIF_VIDEO_SHARE_MOBILE_FALLBACK').' '.JText::_('YENDIF_VIDEO_SHARE_OPTIONAL'); ?>
                </label>
                <div class="controls">
                	<?php echo YendifVideoShareFields::FileUploader('mobile', '', $this->config->allow_upload); ?>
                </div>
        	</div>
            
            <div class="control-group">
            	<label class="control-label" for="image"><?php echo JText::_('YENDIF_VIDEO_SHARE_IMAGE'); ?></label>
                <div class="controls">
                	<?php echo YendifVideoShareFields::FileUploader('image', '', $this->config->allow_upload); ?>
                </div>
        	</div>
            
            <?php if( $this->config->allow_subtitle ) :  ?>
            	<div class="control-group yendif-media-fields yendif-type-video yendif-type-youtube yendif-type-rtmp">
            		<label class="control-label" for="captions">
						<?php echo JText::_('YENDIF_VIDEO_SHARE_SUBTITLE').' '.JText::_('YENDIF_VIDEO_SHARE_OPTIONAL'); ?>
                	</label>
               		<div class="controls">
                		<?php echo YendifVideoShareFields::FileUploader('captions', '', $this->config->allow_upload); ?>
               		</div>
        		</div>
            <?php endif; ?>
            
            <div class="control-group">
            	<label class="control-label" for="duration">
					<?php echo JText::_('YENDIF_VIDEO_SHARE_DURATION').' '.JText::_('YENDIF_VIDEO_SHARE_OPTIONAL'); ?>
                </label>
               	<div class="controls">
                	<input type="text" name="duration" id="duration" class="input-mini center" placeholder="00:00"/>
       				<span class="help-inline"><?php echo JText::_('YENDIF_VIDEO_SHARE_DURATION_DESCRIPTION'); ?></span>
               	</div>
        	</div>

            <div class="control-group">
            	<label class="control-label" for="description"><?php echo JText::_('YENDIF_VIDEO_SHARE_DESCRIPTION'); ?></label>
               	<div class="controls">
           			<?php echo JFactory::getEditor()->display('description', '', '100%', '400', '20', '20'); ?>
                </div>
            </div>
            
            <?php if( $this->config->schedule_video_publishing ) : ?>
            	<div class="control-group">
            		<label class="control-label" for="published_up"><?php echo JText::_('YENDIF_VIDEO_SHARE_START_PUBLISHING'); ?></label>
                	<div class="controls">
                		<?php echo JHTML::calendar('', 'published_up', 'published_up', "%Y-%m-%d %H:%M:%S", ''); ?>
                    	<p class="help-block"><?php echo JText::_('YENDIF_VIDEO_SHARE_START_PUBLISHING_DESC'); ?></p>
                	</div>
            	</div>
            
                    
            	<div class="control-group">
            		<label class="control-label" for="published_down"><?php echo JText::_('YENDIF_VIDEO_SHARE_FINISH_PUBLISHING'); ?></label>
            		<div class="controls">
            			<?php echo JHTML::calendar('', 'published_down', 'published_down', "%Y-%m-%d %H:%M:%S", ''); ?>
                    	<p class="help-block"><?php echo JText::_('YENDIF_VIDEO_SHARE_FINISH_PUBLISHING_DESC'); ?></p>
            		</div>
            	</div>
            <?php endif; ?>
            
            <div class="control-group">
            	<label class="control-label" for="meta_keywords"><?php echo JText::_('YENDIF_VIDEO_SHARE_META_KEYWORDS'); ?></label>
                <div class="controls">
                	<textarea name="meta_keywords" id="meta_keywords" placeholder="<?php echo JText::_('YENDIF_VIDEO_SHARE_META_KEYWORDS_DESCRIPTION'); ?>"></textarea>
                </div>
            </div>
                    
            <div class="control-group">
            	<label class="control-label" for="meta_description"><?php echo JText::_('YENDIF_VIDEO_SHARE_META_DESCRIPTION'); ?></label>
            	<div class="controls">
               		<textarea name="meta_description" id="meta_description"></textarea>
            	</div>
            </div>
        </div>
        
        <div class="form-actions text-center muted">
      		<button type="submit" class="btn btn-primary validate" onclick="return valYendifAddForm();"><?php echo JText::_('YENDIF_VIDEO_SHARE_SAVE'); ?></button>
      		<a class="btn" href="<?php echo JRoute::_( 'index.php?option=com_yendifvideoshare&view=user'.$itemId ); ?>"><?php echo JText::_('YENDIF_VIDEO_SHARE_CANCEL'); ?></a>
    	</div>
    
    	<input type="hidden" name="option" value="com_yendifvideoshare" />
    	<input type="hidden" name="view" value="user" />
    	<input type="hidden" name="task" value="save" /> 
        <input type="hidden" name="id" id="yendif-insert-id" value="<?php echo $id; ?>"> 
        <input type="hidden" name="Itemid" value="<?php echo $app->input->getInt('Itemid'); ?>" />
        <input type="hidden" name="status" value="new" />
        <?php echo JHTML::_( 'form.token' ); ?>   
    </form>
</div>

<div id="yendif-media-uploader" class="hide"></div>