<?php 

/*
 * @version		$Id: default.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access'); 
$typess = array(
		'custom'  => array( 'mp4', 'mp4_hd', 'webm', 'ogg', 'youtube', 'rtmp', 'flash', 'dash', 'hls', 'captions' ),
		'video'   => array( 'mp4', 'mp4_hd', 'webm', 'ogg', 'captions' ),
		'youtube' => array( 'youtube', 'captions' ),		
		'rtmp'    => array( 'mp4', 'rtmp', 'flash', 'dash','hls', 'captions' )
	);
	
//get ModuleId
$moduleId = isset( $module->id ) ? $module->id : 0;
$isMobile = 0;
$iframeids = 0;
$iframeid = 0;


?>

<div class="yendif-video-share <?php echo $moduleclass_sfx; ?>">
	<?php

		++$iframeid;
		$iframeid = uniqid( 'yendif' . $iframeids);
		
		//unset Params Key
		foreach( $player_params as $key => $value ) {
			if( $value == '' || $value == 'global' ) unset( $player_params[ $key ] );
		}

		$ratio = isset( $player_params['ratio'] ) ? (int) $player_params['ratio'] : (int) $config->ratio;
		if( empty ( $ratio ) ){
			$ratio = 0.5625;
		}
		
		//check Mobile
		if ( preg_match( '/iPhone|iPod|iPad|BlackBerry|Android/', $_SERVER['HTTP_USER_AGENT'] ) ) {
				$isMobile = 1;
		}
		
		if( empty ( $items ) ){
			return;
		}
		
		$ratio = ( $ratio * 100 );
		$ratio = min( 100, $ratio );
		
		//get ItemId
		$input = JFactory::getApplication()->input;
		$Itemid = $input->getInt('Itemid'); 
		
		//Check Playlist Params Config
		$playlistPosition = isset( $player_params['playlist_position'] ) ? $player_params['playlist_position'] : $config->playlist_position;
		$playlistWidth =  isset( $player_params['playlist_width'] ) ? $player_params['playlist_width'] : $config->playlist_width;
		$playlistHeight = isset( $player_params['playlist_height'] ) ? $player_params['playlist_height'] : $config->playlist_height;
		$playerTheme = isset( $player_params['theme'] ) ? $player_params['theme'] : $config->theme;
		$playlist_title_limit = isset( $player_params['playlist_title_limit'] ) ? $player_params['playlist_title_limit'] : $config->playlist_title_limit;
		$playlist_desc_limit = isset( $player_params['playlist_desc_limit'] )   ? $player_params['playlist_desc_limit'] : $config->playlist_desc_limit;
		
		$url = '';
		$urls = array();
		$configproperties = array( 'autoplay', 'embed','fullscreen','loop','playbtn','playlist_height','playlist_position','playlist_width','playpause','progress','ratio','theme','volume','autoplaylist','controlbar','currenttime','download','duration','share' );
		foreach( $configproperties as $conproperty ) {
			if( isset( $player_params[$conproperty] ) ){
				$value = $player_params[$conproperty];
				$urls[ $conproperty ] = is_numeric( $value ) && $conproperty != 'ratio' ? (int) $value : $value;
			}
		}
		
		$seturl = array();
		foreach( $urls as $key => $value ) {
			$seturl[$key] = '&'.$key.'='.$value;
		}
		$seturl = implode( '', $seturl );
		
		//Build iframe Url
		$url = JURI::root() . 'index.php?option=com_yendifvideoshare&view=player&vid=' . $items[0]->id . '&itemid=' . $Itemid . '&mid=' . $moduleId . ''.$seturl.'&format=raw';
	?>
    <style>
		@media only screen and (max-width: 480px) {
			.yendif-playlist-container .vjs-playlist { 
				height:<?php echo $playlistHeight;?>px;
			}
			
			.yendif-playlist-container .vjs-playlist.bottom {
				height:<?php echo $playlistHeight;?>px;
			}
		}
	</style>
    <div class="yendif-playlist-container">
    	<?php if( $playlistPosition == 'bottom' ) {  ?>
			<div class="yendif-playlist-player"  style="width:100%; box-sizing: border-box;">
		<?php } else { ?>
			<div class="yendif-playlist-player"  style="width: calc(100% - <?php echo $playlistWidth; ?>px);  box-sizing: border-box;">
		<?php } ?>
                <div class="yendifplayers" style="padding-bottom: <?php echo $ratio; ?>%">
                    <iframe id="<?php echo $iframeid; ?>" class="yvs-player" width="560" height="315" src="<?php echo $url;?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
             </div>
             <div class="vjs-playlist vjs-playlists <?php echo $playlistPosition; ?> <?php echo $playerTheme; ?> vjs-playlist-vertical vjs-csspointerevents vjs-mouse"  style="width:<?php echo $playlistWidth; ?>px;  box-sizing: border-box;">
             <ul id="vjs-playlist-data" class="vjs-playlist-data vjs-playlist-item-list" style="display: block;">
             <?php
			 	$u = JURI::getInstance( JURI::base() );
				if($u->getScheme()){
					$link = $u->getScheme().'://';
				}else{
					$link = 'http://';
				}
				$link .= $u->getHost();
				
				$mobile = '';
				$index = 0;
				$input = JFactory::getApplication()->input;
				$Itemid = $input->getInt('Itemid'); 
	
				foreach( $items as $item ) {
					if ( 'rtmp' == $item->type && ! empty( $item->mp4 ) ) {
					
						if ( $isMobile == 1 ) {				
							$item->type = 'video';
						} else {
							$item->mp4 = '';
						}
					}

					$types = $typess[ $item->type ];
					array_push( $types, 'id', 'image', 'title', 'description','duration');
					$count = count( $types );
					$attr = array();
					$attributes = array();
					$description = '';
					$image = '';
					$duration = '';	
					$selectedClass = '';
					$attr[] = sprintf( 'data-shareurl="%s"', $link.YendifVideoShareUtils::buildRoute( $item, 'video' ) );
					$attr[] = sprintf( 'data-mid="%s"', $moduleId );
					$attr[] = sprintf( 'data-baseurl="%s"', JURI::root() );
					
					for( $i = 0; $i < $count; $i++ ) {		
						$type = $types[$i];
						if( isset( $item->$type ) && ! empty( $item->$type ) ) {
							$src = $item->$type;
							switch($type) {	
								case 'id' :
									$type = 'vid';
									$src = $item->id;
									break;		
								case 'duration' :
									$duration = $item->duration;
									break;		
								case 'image' :
									$type = 'poster';
									$image = YendifVideoShareUtils::getImage( $item->image, '_poster', $config->default_image );							
									break;
								case 'title' :;
									$title = YendifVideoShareUtils::Truncate( $src, $playlist_title_limit );
									break;
								case 'description' :
									$description = YendifVideoShareUtils::Truncate( $src, $playlist_desc_limit );
									break;
								case 'mp4' :
									$filetype = strtolower( JFile::getExt( $src ) );
									$type = ( $filetype == 'm3u8' ) ? 'mpegurl' : ( $filetype == 'flv' ? 'flash' : 'mp4' );
									break;
								case 'mp4_hd' :
									$type = 'hd';
									break;
						}
							
							$attr[ $type ] = sprintf( 'data-'.$type.'="%s"', $src );
							$attributes[ $type ] = $src;	
							$imploded = implode(' ', $attr);
						};		
					};
					++$index;

					if( $index == 1 ){
						$selectedClass = 'vjs-selected';
					}
					if( empty ( $image ) ) {
						$image = $config->default_image;
					}
					
					?>
					<li class="vjs-playlist-item <?php echo $selectedClass; ?>" <?php echo $imploded; ?>>
                    	<picture class="vjs-playlist-thumbnail vjs-playlist-now-playing">
                        	<source srcset="<?php echo $image; ?>" media="( min-width: 400px; )">
                            <img alt="" src="<?php echo $image; ?>">
                            <span class="vjs-playlist-now-playing-text"></span>
                            <div class="vjs-playlist-title-container">
                            	<span class="vjs-up-next-text" title="Up Next">Up Next</span>
                                <cite class="vjs-playlist-name" title="<?php echo $title; ?>"><?php echo $title; ?></cite>
                                <div class="vjs-playlist-description"><?php if( isset( $description ) && ! empty( $description ) ) echo $description; ?></div>
                            </div>
                       </picture>
                       <?php if( isset( $duration ) && ! empty( $duration ) ):?>
                       	<time class="vjs-playlist-duration"><?php echo $duration; ?></time>
                       <?php endif; ?>
					</li>	
			<?php }; ?>
             </ul>
             </div>
    </div>

</div>

