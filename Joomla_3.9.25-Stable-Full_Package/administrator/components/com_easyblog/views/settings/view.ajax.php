<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 - 2019 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

require_once(JPATH_ADMINISTRATOR . '/components/com_easyblog/views.php');
require_once(JPATH_ROOT . '/administrator/components/com_easyblog/includes/vendor/autoload.php');

use Nahid\JsonQ\Jsonq;

class EasyBlogViewSettings extends EasyBlogAdminView
{
	/**
	 * Display confirmation box to remove email logo
	 *
	 * @since	5.1
	 * @access	public
	 */
	public function confirmRestorelogos()
	{
		$theme = EB::template();
		$output = $theme->output('admin/settings/dialog.restore.logo');

		return $this->ajax->resolve($output);
	}

	/**
	 * Brings up the import dialog form
	 *
	 * @since	4.0
	 * @access	public
	 */
	public function import()
	{
		$template = EB::template();

		$output = $template->output('admin/settings/dialog.import');

		return $this->ajax->resolve($output);
	}

	/**
	 * Rebuilds the search for settings
	 *
	 * @since	5.4.0
	 * @access	public
	 */
	public function rebuildSearch()
	{
		$str = $this->input->get('dataString', '', 'raw');

		$jsonObject = json_decode($str);


		foreach ($jsonObject->items as &$item) {

			$item->keywords = array();
			$item->keywords = EB::extractKeyWords($item->label);

			if (isset($item->description)) {
				$item->keywords = array_merge($item->keywords, EB::extractKeyWords($item->description));
			}

			if ($item->keywords) {
				$item->keywords = implode(' ', $item->keywords);
			}
		}

		$jsonString = json_encode($jsonObject);
		$cacheFile = EBLOG_DEFAULTS . '/cache.json';

		JFile::write($cacheFile, $jsonString);

		$this->info->set('Cache file updated successfully');

		return $this->ajax->resolve();
	}

	/**
	 * Searches for a settings
	 *
	 * @since	5.4.0
	 * @access	public
	 */
	public function search()
	{
		$query = $this->input->get('text', '', 'word');
		$query = EBString::strtolower($query);

		$jsonString = file_get_contents(EBLOG_DEFAULTS . '/cache.json');
		$jsonString = EBString::strtolower($jsonString);

		$jsonq = new Jsonq();
		$jsonq->json($jsonString);

		$result = @$jsonq->from('items')
				->where('keywords', 'contains', $query)
				->groupBy('page')
				->get();

		$theme = EB::themes();
		$theme->set('result', $result);
		$contents = $theme->output('admin/settings/search.result');

		return $this->ajax->resolve($contents);
	}

	/**
	 * Runs mailbox testing
	 *
	 * @since	4.0
	 * @access	public
	 */
	public function testMailbox()
	{
		$server = $this->input->get('server', '', 'default');
		$port = $this->input->get('port', '', 'default');
		$service = $this->input->get('service', '', 'default');
		$ssl = $this->input->get('ssl', true, 'bool');
		$mailbox = $this->input->get('mailbox', 'INBOX', 'default');
		$user = $this->input->get('user', '', 'default');
		$pass = $this->input->get('pass', '', 'default');

		// Ensure that all properties are set
		if (empty($server)) {
			return $this->ajax->reject(JText::_('Please enter the server address for your mail server.'));
		}

		if (empty($port)) {
			return $this->ajax->reject(JText::_('Please enter the server port for your mail server.'));
		}

		if (empty($user)) {
			return $this->ajax->reject(JText::_('Please enter your mailbox username.'));
		}

		if (empty($pass)) {
			return $this->ajax->reject(JText::_('Please enter your mailbox password.'));
		}

		$mailbox = EB::mailbox();
		$result = $mailbox->test($server, $port, $service, $ssl, $mailbox, $user, $pass);

		return $this->ajax->resolve($result);
	}
}
