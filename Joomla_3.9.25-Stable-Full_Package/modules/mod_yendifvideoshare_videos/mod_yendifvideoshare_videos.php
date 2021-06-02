<?php

/*
 * @version		$Id: mod_yendifvideoshare_videos.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
// Include the syndicate functions only once
require_once( dirname(__FILE__).DIRECTORY_SEPARATOR.'helper.php' );

require_once( JPATH_ROOT.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_yendifvideoshare'.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'utils.php' );
 
$document = JFactory::getDocument();
$document->addScript( YendifVideoShareUtils::prepareURL('media/yendifvideoshare/assets/site/js/yendifvideoshare.js') ); 
$app = JFactory::getApplication();

$config = YendifVideoShareUtils::getConfig();

$cols   = (int) $params->get('no_of_cols', $config->no_of_cols);
$rows   = (int) $params->get('no_of_rows', $config->no_of_rows);
$itemId = $params->get('itemid', 0);
$column = 0;

$show_excerpt = $params->get('show_excerpt', $config->show_excerpt);
$excerpt_length = $config->playlist_desc_limit;

$show_views = $params->get('show_views', $config->show_views);
if( $show_views == 'global' ) {			
	$show_views = $config->show_views;			
}

$show_rating = $params->get('show_rating', $config->show_rating);
if( $show_rating == 'global' ) {			
	$show_rating = $config->show_rating;			
}

$enable_popup = $params->get('enable_popup', $config->enable_popup);
if( $enable_popup == 'global' ) {			
	$enable_popup = $config->enable_popup;			
}

$ratio = $params->get('ratio', $config->ratio);

$items = YendifVideoShareVideosHelper::getItems( $params, $config->schedule_video_publishing, $rows * $cols );
$limit  = count( $items );

$has_more_btn = $params->get( 'show_more_btn', 0 );
if( $has_more_btn && $limit > 0 ) {
	$more_link = $params->get( 'more_btn_link', '' );
	if( '' == trim( $more_link ) ) {
		if( $params->get( 'catid', 0 ) > 0 ) {
		    $category = YendifVideoShareVideosHelper::getCategory( $items[0]->catid );
			$more_link = YendifVideoShareUtils::buildRoute( $category, 'category' );
		} else {
			$more_link = YendifVideoShareUtils::buildRoute();
		}
	}
} else {
	$has_more_btn = 0;
}

$document = JFactory::getDocument();

if( $config->bootstrap_version == 3 ) {
	$document->addStyleSheet( YendifVideoShareUtils::prepareURL('media/yendifvideoshare/assets/site/css/bootstrap.css', 'text/css', 'screen') );
}
$document->addStyleSheet( YendifVideoShareUtils::prepareURL('media/yendifvideoshare/assets/site/css/yendifvideoshare.css', 'text/css', 'screen') );
if( ! empty( $config->responsive_css ) ) {
	$document->addStyleDeclaration( $config->responsive_css );
}

if( $enable_popup ) {
	JHtml::_('jquery.framework');

	$document->addStyleSheet( YendifVideoShareUtils::prepareURL('media/yendifvideoshare/assets/site/css/magnific-popup.css', 'text/css', 'screen' ));
		
	$document->addScript( YendifVideoShareUtils::prepareURL('media/yendifvideoshare/assets/site/js/jquery.magnific-popup.min.js') );
	$document->addScript(YendifVideoShareUtils::prepareURL('media/yendifvideoshare/assets/site/js/yendifvideoshare.js') );
}

$moduleclass_sfx = htmlspecialchars( $params->get('moduleclass_sfx') );

require( JModuleHelper::getLayoutPath('mod_yendifvideoshare_videos') );