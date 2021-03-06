<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

jimport('joomla.filesystem.file');

$file = JPATH_ADMINISTRATOR . '/components/com_easyblog/includes/easyblog.php';

if (!JFile::exists($file)) {
	return;
}

require_once($file);
require_once(__DIR__ . '/helper.php');

// Load up our library
$modules = EB::modules($module, false);
$helper = new modEasyBlogTagCloudHelper($modules);
$layout = $params->get('layout', 'default');

// Get the list of tags
$tags = $helper->getTagCloud();

require($modules->getLayout());
