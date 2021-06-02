<?php
/*
 * @version		$Id: default.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies PVT Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$document = JFactory::getDocument();
$vid_attrs  = 'controls';
$playerid = 'yvsplayer';
$showConsent = 0;
$gdpr = 1;
$allowQualitySwitch = 0;
$autoplay = false;


$this->params = $this->getParams();
$sources = $this->getSources();

//Player Options
$vid = ( isset( $this->video->id ) &&  ! empty( $this->video->id ) )  ?  $this->video->id : 0;
$theme = ( ! empty( $this->params['theme'] ) ) ? $this->params['theme'] : '';
$share =  ( ! empty( $this->params['share'] ) ) ? $this->params['share'] : 0;
$embed = ( ! empty( $this->params['embed'] ) ) ? $this->params['embed'] : 0;
$download =  ( ! empty( $this->params['download'] ) ) ? $this->params['download'] : 0;
$controlbar = ( ! empty( $this->params['controlbar'] ) ) ? $this->params['controlbar'] : 0;
$autoplaylist = ( ! empty( $this->params['autoplaylist'] ) ) ? $this->params['autoplaylist'] : 0;
$volume = ( ! empty( $this->params['volume'] ) ) ? $this->params['volume'] : 0;
$logoClickURL = empty( $this->params['license'] ) ? 0 : '"'. JURI::root().'"';
$analytics = ( isset( $this->params['analytics'] ) &&  ! empty( $this->params['analytics'] ) )  ?  "'". $this->params['analytics']."'" : 0;

if( ! $this->is_mobile() ) {
	$autoplay = ( ! empty( $this->params['autoplay'] ) ) ? true : false;
}


// Video Attributes
$attributes = array();
$attributes[] = $theme;

if( ! empty ( $embed ) ) {
	$attributes[] = 'yendif-embed-enable';
}

if( empty ( $this->video->id ) ) {
	$attributes[] = 'yendif-notid-disable';
}

if( $autoplay ) {
	$attributes[] = 'yendif-autoplay-enable';
}

$attributes = implode( ' ', $attributes );


//Thumb Image
$poster = '';
if( isset( $this->video->image ) && ! empty( $this->video->image ) ) {

	if( empty ( $autoplay ) ) {
		$poster = YendifVideoShareUtils::getImage( $this->video->image, '_poster', false );
	}
	
	$vid_attrs .= ' poster='.$poster;
}


// Player Option
$settings = array(
	//'techOrder'                 => ['flash', 'html5'],
	'bigPlayButton'   			=> ( ! empty( $this->params['playbtn'] ) ) ? true : false,
    'autoplay'     				=> $autoplay,
    'loop'  					=> ( ! empty( $this->params['loop'] ) ) ? true : false,
    'nativeControlsForTouch'   	=> false,
	'customControlsOnMobile'    => true,
    'playsinline'    			=> 1,
	'controls' 					=> true,
	'controlBar' => array(
		'playToggle' 			=> ( ! empty( $this->params['playpause'] ) ) ? true : false,
		'currentTimeDisplay' 	=> ( ! empty( $this->params['currenttime'] ) ) ? true : false,
		'progressControl' 		=> ( ! empty( $this->params['progress'] ) ) ? true : false,
		'durationDisplay' 		=> ( ! empty( $this->params['duration'] ) ) ? true : false,
		'remainingTimeDisplay'  => ( ! empty( $this->params['duration'] ) ) ? true : false,
		'volumePanel' 			=> ( ! empty( $this->params['volumebtn'] ) ) ? true : false,
		'fullscreenToggle' 		=> ( ! empty( $this->params['fullscreen'] ) ) ? true : false,
		'children'      => array( 
			'playToggle'       		=> true, 
			'currentTimeDisplay'	=> true, 
			'progressControl' 		=> true,
			'durationDisplay'       => true,
			'remainingTimeDisplay'  => true,
			'volumePanel'       	=> true,
			'fullscreenToggle'      => true
		)
	)	
);


// Share Option
$shareOption = array ();
if( isset( $this->video->id ) &&  ! empty( $this->video->id ) ) {
	$shareOption = array(
		'title' => JText::_('COM_YENDIFVIDEOSHARE_SHARE_TITLE'),
		'embed' => JText::_('COM_YENDIFVIDEOSHARE_EMBED_TITLE'),
		'code' => '<iframe width="560" height="315" src="' .JURI::root(). 'index.php?option=com_yendifvideoshare&view=video&id='.$this->video->id.'&tmpl=component" frameborder="0" allow="autoplay; encrypted-media" class="yvs-socialshare" allowfullscreen></iframe>',
		'url' => $this->shareUrl,
		'icon' => array(
			'title'	=> 'Yendifvideoshare',
			 'fb'	=> array(
				'image'	=> JURI::root().'media/yendifvideoshare/player/images/facebook.png',
				'url'	=> 'https://www.facebook.com/sharer.php?u='
			 ),
			 'twitter'	=> array(
				'image'	=> JURI::root().'media/yendifvideoshare/player/images/twitter.png',
				'url'	=> 'https://twitter.com/intent/tweet?url='
			 ),
			 'google'	=> array(
				'image'	=> JURI::root().'media/yendifvideoshare/player/images/gplus.png',
				'url'	=> 'https://plus.google.com/share?url='
			 ),
			 'vk'	=> array(
				'image'	=> JURI::root().'media/yendifvideoshare/player/images/linkedin.png',
				'url'	=> 'https://www.linkedin.com/shareArticle?url='
			 ),
			 'ok'	=> array(
				'image'	=> JURI::root().'media/yendifvideoshare/player/images/pinterest.png',
				'url'	=> 'https://pinterest.com/pin/create/bookmarklet/?media='
			 )
		)
	);
}


?>
<!DOCTYPE html>
<html>
<head>
	<?php JHtml::_('jquery.framework'); ?>
	<meta charset="utf-8">
	<title><?php echo $this->getTitle(); ?></title>    
    <link rel="canonical" href="<?php echo $this->shareUrl; ?>" />
    <meta property="og:url" content="<?php echo $this->getURL(); ?>" />
	<link rel="stylesheet" href="<?php echo JURI::root(); ?>media/yendifvideoshare/player/video-js.min.css?v=<?php echo YENDIFVIDEOSHARE_VERSION; ?>" />
    <link rel="stylesheet" href="<?php echo JURI::root(); ?>media/yendifvideoshare/player/videojs-sublime-skin.css?v=<?php echo YENDIFVIDEOSHARE_VERSION; ?>" />
	<style type="text/css">
        html, 
        body, 
        video, 
        iframe {
            width: 100% !important;
            height: 100% !important;
            margin:0 !important; 
            padding:0 !important; 
            overflow: hidden;
        }
            
        video, 
        iframe {
            display: block;
        }
		
		.yvs-player {
            width: 100% !important;
            height: 100% !important;
		}
		
		*:focus {
			outline: none;
		}
		
		.video-js .vjs-big-play-button:focus, .video-js:hover .vjs-big-play-button {
			background-color: transparent;
		}
		
		.vjs-paused .vjs-big-play-button {
			display:block !important;
		}
		
		.yvs-player.yendif-autoplay-enable .vjs-big-play-button,
		.yvs-player.yendif-autoplay-enable .vjs-poster {
			display:none;
			background-image:none !important;
		}
		
		.yvs-player.yendif-autoplay-enable .vjs-loading-spinner {
			display:block;
			visibility: visible;
		}
		.yvs-player.yendif-autoplay-enable .vjs-loading-spinner:before {
			-webkit-animation: vjs-spinner-spin 1.1s cubic-bezier(0.6, 0.2, 0, 0.8) infinite, vjs-spinner-fade 1.1s linear infinite;
  			animation: vjs-spinner-spin 1.1s cubic-bezier(0.6, 0.2, 0, 0.8) infinite, vjs-spinner-fade 1.1s linear infinite;
		}
		
		.yendif-notid-disable .vjs-share-open,.yendif-notid-disable .vjs-download{display:none!important}.video-js .vjs-remaining-time,.vjs-share .vjs-share-code,.vjs-share .vjs-share-icon-list{display:none}.yendif-embed-enable .vjs-share .vjs-share-code,.yendif-share-enable .vjs-share .vjs-share-icon-list{display:block}#vjs-share-open-button,.vjs-user-inactive #vjs-share-open-button{-webkit-transition:.3s ease-in-out;-moz-transition:.3s ease-in-out;-o-transition:.3s ease-in-out;transition:.3s ease-in-out}.vjs-user-inactive #vjs-share-open-button{margin-right:-50px}#vjs-share-open-button-info{background-color:rgba(0,0,0,0.6);color:rgba(255,255,255,0.9);font-size:16px;padding:12px 5px;text-align:center;float:right;cursor:pointer;position:absolute;right:53px;top:2px;display:none}.vjs-share{background-color:#000;display:none;height:100%;left:0;overflow:hidden;padding:5px;position:absolute;right:0;top:0;bottom:0;width:100%;z-index:99999999}.vjs-share-parent-container{position:absolute;top:0;bottom:0;left:0;width:100%;height:100%;z-index:100;padding:0;margin:0;display:-webkit-box;display:-moz-box;display:-ms-flexbox;display:-webkit-flex;display:flex;align-items:center;justify-content:center}.vjs-share-active{display:block}.vjs-share-child-container{width:100%}.vjs-share-title{color:#999;font-size:12px;text-align:center;text-shadow:1px 1px 10px #000}.vjs-share-code-tile,.vjs-share-code-description{color:#EEE;font-size:12px;text-align:center;text-shadow:1px 1px 10px #000}.vjs-share-code-tile{color:#999}.vjs-share-close{background:url(./media/yendifvideoshare/assets/site/images/icons_white.png);background-color:transparent;background-position:2px -98px;color:#EEE;background-repeat:no-repeat;width:30px;height:30px;top:10px;right:10px;z-index:999999999;float:right;cursor:pointer;position:absolute}.vjs-share-open{position:absolute;background-color:rgba(0,0,0,0.6);background-position:2px -147px;background-repeat:no-repeat;width:30px;height:30px;top:10px;right:10px;z-index:99999999;font-size:20px;padding:5px;float:right}.vjs-share-open:hover{cursor:pointer}.vjs-share-url{font-size:14px;font-weight:400;margin-left:3px;margin-right:3px;margin-bottom:3px;width:23%;text-decoration:none;color:#fff;background-color:rgba(4,4,4,0.35);border:1px solid rgba(0,0,0,0.38);cursor:pointer;text-align:center;white-space:normal;line-height:1.3;padding:0;height:44%;overflow:hidden}.vjs-share-url:hover{background-color:rgba(166,0,255,0.35)}.vjs-share-img{height:auto;margin-bottom:5px;width:100%}.vjs-share-icon-list{width:100%;margin:0 auto;text-align:center}.vjs-share-icon-list a{display:inline-block;background-repeat:no-repeat;background-position:center;margin-right:12px;width:40px;height:40px;cursor:pointer;background-size:100% auto;background-position:center top;background-repeat:no-repeat}.vjs-share-code{font-size:10pt;margin:20px;line-height:1.5}.vjs-share-code-text{background-color:#FFF;-webkit-box-shadow:0 0 10px 2px #000;-moz-box-shadow:0 0 10px 2px #000;box-shadow:0 0 10px 2px #000;border:none;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;color:#666;width:100%;margin:0;padding:4px;font-size:12px;-webkit-user-select:text;-moz-user-select:text;-ms-user-select:text;user-select:text}.vjs-share-code-description{margin-top:10px}.vjs-share-open{display:none}.vjs-has-started .vjs-share-open{display:block}.vjs-has-started .vjs-download{display:block}.vjs-download{display:none}.vjs-download,.vjs-user-inactive .vjs-download{-webkit-transition:.3s ease-in-out;-moz-transition:.3s ease-in-out;-o-transition:.3s ease-in-out;transition:.3s ease-in-out}.vjs-user-inactive .vjs-download{margin-right:-50px}.vjs-download{position:absolute;width:30px;height:30px;top:50px;right:10px;background:url(./media/yendifvideoshare/assets/site/images/download.png);background-color:rgba(0,0,0,0.6);background-position:center;background-repeat:no-repeat;z-index:9999;cursor:pointer}.vjs-resolution-button{color:#fff}.vjs-resolution-button .vjs-resolution-button-staticlabel:before{font-family:VideoJS;content:'\f110';font-size:1.8em;line-height:1.67}.vjs-resolution-button .vjs-resolution-button-label{font-size:1.1em;line-height:3em;position:absolute;top:0;left:0;width:100%;height:100%;text-align:center;box-sizing:inherit;font-family:Arial,Helvetica,sans-serif}.vjs-resolution-button ul.vjs-menu-content{width:4em!important;overflow:hidden;z-index: 99999;}.vjs-resolution-button .vjs-menu{left:0!important}.vjs-resolution-button .vjs-menu li{text-transform:none;font-size:1em;font-family:Arial,Helvetica,sans-serif}.video-js .vjs-current-time,.vjs-no-flex .vjs-current-time,.video-js .vjs-duration,.vjs-no-flex .vjs-duration{display:block!important}.video-js .vjs-control-bar{background-color:rgba(0,0,0,0.6);font-weight:700}.vjs-ad-playing .vjs-control-bar{visibility:visible!important;opacity:1!important}.vjs-paused.vjs-user-inactive img.yf-logo,.vjs-user-active img.yf-logo{display:block!important}.vjs-user-inactive img.yf-logo{display:none!important}.white .video-js{background-color:#FFF;color:#000}.white .vjs-control-bar{background-color:#EEE;background:-webkit-gradient(linear,0% 0%,0% 100%,from(#FFF),to(#E0E0E0));background:-webkit-linear-gradient(top,#FFF,#E0E0E0);border-top:1px solid #FFF;color:#000000eb}.white .vjs-sublime-skin .vjs-fullscreen-control{border:3px solid #000;box-sizing:border-box;cursor:pointer;margin-top:-7px;top:50%;height:14px;width:22px;margin-right:10px}.white .vjs-share-open,.white .vjs-share-open,.white .vjs-download,.white .vjs-download,.white .vjs-big-play-button{color:#fff}.white .vjs-resolution-button-label,.white .vjs-resolution-button-label{color:#000}.white .vjs-volume-level{background:url(./media/yendifvideoshare/assets/site/images/vol-black.png) no-repeat center center;background-size:25px 20px;background-repeat:no-repeat;max-width:22px;max-height:17px;height:100%}.white .vjs-fullscreen-control{border:3px solid #333}.vjs-ad-playing .yendif-ad-controls-hidden,.vjs-ad-playing .vjs-current-time,.vjs-ad-playing .vjs-progress-control,.vjs-ad-playing .vjs-duration,.vjs-ad-playing .vjs-resolution-button,.vjs-ad-playing .vjs-volume-panel,.vjs-ad-playing .vjs-volume-control,.vjs-ad-playing .vjs-share-open,.vjs-ad-playing .vjs-download,.vjs-ad-playing .vjs-text-track-display,.vjs-ad-playing a{display:none!important}.vjs-youtube-mobile .vjs-poster{display:none}.vjs-youtube-mobile .vjs-big-play-button,.vjs-youtube-mobile .vjs-resize-manager{display:none!important}.gdpr-consent-overlay{position:absolute;top:0;bottom:0;width:100%;height:100%;z-index:99999999;padding:0;margin:0;display:-webkit-box;display:-moz-box;display:-ms-flexbox;display:-webkit-flex;display:flex;align-items:center;background-color:#43333391;overflow:auto}.gdpr-consent-overlay .gdpr-overlay-content{color:#fff;background:#000;margin:auto;width:60%;padding:15px;border-radius:6px;position:relative}.gdpr-consent-overlay .gdprcookie-intro h1{color:#fff;font:bold 17px Quicksand,sans-serif;text-align:center}.gdpr-consent-overlay .gdprcookie-intro p{line-height:16px;font-size:15px;text-align:center;font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;}.gdprcookie-buttons{padding:10px}.gdpr-consent-overlay .gdprcookie-buttons button{border:none;background:red;color:#fff;font-size:13px;padding:10px;border-radius:3px;margin:auto;display:block;cursor:pointer;transition:all .3s ease-in}.gdpr-consent-overlay .gdprcookie-buttons button:hover{background:red;color:#fff;transition:all .3s ease-in}@media only screen and (max-width: 720px){.gdpr-consent-overlay .gdpr-overlay-content{width:80%}}
@media only screen and (max-width: 480px){ .gdpr-consent-overlay .gdpr-overlay-content,.gdprcookie-intro,.gdprcookie-buttons{padding-top:7px}.gdpr-consent-overlay .gdprcookie-buttons button{padding:5px}.gdpr-consent-overlay .gdprcookie-intro p,.gdpr-consent-overlay .gdprcookie-intro h1{font-size:10px;line-height:13px}.vjs-share-title,.vjs-share-code-description{display:none}.vjs-share-icon-list a{width:25px;height:25px;margin:0 5px}.vjs-share-code{margin:10px 5px 5px;font-size:11px}.vjs-share-embed-title{display:block;padding:0}.vjs-share-close{top:0;right:0}.video-js .vjs-volume-panel {display:none !important;}}
		
		<?php if ( ! empty( $this->config->progress_bar_color ) ) { ?>
				.video-js .vjs-play-progress{
					background-color: <?php echo $this->config->progress_bar_color; ?>;
				}
		<?php } ?>
		
		<?php if ( $embed == 0  ) { ?>
				#yvsplayer .vjs-download {
					top: 10px;
				}
		<?php }
			
			if ( $controlbar == 0 ) { ?>
				#yvsplayer .vjs-control-bar {
					display:none;
				}
		<?php }
			if( $this->config->show_adverts_timeframe == 0 && 'custom' == $this->config->ad_engine ){ 	?>

				#yvsplayer .ima-controls-div {
					display:none !important;
				}
		<?php } 
			if( $this->is_mobile() ) { ?>
			.video-js .vjs-volume-panel {display:none !important;}
		<?php } ?>	

    </style> 
    <?php
    if ( ! empty( $this->config->responsive_css ) ) {
        printf( '<style type="text/css">%s</style>', $this->config->responsive_css );
    }
    ?>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js?v=<?php echo YENDIFVIDEOSHARE_VERSION; ?>" type="text/javascript"></script>
    <script src="<?php echo JURI::root(); ?>media/yendifvideoshare/player/video.min.js?v=<?php echo YENDIFVIDEOSHARE_VERSION; ?>" type="text/javascript"></script>
    <script src="<?php echo JURI::root(); ?>media/yendifvideoshare/player/videojs-plugins.min.js?v=<?php echo YENDIFVIDEOSHARE_VERSION; ?>" type="text/javascript"></script>
   
</head>
<body>
    <video id="yvsplayer" preload="auto" class="yvs-player video-js vjs-sublime-skin <?php echo $attributes;?>" <?php echo $vid_attrs;?>>
      <?php 
        foreach ( $sources as $source ) {
		
            if ( ! empty( $source['label'] ) && ! empty( $source['res'] )) {
                printf( '<source type="%s" src="%s" label="%s" res="%s" />', $source['type'], $source['src'], $source['label'], $source['res'] );
            } else {
                printf( '<source type="%s" src="%s" />', $source['type'], $source['src'] );
            } 
			
			//Check GDPR Consent
			if( ! empty( $source['src'] ) ) {
				$showConsent = ( 'video/youtube' == $source['type'] && ! empty( $this->config->show_consent ) && ! isset( $_COOKIE['yendif_gdpr_consent'] ) );
				
				if( 'video/youtube' == $source['type'] && ! empty( $this->config->show_consent ) && ! isset( $_COOKIE['yendif_gdpr_consent'] ) ) {
					$gdpr = ( ! empty( $_COOKIE['yendif_gdpr_consent'] ) ) ? $_COOKIE['yendif_gdpr_consent'] : 0;	
				}

			}
			
			if( 'video/youtube' == $source['type'] || 'video/mp4' == $source['type'] ) {
				$allowQualitySwitch = 1;
			}
			
        }
		
		$tracks = $this->getTracks();
		if( ! empty( $tracks  )) { 
			printf( '<track kind="captions" src="%s" srclang="en" label="English" default />', $this->getTracks() );
		}
		?>
	</video>
    <?php if ( $showConsent ) { ?>
    	<div class="gdpr-consent-overlay" style="background-color: #000;">
        	<div class="gdpr-overlay-content">
            	<div class="gdprcookie-intro">
                    <h1><?php echo JText::_('YENDIF_VIDEO_SHARE_PRIVACY_POLICY'); ?></h1>
                    <p><?php echo JText::_('YENDIF_VIDEO_SHARE_GDPR_DESCRIPTION'); ?></p>
                </div>
                <div class="gdprcookie-buttons">
                	<button type="button" id="yendifgdprConsent" class="yendifgdprConsent"><?php echo JText::_('YENDIF_VIDEO_SHARE_ACCEPT_COOKIED'); ?></button>
                </div>
            </div>
    	</div>
    <?php } ?>
 
 <script>
 	var yendifplayer = {};
	var yendifplayer = <?php echo json_encode( $this->getParams() ); ?>
	
	if( typeof( yendif ) === 'undefined' ) {
		var yendif = {};
	}; 
	
	yendif.i18n = [];
	yendif.i18n['avertisment'] = '".<?php echo JText::_('COM_YENDIFVIDEOSHARE_ADVERTISMENT'); ?>."';
	yendif.i18n['show_skip'] = '".<?php echo JText::_('COM_YENDIFVIDEOSHARE_SHOW_SKIP'); ?>."';
	yendif.i18n['share_title'] = '".<?php echo JText::_('COM_YENDIFVIDEOSHARE_SHARE_TITLE'); ?>."';
	yendif.i18n['embed_title'] = '".<?php echo JText::_('COM_YENDIFVIDEOSHARE_EMBED_TITLE'); ?>."';
	
	function yendifsetCookie( name, value ) {
		document.cookie = name + "=" + value;
	}
	
	function yendifViewCount( id ) {
	
		var __url = yendifplayer.baseurl + 'index.php?option=com_yendifvideoshare&view=ajax&format=raw&task=updateviews&id=' + id;
		
		$.get( __url, function( data ) {
			// console.log( 'View Count: ' + id );
		});
	
   };

	
 </script>

 
 <script type="text/javascript">
	(function() {
		'use strict';

			var vid = <?php echo $vid ?>;
			var settings = <?php echo json_encode( $settings ); ?>;
			var shareOption = <?php echo json_encode( $shareOption ); ?>;
			var embed = <?php echo $embed; ?>;
			var autoplaylist = <?php echo $autoplaylist; ?>;
			var volume = <?php echo $volume; ?>;
			var logoClickurl = <?php echo $logoClickURL; ?>;
			var allowQualitySwitch = <?php echo $allowQualitySwitch; ?>;
			var gdpr = <?php echo $gdpr; ?>;
			
 
			// Initialize Player
			var player = videojs( 'yvsplayer', settings );
			
			player.on( 'play', function() {
				jQuery( '#yvsplayer.vjs-has-started ' ).removeClass( 'yendif-autoplay-enable' );
			});
			
			// Set Volume
			if( volume ){
				player.volume( volume/100 );
			}
						
			// Initialize Embed & Socialsharing
			if ( ( embed == 1 ) && shareOption != '' ) {
				player.share( shareOption );
			}
						

			var isRTMP = /rtmp:/.test( player.src() ); 
			var isMobile = navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/Android/i);			
	
			// check if mobile autoplay pause
			if ( 0 == gdpr || isMobile ) {
				player.pause();
			}	
			
			// View Count
			if( vid > 0 ) {
				yendifViewCount( vid );
			}
		
			jQuery( '.yendifgdprConsent' ).click(function() {
				yendifsetCookie( 'yendif_gdpr_consent',1 );
				jQuery('iframe').attr('src', function() { return jQuery(this).attr('data-src'); })
				.removeAttr('data-src');
				location.reload(true);
			
		   });
		   
		   player.on('touchstart', function (e) {
				if (e.target.nodeName === 'VIDEO') {
					if (player.paused()) {
						this.play();
					} else {
						this.pause();
					}
				}
		  });
		   
		   
		   jQuery('body').on('click','.yf-logo', function(){
			   top.window.location.href = logoClickurl;
			});
		  
		   // AutoplayList Enable
		   if( autoplaylist == 1 ) {
			   player.on( 'ended', function() {
					parent.postMessage( { message: 'YVS_VIDEO_ENDED', iframeId: window.frameElement.id }, '*' ); 
			   });
			}
			
	})( );
 </script>
 
</body>
</html>

