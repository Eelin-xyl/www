<?php

/*
 * @version		$Id: upload.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// import libraries
jimport('joomla.filesystem.file');

require_once( JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'class.upload.php' );

class YendifVideoShareUpload {
	
	private $config = null;
	
	protected static $instance = null;
	
	public function __construct() {
	
		$this->config = YendifVideoShareUtils::getConfig();
		
    }
	
	public static function getInstance() {
	
		if( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
		
	}
	
	public function doUpload( $field, $folder, $return = '' ) {	
		
		$app = JFactory::getApplication();
		
		$fileTemp = '';
		
		// Check whether the file exists
		if( $files = $app->input->files->get( $field ) ) {
			$fileName = $files['name'];
			$fileTemp = $files['tmp_name'];
			$fileSize = $files['size'];
		}
		
		// Return if no file was found
		if( empty( $fileTemp ) ) {
			if( ! empty( $return ) ) {
				$fileName = $return;
			} else {
				return $return;
			}
		}
			
		// Extract file extension
		$extension = strtolower( JFile::getExt( $fileName ) );	
		
		// Check whether the file's extension type was to be ignored
		$ignored_extensions = explode(',', trim( $this->config->ignored_extensions ) );
		if( $ignored_extensions == '' ) {
			$ignored_extensions = array('php','txt','swf');
		}
		
		if( in_array( $extension, $ignored_extensions ) ) return 'invalid_file_type';
		
		// Check whether the file has valid extension
		$allowed_extensions = explode( ',', trim( $this->config->allowed_extensions ) );
		if( $allowed_extensions == '' ) {
			$allowed_extensions = array('jpeg','jpg','png','gif','flv','f4v','mp4','m4v','mov','webm','ogg','ogv');
		}
			
		if( ! in_array( $extension, $allowed_extensions ) ) return 'invalid_file_type';
		
		// Return if valid Direct URL
		if( empty( $fileTemp ) ) return $return;
		
		// Check whether the file size is not greater than the allowed
		if( $fileSize > (int) $this->config->max_upload_size ) return 'invalid_file_size';
		
		// Illegal Mime Types
		$illegal_mime_types = explode( ',', trim( $this->config->illegal_mime_types ) );
		if( $illegal_mime_types == '' ) {
			$illegal_mime_types = array('application/x-shockwave-flash','application/msword','application/excel','application/pdf','application/powerpoint','application/x-zip','text/plain','text/css','text/html','text/php','text/x-php','application/php','application/x-php','application/x-httpd-php','application/x-httpd-php-source');
		}
									
		// Legal Mime Types
		$legal_mime_types = explode( ',', trim( $this->config->legal_mime_types ) );
		if( $legal_mime_types == '' ) {
			$legal_mime_types = array('image/*','video/*','audio/*');
		}	
		
		// Check whether the file have valid Mime Type
		if( in_array( $extension, array('jpeg','jpg','png','gif') ) ) {		
			$imgInfo = null;
			if( ( $imgInfo = getimagesize( $fileTemp ) ) === FALSE ) return '';			
		} else {
			$isAllowed = false;
			
			if( function_exists('finfo_open') ) {	
				$finfo = finfo_open( FILEINFO_MIME );
				$mimeType = finfo_file( $finfo, $fileTemp );				
				finfo_close( $finfo );
			} else if( function_exists('mime_content_type') ) {			
				$mimeType = mime_content_type( $fileTemp );
			}
			
			if( isset( $mimeType ) && strlen( $mimeType ) && in_array( $mimeType, $legal_mime_types ) && !in_array( $mimeType, $illegal_mime_types ) ) {
				list( $m1, $m2 )= explode('/', $type);
				foreach( $legal_mime_types as $k => $v ) {
                   	list( $v1, $v2 ) = explode('/', $v);
                   	if( ( $v1 == '*' && $v2 == '*' ) || ( $v1 == $m1 && ( $v2 == $m2 || $v2 == '*' ) ) ) {
                       	$isAllowed = true;
                       	break;
                   	}
               	}
				if( $isAllowed == false ) return 'invalid_mime_type';
			}			
		}
		
		// Check whether the file has unsafe content
		$xssCheck = JFile::read( $fileTemp, false, 256 );
		$htmlTags = array('abbr','acronym','address','applet','area','audioscope','base','basefont','bdo','bgsound','big','blackface','blink','blockquote','body','bq','br',
		'button','caption','center','cite','code','col','colgroup','comment','custom','dd','del','dfn','dir','div','dl','dt','em','embed','fieldset','fn','font','form','frame','frameset','h1','h2','h3','h4','h5','h6','head','hr','html','iframe','ilayer','img','input','ins','isindex','keygen','kbd','label','layer','legend','li','limittext','link','listing','map','marquee','menu','meta','multicol','nobr','noembed','noframes','noscript','nosmartquotes','object','ol','optgroup','option','param','plaintext','pre','rt','ruby','s','samp','script','select','server','shadow','sidebar','small','spacer','span','strike','strong','style','sub','sup','table','tbody','td','textarea','tfoot','th','thead','title','tr','tt','ul','var','wbr','xml','xmp','!DOCTYPE', '!--');
		foreach( $htmlTags as $tag ) {
			if( stristr( $xssCheck, '<'.$tag.' ' ) || stristr( $xssCheck, '<'.$tag.'>' ) || stristr( $xssCheck, '<?php' ) ) {
				return '';
			}
		}
 
 		// Everything is OK. Upload the file
		$uploadDir = YENDIF_VIDEO_SHARE_UPLOAD_BASE . $folder . DIRECTORY_SEPARATOR;
		$saveDir = YENDIF_VIDEO_SHARE_UPLOAD_BASEURL . $folder . '/';
		
		if( $field == 'upload_category' || $field == 'upload_image' ) {
			$uploader = new upload( $files );
			$uploader->file_max_size = $this->config->max_upload_size;		
			if( $uploader->uploaded ) {
				$uploader->file_overwrite = true;
				$uploader->allowed = array('image/*');
    			$uploader->process($uploadDir);
				$fileName = $uploader->file_dst_name;
			
				$this->doProcess( $uploader, '_thumb', $this->config->gallery_thumb_width, $this->config->gallery_thumb_height, $uploadDir );
				if( $folder != 'categories' ) {
					$this->doProcess( $uploader, '_poster', $this->config->poster_image_width, $this->config->poster_image_height, $uploadDir );
				}
			} else {
				return 'error_moving_file';
			}
		} else {
			$fileName = JFile::stripExt( $fileName );
			$fileName = uniqid( JFile::makeSafe( $fileName ) ) . '.' . $extension;
			if( ! JFile::upload( $fileTemp, $uploadDir . $fileName ) ) {
        		return 'error_moving_file';
			}
		}
		
		return str_replace( DIRECTORY_SEPARATOR, '/', $saveDir.$fileName );
		
    }
	
	public function doRecreateImages( $file, $folder ) {
	
		$uploadDir = YENDIF_VIDEO_SHARE_UPLOAD_BASE . $folder . DIRECTORY_SEPARATOR;
		
		$file = $uploadDir.basename( $file );		
		$uploader = new upload( $file );
		$uploader->file_max_size = $this->config->max_upload_size;
		if( $uploader->uploaded ) {			
			$this->doProcess( $uploader, '_thumb', $this->config->gallery_thumb_width, $this->config->gallery_thumb_height, $uploadDir );
			if( $folder != 'categories' ) {
				$this->doProcess( $uploader, '_poster', $this->config->poster_image_width, $this->config->poster_image_height, $uploadDir );
			}
		}
		
	}
	
	public function doProcess( $uploader, $suffix, $width, $height, $uploadDir ) {
	
		$uploader->file_name_body_add = $suffix;
		$uploader->file_overwrite = true;
		$uploader->image_resize = true;
		$uploader->image_x = $width;
		$uploader->image_y = $height;
		
		switch( $this->config->resize_method ) {
			case 'image_ratio_crop':
				$uploader->image_ratio_crop = true;
				break;
			case 'image_ratio_fill':
				$uploader->image_ratio_fill = true;
				break;
			case 'image_ratio_no_zoom_in':
				$uploader->image_ratio_no_zoom_in = true;
				break;
			case 'image_ratio_no_zoom_out ':
				$uploader->image_ratio_no_zoom_out = true;
				break;
			case 'image_ratio_x ':
				$uploader->image_ratio_x = true;
				break;
			case 'image_ratio_y  ':
				$uploader->image_ratio_y = true;
				break;
			default :
				$uploader->image_ratio = true;
		}		
		
    	$uploader->process( $uploadDir );
		
	}
			
}