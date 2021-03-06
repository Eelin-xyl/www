<?php
/**
* @package		EasyDiscuss
* @copyright	Copyright (C) 2010 - 2015 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyDiscuss is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

require_once DISCUSS_ADMIN_ROOT . '/views/views.php';

class EasyDiscussViewTelegram extends EasyDiscussAdminView
{
	/**
	 * Scans a bot's updates
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function discover()
	{
		$telegram = ED::telegram();
		$messages = $telegram->discover();

		$theme = ED::themes();
		$theme->set('messages', $messages);

		$output = $theme->output('admin/telegram/messages');

		return $this->ajax->resolve($output);
	}

	public function test()
	{
		$telegram = ED::telegram();

		$data = array();
		$data['chat_id'] = $this->config->get('integrations_telegram_chat_id');
		$data['text'] = urlencode("This is a test message for EasyDiscuss integration connection. If you're receiving this message, means your connection is a successful.");
		$data['parse_mode'] = 'markdown';

		$response = $telegram->ping('sendMessage', $data);
		
		if (!$response) {
			return $this->ajax->resolve('<div class="t-mt--sm alert alert-error">Auch! Something went wrong. Kindly consult with our support team for more information.</div>');
		}

		return $this->ajax->resolve('<div class="t-mt--sm alert alert-success">Connection successful. Check your Telegram chat.</div>');
	}
}
