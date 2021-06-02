<?php
/**
* @package      EasyBlog
* @copyright    Copyright (C) 2010 - 2019 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

require_once(JPATH_COMPONENT . '/views/views.php');

class EasyBlogViewAuth extends EasyBlogView
{
	public function display($tpl = null)
	{
		// Get type
		$type = $this->input->get('type', '', 'default');

		$method = $type . 'Authorize';

		return $this->$method();
	}

	/**
	 * Request for the Linkedin another scope
	 *
	 * @since	5.4.0
	 * @access	public
	 */
	public function requestLinkedin($triggerDefaultScope = false)
	{
		$client = EB::oauth()->getClient('LinkedIn');

		// Generate the authorize url
		$url = $client->getAuthorizeURL(null, $triggerDefaultScope);

		return $this->app->redirect($url, false);
	}

	/**
	 * Authorize linkedin oauth
	 *
	 * @since	5.2.5
	 * @access	public
	 */
	public function linkedinAuthorize()
	{
		$code = $this->input->get('code', '', 'default');
		$system = $this->input->get('system', false, 'bool');
		$state = $this->input->get('state', '', 'default');
		$errorCode = $this->input->get('error', '', 'default');

		if ($errorCode == 'unauthorized_scope_error') {
			return $this->requestLinkedin(true);
		}

		// Stored the generated token code
		if ($code) {

			$client = EB::oauth()->getClient('LinkedIn');

			// Set the authorization code
			$client->setAuthCode($code);

			// Get the access token
			$result = $client->getAccess();

			$table = EB::table('OAuth');

			$userId = $client->getUserIdFromState($state);

			if (!$userId) {
				$userId = $this->my->id;
			}

			if ($system) {
				$table->load(array('type' => 'linkedin', 'system' => 1));

				if (!$table->id) {
					$table->type = 'linkedin';
					$table->user_id = $userId;
					$table->system = true;
				}
			} else {
				$table->load(array('type' => 'linkedin', 'user_id' => $userId, 'system' => false));

				if (!$table->id) {
					$table->type = 'linkedin';
					$table->user_id = $userId;
					$table->system = false;
				}
			}

			if ($result) {
				$accessToken = new stdClass();
				$accessToken->token  = $result->token;
				$accessToken->secret = $result->secret;

				// Set the access token now
				$table->access_token = json_encode($accessToken);

				// Set the params
				$table->params = json_encode($result);
				$table->expires = $result->expires;

				$state = $table->store();

				if ($state) {
					// now everything is set. lets migrate the data in oauth_posts with this new oauth record.
					$table->restoreBackup();
				}

			}

			// Since the page that is redirected to here is a popup, we need to close the window
			EB::info()->set(JText::_('COM_EASYBLOG_AUTOPOSTING_LINKEDIN_AUTHORIZED_SUCCESS'), 'success');
			echo '<script type="text/javascript">window.opener.doneLogin();window.close();</script>';
		} else {
			EB::info()->set(JText::sprintf('COM_EB_AUTOPOSTING_LINKEDIN_AUTHORIZED_FAILED', $errorCode), 'success');
			echo '<script type="text/javascript">window.opener.doneLogin();window.close();</script>';
		}
	}
}