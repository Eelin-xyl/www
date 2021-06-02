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

use Joomla\CMS\Component\ComponentHelper;

class EasyBlogActionLog extends EasyBlog
{
	private $defaultData = array(
		'action' => '',
		'title' => 'com_easyblog',
		'extension_name' => 'com_easyblog'
	);

	/**
	 * Determines if actionlog feature is enabled or not from the 'Events To Log' option
	 *
	 * @since	5.4
	 * @access	public
	 */
	public function isEnabled()
	{
		$params = ComponentHelper::getComponent('com_actionlogs')->getParams();

		$extensions = $params->get('loggable_extensions', array());

		if (in_array('com_easyblog', $extensions)) {
			return true;
		}

		return false;
	}

	/**
	 * Determines if actionlog feature already exist in current Joomla version.
	 * Because this actionlog feature only available in Joomla 3.9
	 *
	 * @since	5.3.3
	 * @access	public
	 */
	public function exists()
	{
		static $loaded = null;

		$file = JPATH_ADMINISTRATOR . '/components/com_actionlogs/models/actionlog.php';

		if (EB::isJoomla4()) {
			$file = JPATH_ADMINISTRATOR . '/components/com_actionlogs/src/Model/ActionlogModel.php';
		}

		if (is_null($loaded)) {
			jimport('joomla.filesystem.file');

			$exists = JFile::exists($file);
			$loaded = $exists;
		}

		return $loaded;
	}

	public function log($actionString, $context, $data = array())
	{
		// Skip this if the actionlog feature not exist in current Joomla version
		if (!$this->exists()) {
			return;
		}

		if (!$this->isEnabled()) {
			return;
		}

		$user = isset($data['user']) && is_object($user) ? $user : $this->my;
		
		$data = array_merge($data, $this->defaultData);
		
		$data['userid'] = $user->id;
		$data['username'] = $user->username;
		$data['accountlink'] = "index.php?option=com_users&task=user.edit&id=" . $user->id;
		
		$context = $data['extension_name'] . '.' . $context;

		$model = $this->getModel();

		// Could be disabled
		if ($model === false) {
			return false;
		}
		
		$model->addLog(array($data), JText::_($actionString), $context, $user->id);
	}

	/**
	 * Retrieve joomla's ActionLog model
	 *
	 * @since	5.3.0
	 * @access	public
	 */
	public function getModel()
	{
		$config = array('ignore_request' => true);

		if (EB::isJoomla4()) {
			$model = new Joomla\Component\Actionlogs\Administrator\Model\ActionlogModel($config);

			return $model;
		}

		\Joomla\CMS\MVC\Model\ItemModel::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_actionlogs/models', 'ActionlogsModelActionlog');
		$model = \Joomla\CMS\MVC\Model\ItemModel::getInstance('Actionlog', 'ActionLogsModel', $config);

		return $model;
	}
}
