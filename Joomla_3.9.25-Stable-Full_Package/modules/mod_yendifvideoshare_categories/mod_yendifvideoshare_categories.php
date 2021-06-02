<?php

/*
 * @version		$Id: mod_yendifvideoshare_categories.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
// Include the syndicate functions only once
require_once( dirname(__FILE__).DIRECTORY_SEPARATOR.'helper.php' );
require_once( JPATH_ROOT.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_yendifvideoshare'.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'utils.php' );
 
$app = JFactory::getApplication();

$config = YendifVideoShareUtils::getConfig();

$cols   = (int) $params->get('no_of_cols', $config->no_of_cols);
$rows   = (int) $params->get('no_of_rows', $config->no_of_rows);
$itemId = $params->get('itemid', 0);
$column = 0;

$show_excerpt = $params->get('show_excerpt', $config->show_excerpt);
$excerpt_length = $config->playlist_desc_limit;

$items = YendifVideoShareCategoriesHelper::getItems( $params, $rows * $cols );
$limit = count( $items );

$show_videos_count = $params->get('show_media_count', $config->show_media_count);
if( $show_videos_count == 'global' ) {			
	$show_videos_count = $config->show_media_count;			
}

$document = JFactory::getDocument();

if( $config->bootstrap_version == 3 ) {
	$document->addStyleSheet( YendifVideoShareUtils::prepareURL('media/yendifvideoshare/assets/site/css/bootstrap.css', 'text/css', 'screen' ));
}

$document->addStyleSheet( YendifVideoShareUtils::prepareURL('media/yendifvideoshare/assets/site/css/yendifvideoshare.css', 'text/css', 'screen') );

$moduleclass_sfx = htmlspecialchars( $params->get('moduleclass_sfx') );

require( JModuleHelper::getLayoutPath('mod_yendifvideoshare_categories') );