<?php

/*
 * @version		$Id: mod_yendifvideoshare_search.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Include the syndicate functions only once
require_once( dirname(__FILE__).DIRECTORY_SEPARATOR.'helper.php' );

require_once( JPATH_ROOT.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_yendifvideoshare'.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'utils.php' );

JHTML::_('behavior.formvalidation');
JHtml::_('jquery.framework');

$app = JFactory::getApplication();
$document = JFactory::getDocument();

$config = YendifVideoShareUtils::getConfig();

if( $config->bootstrap_version == 3 ) {
	$document->addStyleSheet( YendifVideoShareUtils::prepareURL('media/yendifvideoshare/assets/site/css/bootstrap.css', 'text/css', 'screen' ));
}
$document->addStyleSheet( YendifVideoShareUtils::prepareURL('media/yendifvideoshare/assets/site/css/yendifvideoshare.css', 'text/css', 'screen') );
if( ! empty ( $config->responsive_css ) ) {
	$document->addStyleDeclaration( $config->responsive_css );
}

$option = $app->input->get('option');
$view   = $app->input->get('view');
$search_key = '';
if( $option == 'com_yendifvideoshare' && $view == 'search' ) {	
	$search_key = $app->getUserStateFromRequest('yendif.search.public', 'search', '', 'string');
} else {
	$app->setUserState('yendif.search.public', '');
}

$itemId = $params->get('itemid', $app->input->getInt('Itemid'));
$moduleclass_sfx = htmlspecialchars( $params->get('moduleclass_sfx') );

require( JModuleHelper::getLayoutPath('mod_yendifvideoshare_search') );