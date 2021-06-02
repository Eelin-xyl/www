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

// Access check: is this user allowed to access the backend of this component?
if( ! JFactory::getUser()->authorise( 'core.manage', 'com_yendifvideoshare' ) ) {
	return $app->enqueueMessage( JText::_('JERROR_ALERTNOAUTHOR'), 'warning' );
}

// Define constants for all pages
define( 'YENDIF_VIDEO_SHARE_UPLOAD_DIR', DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'yendifvideoshare'.DIRECTORY_SEPARATOR );
define( 'YENDIF_VIDEO_SHARE_UPLOAD_BASE', JPATH_ROOT.YENDIF_VIDEO_SHARE_UPLOAD_DIR );
define( 'YENDIF_VIDEO_SHARE_UPLOAD_BASEURL', JURI::root( true ) . str_replace( DIRECTORY_SEPARATOR, '/', YENDIF_VIDEO_SHARE_UPLOAD_DIR ) );
define( 'YENDIF_VIDEO_SHARE_USERID', JFactory::getUser()->get( 'id' ) );

// Register Libraries
JLoader::register( 'YendifVideoShareController', JPATH_COMPONENT.'/controllers/controller.php' );
JLoader::register( 'YendifVideoShareModel', JPATH_COMPONENT.'/models/model.php' );
JLoader::register( 'YendifVideoShareView', JPATH_COMPONENT.'/views/view.php' );
JLoader::register( 'YendifVideoShareFields', JPATH_COMPONENT.'/libraries/fields.php' );
JLoader::register( 'YendifVideoShareUpload', JPATH_COMPONENT.'/libraries/upload.php' );
JLoader::register( 'YendifVideoShareUtils', JPATH_COMPONENT.'/libraries/utils.php' );

// CSS
$document = JFactory::getDocument();
$document->addStyleSheet( YendifVideoShareUtils::prepareURL( 'media/yendifvideoshare/assets/admin/css/yendifvideoshare.css', 'text/css', 'screen' ));

// Require the base controller
$view = $app->input->get( 'view', 'dashboard' );

if( $view !== 'upload' ) : ?>
  <div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>NOTE!</strong> You're using the Yendif Video Share - FREE version. Upgrade to our <a href="https://yendifplayer.com/joomla-video-share/pricing.html" target="_blank">PRO Version</a> and get access to all our pro features, receive updates & get lifetime support.
  </div>
<?php endif; 

$controller = JString::strtolower( $view );
require_once JPATH_COMPONENT.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.$controller.'.php';

// Initialize the controller
$obj = 'YendifVideoShareController'.$controller;
$controller = new $obj();

// Perform the Request task
$task = $app->input->get( 'task', $view );
$controller->execute( $task );
$controller->redirect();