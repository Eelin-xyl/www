<?php

/*
 * @version		$Id: yendifvideoshare.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

$app = JFactory::getApplication();

// Get [or] Set the view
$id = $app->input->getInt('id', 0);
if( $app->input->get('view') == 'category' && $id == 0 ) {
	$app->input->set('view', 'categories');
} else if( $app->input->get('view') == 'video' && $id == 0 ) {
	$app->input->set('view', 'videos');
}
$view = $app->input->get('view', 'categories');

if ( 'player' == $view ) {
	$app->input->set( 'tmpl', 'component' );
}

$user = JFactory::getUser();
if( $view == 'user' ) {
	$userid = $user->get('id');
	if( ! $userid ) {		
		$app = JFactory::getApplication();
		$uri = JFactory::getURI();
		$loginURL = 'index.php?option=com_users&view=login&Itemid='.$app->input->getInt('Itemid').'&return='.base64_encode( $uri->toString() );
		$app->redirect( $loginURL, JText::_('YENDIF_VIDEO_SHARE_PLEASE_LOGIN') );	
		return;
	}
}

// Define constants for all pages
define( 'YENDIF_VIDEO_SHARE_UPLOAD_DIR', DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'yendifvideoshare'.DIRECTORY_SEPARATOR );
define( 'YENDIF_VIDEO_SHARE_UPLOAD_BASE', JPATH_ROOT.YENDIF_VIDEO_SHARE_UPLOAD_DIR );
define( 'YENDIF_VIDEO_SHARE_UPLOAD_BASEURL', JURI::root( true ) . str_replace( DIRECTORY_SEPARATOR, '/', YENDIF_VIDEO_SHARE_UPLOAD_DIR ) );
define( 'YENDIF_VIDEO_SHARE_USERID', $user->get('id') );
define( 'YENDIFVIDEOSHARE_VERSION', '1.2.8' );

// Register Libraries
JLoader::register('YendifVideoShareController', JPATH_COMPONENT_ADMINISTRATOR.'/controllers/controller.php');
JLoader::register('YendifVideoShareModel', JPATH_COMPONENT_ADMINISTRATOR.'/models/model.php');
JLoader::register('YendifVideoShareView', JPATH_COMPONENT_ADMINISTRATOR.'/views/view.php');
JLoader::register('YendifVideoShareFields', JPATH_COMPONENT_ADMINISTRATOR.'/libraries/fields.php');
JLoader::register('YendifVideoShareUpload', JPATH_COMPONENT_ADMINISTRATOR.'/libraries/upload.php');
JLoader::register('YendifVideoShareUtils', JPATH_COMPONENT_ADMINISTRATOR.'/libraries/utils.php');

// Require the base controller
$controller = JString::strtolower( $view );
require_once JPATH_COMPONENT.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.$controller.'.php';

// Initialize the controller
$classname  = 'YendifVideoShareController'.$controller;
$controller = new $classname();

// Perform the Request task
if( $layout = $app->input->get('layout') ) {
	$app->input->set('task', $layout);
}

$controller->execute( $app->input->get('task', $view) );
$controller->redirect();