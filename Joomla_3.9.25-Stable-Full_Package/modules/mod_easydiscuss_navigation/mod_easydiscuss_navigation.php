<?php
/**
* @package		EasyDiscuss
* @copyright	Copyright (C) Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyDiscuss is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

$path = JPATH_ADMINISTRATOR . '/components/com_easydiscuss/includes/easydiscuss.php';

jimport('joomla.filesystem.file');

if (!JFile::exists($path)) {
	return;
}

require_once($path);

ED::init();
$lib = ED::modules($module);

// Load component's language file.
JFactory::getLanguage()->load('com_easydiscuss', JPATH_ROOT);

$my = ED::user();
$config = ED::config();
$input = ED::request();

// We need to detect if the user is browsing a particular category
$active = '';
$view = $input->get('view', '', 'default');
$layout = $input->get('layout', '', 'default');
$option = $input->get('option', '', 'default');
$id = $input->get('category_id', 0, 'int');

$model = ED::model('Categories');
$data = $model->getCategoryTree();
	
$categories = [];
// Formats the categories.
foreach ($data as $item) {
	$postCount = $item->getTotalPosts();
	$totalNew = ($my->id > 0) ? $item->getUnreadCount() : '0';

	if (!$params->get('display_empty_category') && !$postCount) {
		continue;
	}

	if (!$params->get('display_empty_category') && $totalNew) {
		continue;
	}

	$item->totalNew = $totalNew;
	$categories[] = $item;
}

$notificationsCount = 0;

if ($my->id) {
    $notificationModel = ED::model('Notification');
    $notificationsCount = $notificationModel->getTotalNotifications($my->id);
}

if ($option == 'com_easydiscuss' && $view == 'forums' && $layout == 'listings' && $id) {
	$active	= $id;
}



require(JModuleHelper::getLayoutPath('mod_easydiscuss_navigation'));
