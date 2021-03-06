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

class EasyDiscussToolbar
{
	public function __construct()
	{
		$this->config = ED::config();
		$this->acl = ED::acl();
		$this->input = ED::request();
		$this->my = JFactory::getUser();
	}

	public function render($options = array())
	{
		$app = JFactory::getApplication();

		// Get a list of available views
		$views = JFolder::folders(JPATH_COMPONENT . '/views');		

		// Get the active view name
		$active = $this->input->get('view', '', 'cmd');

		// If the current active view doesn't exist on our known views, set the latest to be active by default.
		if (!in_array($active, $views)) {
			$active = 'index';
		}

		$showToolbar = isset($options['showToolbar']) ? $options['showToolbar'] : $this->config->get('layout_enabletoolbar');
		$showSearch = isset($options['showSearch']) ? $options['showSearch'] : $this->config->get('layout_toolbar_searchbar');
		$showHome = isset($options['showHome']) ? $options['showHome'] : $this->config->get('layout_toolbarhome');
		$showTags = isset($options['showTags']) ? $options['showTags'] : $this->config->get('layout_toolbartags');
		$showCategories = isset($options['showCategories']) ? $options['showCategories'] : $this->config->get('layout_toolbarcategories');
		$showUsers = isset($options['showUsers']) ? $options['showUsers'] : $this->showUserMenu();
		$showBadges = isset($options['showBadges']) ? $options['showBadges'] : $this->config->get('layout_toolbarbadges') && $this->config->get('main_badges');
		$showSettings = isset($options['showSettings']) ? $options['showSettings'] : $this->config->get('layout_toolbarprofile');
		$showLogin = isset($options['showLogin']) ? $options['showLogin'] : $this->config->get('layout_toolbarlogin');
		$showConversation = isset($options['showConversation']) ? $options['showConversation'] : $this->config->get('layout_toolbar_conversation');
		$showNotification = isset($options['showNotification']) ? $options['showNotification'] : ($this->config->get('layout_toolbar_notification') && $this->config->get('main_notifications'));
		$processLogic = isset($options['processLogic']) ? $options['processLogic'] : true;
		$renderToolbarModule = isset($options['renderToolbarModule']) ? $options['renderToolbarModule'] : true;

		$query = '';

		if ($showSearch) {
			// Search queries
			$query = $this->input->get('query', '', 'string');
		}

		// If a user is viewing a specific category, we need to ensure that it's setting the correct active menu
		$activeCategory = $this->input->get('category_id', 0, 'int');

		if ($activeCategory !== 0) {
			$active = 'categories';
		}

		// Rebuild the views
		$tmp = new stdClass();

		foreach ($views as $key) {
			$tmp->$key = false;
		}

		// Reset back the views to the tmp variable
		$views = $tmp;

		// Set the active menu
		if (isset($views->$active)) {
			$views->$active = true;
		}

		if ($processLogic) {

			if ($active == 'profile') {

				$id = $this->input->get('id', 0, 'int');

				if ($this->my->id == $id || $id === 0) {
					$views->$active = true;
				} else {
					$views->index = true;
				}
			}

			// When the current viewer is reading a discussion item.
			if ($active == 'post') {
				$postId = $this->input->get('id', 0, 'int');

				if ($postId) {
					$postModel = ED::model('Posts');
					$categoryId = $postModel->getCategoryId($postId);

					// Mark as read
					ED::notifications()->clear($postId);
				}
			}

			// When the current viewer is checking on pending post
			if ($active == 'ask') {
				$postId = $this->input->get('id', 0, 'int');
				// Mark as read
				ED::notifications()->clear($postId);
			}
		}

		$notificationsCount = 0;

		// Get total notifications for the current viewer
		if ($showNotification) {
			$model = ED::model('Notification');
			$notificationsCount = $model->getTotalNotifications($this->my->id);
		}

		$conversationsCount = 0;

		// Get total conversation for the current viewer.
		if ($showConversation) {
			$model = ED::model('conversation');
			$conversationsCount = $model->getCount($this->my->id, array('filter' => 'unread'));
		}

		$postCatId = 0;

		$id = $this->input->get('id', 0, 'int');
		$post = ED::post($id);

		if ($id) {
			$postCatId = $post->category_id;
		}

		$clusterId = '';

		// Retrieve the mini header for easysocial group.
		if ($active == 'post') {
			$postId = $this->input->get('id');
			$post = ED::post($postId);

			$clusterId = $post->cluster_id;
		}

		if ($active == 'ask' || $active == 'groups') {
			$clusterId = $this->input->get('group_id', '', 'int');
		}

		// Get all the categories ids
		$sortConfig = $this->config->get('layout_ordering_category','latest');

		// Set the default return url
		$return = EDR::getLoginRedirect();

		// Get any callback url and use it.
		$url = ED::getCallback('', false);

		if ($url) {
			$return = base64_encode($url);
		}

		// Determines if we should use external conversations
		$useExternalConversations = false;

		if (ED::easysocial()->exists() && $this->config->get('integration_easysocial_messaging')) {
			$useExternalConversations = true;
		}

		if (ED::jomsocial()->exists() && $this->config->get('integration_jomsocial_messaging')) {
			$useExternalConversations = true;
		}

		$usernameField = 'COM_EASYDISCUSS_TOOLBAR_USERNAME';

		if (ED::easysocial()->exists() && $this->config->get('main_login_provider') == 'easysocial') {
			$usernameField = ED::easysocial()->getUsernameField();
		}

		// If tags has been explicitly disabled, it shouldn't show up on the toolbar at all
		if (!$this->config->get('main_master_tags')) {
			$showTags = false;
		}

		// determine which user menu link should show
		$userMenuLink = $this->showUserMenuLink();
		$showManageSubscription = true;

		// determine to show manage subscription link
		if (!$this->config->get('main_sitesubscription') && !$this->config->get('main_ed_categorysubscription') && !$this->config->get('main_postsubscription')) {
			$showManageSubscription = false;
		}

		$showNavigationMenu = true;

		// determine whether need to show the mobile toolbar toggle menu icon
		if (!$this->my->id && (ED::responsive()->isMobile() || ED::responsive()->isTablet()) && (!$showCategories && !$showTags && !$showUsers && !$showBadges && !$group)) {
			$showNavigationMenu = false;
		}

		// Determines if user is already subsribed
		$subscribeModel = ED::model('Subscribe');
		$isSubscribed = $subscribeModel->isSiteSubscribed('site', $this->my->email, 0);

		$theme = ED::themes();
		$theme->set('isSubscribed', $isSubscribed);
		$theme->set('active', $active);
		$theme->set('conversationsCount', $conversationsCount);
		$theme->set('notificationsCount', $notificationsCount);
		$theme->set('return', $return );
		$theme->set('useExternalConversations', $useExternalConversations);
		$theme->set('categoryId', $activeCategory);
		$theme->set('views', $views);
		$theme->set('query', $query);
		$theme->set('post', $post);
		$theme->set('usernameField', $usernameField);
		
		// settings
		$theme->set('showToolbar', $showToolbar);
		$theme->set('showSearch', $showSearch);
		$theme->set('showHome', $showHome);
		$theme->set('showTags', $showTags);
		$theme->set('showCategories', $showCategories);
		$theme->set('showUsers', $showUsers);
		$theme->set('showBadges', $showBadges);
		$theme->set('showSettings', $showSettings);
		$theme->set('showLogin', $showLogin);
		$theme->set('showConversation', $showConversation);
		$theme->set('showNotification', $showNotification);
		$theme->set('renderToolbarModule', $renderToolbarModule);
		$theme->set('userMenuLink', $userMenuLink);
		$theme->set('showManageSubscription', $showManageSubscription);
		$theme->set('showNavigationMenu', $showNavigationMenu);

		return $theme->output('site/toolbar/default');
	}

	/**
	 * Determine to show user listing menu
	 *
	 * @since	4.1.7
	 * @access	public
	 */
	public function showUserMenu()
	{
		$hasEnabledMainUserListing = $this->config->get('main_user_listings');
		$hasEnabledToolbarUser = $this->config->get('layout_toolbarusers');
		$hasEnabledAccessProflePublic = $this->config->get('main_profile_public');

		// Do not render this if main user listing setting disabled
		if (!$hasEnabledMainUserListing) {
			return false;
		}

		// Do not render this if public user unable to access user profile page
		if (!$this->my->id && !$hasEnabledAccessProflePublic) {
			return false;
		}

		// Do not render this if the toolbar show user menu is disabled
		if (!$hasEnabledToolbarUser) {
			return false;
		}

		return true;
	}

	/**
	 * Determine to show user listing link
	 *
	 * @since	4.1.7
	 * @access	public
	 */
	public function showUserMenuLink()
	{
		$permalink = EDR::_('view=users');

		$hasES = ED::easysocial()->exists();
		$hasEnabledESMembers = $this->config->get('integration_easysocial_members');

		// Check for the Easysocial integration
		if ($hasES && $hasEnabledESMembers) {
			$permalink = ESR::users();
		}

		return $permalink;
	}	
}
