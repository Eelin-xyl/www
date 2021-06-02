	
<?php

/*
 * @version		$Id: player.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Import libraries
require_once( JPATH_ROOT.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_yendifvideoshare'.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'utils.php' );

class YendifVideoSharePlayer {	

	private $config = null;
	
	private $players = 0;
	private $license = array();
	
	private $properties = array(
		'autoplay',
		'analytics',
		'autoplaylist',
		'can_skip_adverts',
		'controlbar',
		'currenttime',
		'download',
		'duration',
		'embed',
		'engine',
		'fullscreen',
		'keyboard',
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
		'ima_vasturl'
	);   
	
	protected static $instance = null;
	public $width = -1;
	public $height = -1;

    public function __construct( $config = null ) {
	
		$this->config = $config == null ? YendifVideoShareUtils::getConfig() : $config;
		$this->width = $this->height = '100%';
		$this->addScript();
		
    }
	
	
	
	public function build( $params, $item = null, $model = null ) {
		
		$this->item = $item;	
		
		
		
		if( ! isset( $params['videoid' ] ) && $this->item == null ) return;
		
		$db = JFactory::getDBO();
		$app = JFactory::getApplication();	
		$html = '';

		
		if( isset( $params['videoid'] ) && $params['videoid'] > 0 ) {
			$db = JFactory::getDBO();
        	$query = "SELECT * FROM #__yendifvideoshare_videos WHERE id=".(int) $params['videoid'];
        	$db->setQuery( $query );
        	$this->item = $db->loadObject();
		}
		
		
		$ratio = ( isset( $params['ratio'] ) && ! empty( $params['ratio']  ) ) ?  $params['ratio'] :  $this->config->ratio;
		$moduleid = isset( $params['moduleId'] ) ? $params['moduleId'] : 0; 
		
		if( empty ( $ratio ) ){
			$ratio = 0.5625;
		}
		
		$ratio = ( $ratio * 100 );
		$ratio = min( 100, $ratio );
		
		$data_attrs = '';
		$attrs[] = sprintf( 'class="yendifplayers" style="padding-bottom: %s;" ',$ratio . '%');
		$attrs = implode( ' ', $attrs );
		
		
		// if video found
		if ( ! empty( $this->item ) ) {
		
			$input = JFactory::getApplication()->input;
			$Itemid = $input->getInt('Itemid');
			
			if( $this->item->type == 'thirdparty' ){
				$this->updateViews( $this->item->id );
				$poster = '';
				if( isset( $this->item->image ) && ! empty( $this->item->image ) ) {
					$poster = YendifVideoShareUtils::getImage( $this->item->image, '_poster', false );
				}

				$show_consent = $this->config->show_consent;
				$html = '<div class="yendif-responsive-media" id="yendif-responsive-media" style="padding-bottom:'.( $ratio).'%;">';
				if( $show_consent !=0 ){
					if( ! isset( $_COOKIE['yendif_gdpr_consent'] ) ) {
						$PosterObj= '';
						if( $poster ) $PosterObj = '<div class="gdpr-consent-poster" style="opacity: 0.4;"><img src="'.$poster.'" /></div>';
						$html .= '<div class="gdpr-consent-wrapper">' . 
									str_replace( 'src', 'data-src', $this->item->thirdparty ) . 
									'<div class="gdpr-consent-overlay" style="background-color: #000;">
										"'.$PosterObj.'"
										<div class="gdpr-overlay-content">
											<div class="gdprcookie-intro">
												 <h1>'.JText::_('YENDIF_VIDEO_SHARE_PRIVACY_POLICY').'</h1>
												<p>'.JText::_('YENDIF_VIDEO_SHARE_GDPR_DESCRIPTION').'</p>
											</div>
											<div class="gdprcookie-buttons">
												<button type="button" class="yendifgdprConsent">'.JText::_('YENDIF_VIDEO_SHARE_ACCEPT_COOKIED').' </button>
											</div>
										</div>
									 </div>
								 </div>';
					} else {
						$html .= $this->item->thirdparty;	
					}

				}else{
					$html .= $this->item->thirdparty;
				}
				$html .= '</div>';
				
			} else {
			
				$url = '';
				$urls = array();
				$customproperties = array ( 'mp4', 'mp4_hd', 'webm', 'ogg', 'youtube', 'rtmp', 'flash', 'dash', 'hls', 'captions' );
				
				if( isset( $this->item->id ) && ! empty( $this->item->id ) ){
					$urls['vid'] = $this->item->id;
				}
				
				if( ! empty( $Itemid ) ){
					$urls['itemid'] = $Itemid;
				}
				
				if( ! empty( $moduleid ) ){
					$urls['mid'] = $moduleid;
				}
			
				foreach( $customproperties as $property ) {
					if( isset( $this->item->$property ) &&  ! empty( $this->item->$property ) ) {
						$value = $this->item->$property;
						$urls[ $property ] = is_numeric( $value ) && $property != 'ratio' ? (int) $value : $value;
					}
				}
				
				foreach( $this->properties as $conproperty ) {
					if( isset( $params[$conproperty] ) ){
						$value = $params[$conproperty];
						$urls[ $conproperty ] = is_numeric( $value ) && $conproperty != 'ratio' ? (int) $value : $value;
					}
				}
				
				$seturl = array();
				foreach( $urls as $key => $value ) {
					$seturl[$key] = '&'.$key.'='.$value;
				}
				
				$seturl = implode( '', $seturl );
				
				$url = JURI::root() . 'index.php?option=com_yendifvideoshare&view=player' . $seturl . '&format=raw';
				//$url = JURI::root() . 'index.php?option=com_yendifvideoshare&view=player&vid=' . $this->item->id . '&itemid=' . $Itemid . '&mid=' . $moduleid . '&format=raw';
			
				$html = sprintf( '<div %s style="padding-bottom: %s;"><iframe  width="560" height="315" src="%s" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>',$attrs, $ratio . '%', $url );
			
			}
		}
			
		// return...
		 return $html;
	
	}
	
	public static function getInstance( $config = null ) {
		if( null == self::$instance ) {
			self::$instance = new self( $config );
		}

		return self::$instance;
	}
	
	public function addScript() {	
		 
		$document = JFactory::getDocument();

		$document->addStyleSheet( YendifVideoShareUtils::prepareURL( 'media/yendifvideoshare/assets/site/css/yendifvideoshare.css', 'text/css', 'screen' ) );
		
		$document->addScript( 'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js' );
				
		$document->addScript(  YendifVideoShareUtils::prepareURL( 'media/yendifvideoshare/assets/site/js/yendifvideoshare.js' ) );		
		
	}
	
	
	public function embedPlayer( $params ) {
	
		$html  = '';
		$html  = '<!DOCTYPE html>';
		$html .= '<html>';
		$html .= '<head>';
		$html .= sprintf( '<link rel="stylesheet" href="%s" />', YendifVideoShareUtils::prepareURL( 'media/yendifvideoshare/assets/site/css/yendifvideoshare.css', false ));
		$html .= '<style type="text/css">body, iframe { margin: 0 !important; padding: 0 !important; background: transparent !important; }</style>';
		$html .= '</head>';
		$html .= '<body>';
		$html .= $this->build( $params, '', true );
		$html .= '</body>';
		$html .= '</html>';
		
		return $html;
		
	}
	
    public function updateViews( $videoid ) {
		
		$session = JFactory::getSession();	
		$ses_videos = $session->get('yendif_videos', array());

		if( ! in_array( $videoid, $ses_videos ) ) {
		    $ses_videos[] = $videoid;
				
		 	$db = JFactory::getDBO();     	    
		 	$query = "SELECT views FROM #__yendifvideoshare_videos WHERE id=" . (int) $videoid;
    	 	$db->setQuery ( $query );
    	 	$result = $db->loadObject();
		 
		 	$count = $result ? $result->views + 1 : 1;	 
		 	$query = "UPDATE #__yendifvideoshare_videos SET views=".$count." WHERE id=" . (int) $videoid;
    	 	$db->setQuery ( $query );
		 	$db->query();
		 
		 	$session->set('yendif_videos', $ses_videos);
		}
		
	}
	
}
