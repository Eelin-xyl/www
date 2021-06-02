<?php

/*
 * @version		$Id: mod_yendifvideoshare_playlist.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
// Include the syndicate functions only once
require_once( dirname(__FILE__).DIRECTORY_SEPARATOR.'helper.php' );

require_once( JPATH_ROOT.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_yendifvideoshare'.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'player.php' );

require_once( JPATH_ROOT.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_yendifvideoshare'.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'utils.php' );

$config = YendifVideoShareUtils::getConfig();



$player_params = (array) json_decode( $params );

// Get the Plugin Params
if( isset( $attribs['catid'] ) && !empty( $attribs['catid'] ) ){
	$player_params = $attribs;
}


if( isset( $player_params['filterby'] ) && ! empty( $player_params['filterby']  ) ) {
	$player_params['featured'] = $player_params['filterby'];
}
unset( $player_params['filterby'] );
		
$playerObj = YendifVideoSharePlayer::getInstance();
$items = YendifVideoSharePlaylistHelper::getItems( $player_params, $config->schedule_video_publishing );


$moduleclass_sfx = htmlspecialchars( $params->get('moduleclass_sfx') );

require( JModuleHelper::getLayoutPath('mod_yendifvideoshare_playlist') );