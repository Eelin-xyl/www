<?php
/*
 * @version		$Id: view.html.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies PVT Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.filesystem.file' );

class YendifVideoShareViewPlayer extends YendifVideoShareView {
	
	private	$properties = array(
		'autoplay',
			'analytics',
			'autoplaylist',
			'can_skip_adverts',
			'controlbar',
			'currenttime',
			'download',
			'duration',
			'embed',
			'fullscreen',
			'license',
			'logo',
			'loop',
			'playbtn',
			'playlist_height',
			'playlist_position',
			'playlist_width',
			'playpause',
			'progress',		
			'ratio',
			'share',
			'show_adverts_timeframe',
			'show_skip_adverts_on',
			'enable_adverts',
			'theme',
			'volume',
			'volumebtn',
			'hd',
			'show_consent',
			'default_image'	,
			'ad_engine',
			'vasturl'
		);

    public function display( $tpl = null ) { 
	
        $this->app = JFactory::getApplication();        

        $this->config = YendifVideoShareUtils::getConfig();
		
		$model = $this->getModel();
		
		$vid = $this->app->input->getInt( 'vid' );

        $this->video = $model->getVideo( $vid );
		
		
		$this->shareUrl = YendifVideoShareUtils::buildRoute( $this->video, 'video' );
		        
        parent::display( $tpl );		
    }  
	
	public function getTitle() {	
	
		$document = JFactory::getDocument();
		
        return $document->getTitle() . ' - ' . $this->video->title;	
			
    } 
	
	public function getURL() {	
	
		$itemId = $this->app->input->getInt( 'Itemid' ) ? '&Itemid=' . $this->app->input->getInt( 'Itemid' ) : '';
		
        return JRoute::_( 'index.php?option=com_yendifvideoshare&view=video&id=' . $this->video->id . $itemId );
			
    }
	
	public function is_mobile(  ) {
		
		// detect mobile device
		$is_mobile = false;
		if( preg_match( '/iPhone|iPod|iPad|BlackBerry|Android/', $_SERVER['HTTP_USER_AGENT'] ) ) {
			$is_mobile = true;
		}
		
		return $is_mobile;
	
	}

	public function hasAds() {
	
        $this->config->ad_engine = ! empty( $this->config->ad_engine ) ? $this->config->ad_engine : 'custom';

        if ( 'custom' == $this->config->ad_engine  &&  'none' != $this->config->enable_adverts ) {
		
            $this->config->vasturl = JURI::root() . 'index.php?option=com_yendifvideoshare&view=ads&task=vmap&id=' . $this->video->id . '&format=xml';
            return true;
			
        } else if ( 'vast' == $this->config->ad_engine ) {
		
            if ( ! empty( $this->config->vasturl ) ) {
                return true;
            }
			
        }

        return false;
    }
	
	public function getIpAddress() {
	
        // Whether ip is from share internet
        if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        }
        
        // Whether ip is from proxy
        elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        
        // Whether ip is from remote address
        else {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }
        
        return $ip_address;        
    }
	
	public function getTracks() {
		$tracks = array();
		
		if( isset( $this->video ) && ! empty( $this->video->captions  ) ) {
			$tracks = $this->video->captions;
		}
		
		return $tracks;
	}

	
	public function getSources() {
	
		//video Types
		$types = array(
			'video'   => array( 'mp4', 'mp4_hd', 'webm', 'ogv' ),
			'youtube' => array( 'youtube' ),		
			'rtmp'    => array( 'hls', 'dash', 'rtmp', 'flash', 'mp4' )
		);
		
		// Video Sources
		$sources = array();

		if( isset( $this->video ) && ! empty( $this->video  ) ) {
			if ( 'rtmp' == $this->video->type && ! empty( $this->video->mp4 ) ) {
	
				if ( $this->is_mobile() ) {				
					$this->video->type = 'video';
				} else {
					$this->video->mp4 = '';
				}
			}
		
			$types = $types[ $this->video->type ];
			$count = count( $types );
			
			
			for( $i = 0; $i < $count; $i++ ) {
				$type = $types[$i];
				if( isset( $this->video->$type ) && ! empty( $this->video->$type ) ) {
					$src = $this->video->$type;
					
					//Check RTMP video
					if(  $type == 'rtmp' && ! empty ( $this->video->flash ) ){
						$src = $src.'/'.$this->video->flash;
					}
					
					if( $type != 'flash' ) {
					
						$sources[] = $this->buildSourceArray( $type , $src );
					}
				}
			}
			
		} else {
			

			if ( ! empty( $_GET['rtmp'] ) && ! empty( $_GET['mp4'] ) ) {
					
					if ( $this->is_mobile() ) {				
						$types = array( 'mp4', 'mp4_hd', 'webm', 'ogv' );
					} else {
						$types = array( 'hls', 'dash', 'rtmp', 'flash');
					}
					
					$formats = $types;
					
			} else {
				
				// GetUrlAttributes
				$formats = call_user_func_array( 'array_merge', $types );
			}
			
			foreach( array_unique( $formats ) as $format ) {
				if( isset( $_GET[$format] ) && ! empty( $_GET[$format] ) ) {
					
					$type = $format;
					$src = $_GET[$format];
					
					//Check RTMP video
					if(  $format == 'rtmp' && ! empty ( $_GET['flash'] ) ){
						$src = $_GET[$format].'/'.$_GET['flash'];
					}
					
					$sources[] = $this->buildSourceArray( $type , $src );
				}
			}
		}
		
		return $sources;
		
	}
	
	public function buildSourceArray( $type, $src ) {
	
		$source = array();
		
		
		
	
		switch( $type ) {
		
			case 'mp4' :
				$filetype = strtolower( JFile::getExt($src) );
				$mimetype = ( $filetype == 'm3u8' ) ? 'application/x-mpegurl' : ( $filetype == 'flv' ? 'video/flash' : 'video/mp4' );
				$source = array(
					'type' 	=> $mimetype,
					'src'  	=> $src.'?sd',
					'label'	=> 'SD',
					 'res'	=> '480'
				);
				break;
			case 'youtube' :
				$source = array(
					'type' => 'video/' . $type,
					'src'  => $src
				);
				break;
			case 'dash' :
				$source = array(
					'type' => 'application/dash+xml',
					'src'  => $src
				);
				break;
			case 'hls' :
					$source = array(
						'type' => 'application/x-mpegurl',
						'src'  => $src
					);
				break;
			case 'rtmp' :
				$source = array(
					'type' => 'rtmp/mp4',
					'src'  => $src
				);
				break;
			case 'flash' :
				$source = array(
					'type' => 'video/' . $type,
					'src'  => $src
				);
				break;
			default :
				if( $type == 'mp4_hd' ){ 
					$type = 'mp4'; 
				}
				$source = array(
					'type' 	=> 'video/' . $type,
					'src'  	=> $src.'?hd',
					'label'	=> 'HD',
					 'res'	=> '1080'
				);
		}	
		
		return $source;
		
	}
	
	
	public function getParams() {
	
		$config = array();
		$params = array();
		
		// Config
		foreach( $this->properties as $property ) {
			if( isset( $this->config->$property ) ) {
				$value = $this->config->$property;
				$config[ $property ] = is_numeric( $value ) && $property != 'ratio' ? (int) $value : $value;
			}
		}
		
		$itemId = $this->app->input->getInt( 'itemid' );
		$mid = $this->app->input->getInt( 'mid' );
		
		// Params ItemId
		if ( $itemId > 0 ) {
			$menuItem =  $this->app->getMenu()->getItem( $itemId );
			$params = $menuItem->params;
		} 
		
		// Params ModuleId
		if( $mid > 0 ) {
			jimport( 'joomla.application.module.helper' );
			$module = self::getModule( $mid,'','' );
			$params = json_decode( $module[0]->params, true );
		}
		
		if ( count( $params ) ) {

			foreach( $params as $key => $value ) {

				if ( in_array( $key,  $config ) ) {
					if ( $value != 'global' && $value !='' ) {
						$config[ $key ] = $params[ $key ];
					}				
				}
			}
		}
		
		// URL
		foreach( $_GET as $key => $value ) {
			if ( in_array( $key,  $config ) ) {
					if ( $value != 'global' && $value !='' ) {
						$config[ $key ] = $_GET[ $key ];
					}				
			}
		}
		
		// $config['responsive']  = 1;
		$config['baseurl']     = JURI::root();	


		return $config;
		
	}

	public  function getModule($module_id, $module_class, $module_style){
		
		$db = JFactory::getDBO();
		$document = JFactory::getDocument();
		$renderer = $document->loadRenderer('module');
		
		$params	= array('style'=>$module_style);
		
		$contents = '';
		$module = 0;
		
		//get module as an object		
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__modules');
		$query->where('id='.$db->q($module_id));		
		$rows = $db->setQuery($query);				
		$rows = $db->loadObjectList();

		$contents = $rows;

		return $contents;	

	}




}