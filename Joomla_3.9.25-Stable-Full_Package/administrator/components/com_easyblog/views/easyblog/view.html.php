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

class EasyBlogViewEasyblog extends EasyBlogAdminView
{
	public function display($tpl = null)
	{
		$this->setHeading('COM_EASYBLOG_DASHBOARD');
		$this->checkAccess('core.manage');

		// Add toolbar buttons
		if ($this->my->authorise('easyblog.manage.blog', 'com_easyblog')) {
			JToolBarHelper::addNew('new', JText::_('COM_EASYBLOG_COMPOSE_NEW_POST'));
		}

		if ($this->my->authorise('core.admin', 'com_easyblog')) {
			JToolBarHelper::preferences('com_easyblog');
		}

		// Get the model
		$model = EB::model('Stats');

		// Get the total number of posts
		$totalPosts = $model->getTotalPosts();
		$totalPending = $model->getTotalPending();
		$totalComments = $model->getTotalComments();
		$totalCategories = $model->getTotalCategories();
		$totalAuthors = $model->getTotalAuthors();
		$totalTags = $model->getTotalTags();
		$totalTeams = $model->getTotalTeams();
		$totalFeeds = $model->getTotalFeeds();
		$totalReactions = $model->getTotalReactions();

		// Get comments history
		$commentsHistory = $model->getCommentsHistory();

		// Format the tickets for comments
		$commentsTicks = array();
		$commentsCreated = array();
		$i = 0;

		foreach ($commentsHistory->dates as $dateString) {

			// Normalize the date string first
			$dateString = str_ireplace('/', '-', $dateString);
			$date = EB::date($dateString);

			$commentsTicks[] = array($i, $date->format('jS M'));
			$commentsCreated[] = array($i, $commentsHistory->count[$i]);
			$i++;
		}

		// Get posts history
		$postsHistory = $model->getPostsHistory();

		// Format the ticks for the posts
		$postsTicks = array();
		$postsCreated = array();
		$i = 0;

		foreach ($postsHistory->dates as $dateString) {
			// Normalize the date string first
			$dateString = str_ireplace('/', '-', $dateString);
			$date = EB::date($dateString);

			$postsTicks[] = array($i, $date->format('jS M'));
			$postsCreated[]	= array($i, $postsHistory->count[$i]);
			$i++;
		}

		// Get reactions history
		$reactionsHistory = $model->getReactionsHistory();

		// Format the ticks for reactions
		$reactionTicks = array();
		$reactionCreated = array();
		$i = 0;

		foreach ($reactionsHistory->dates as $dateString) {
			// Normalize the date string first
			$dateString = str_ireplace('/', '-', $dateString);
			$date = EB::date($dateString);

			$reactionTicks[] = array($i, $date->format('jS M'));
			$reactionCreated[] = array($i, $reactionsHistory->count[$i]);
			$i++;
		}

		$reactionCreated = json_encode($reactionCreated);
		$reactionTicks = json_encode($reactionTicks);
		$postsCreated = json_encode($postsCreated);
		$postsTicks = json_encode($postsTicks);
		$commentsCreated = json_encode($commentsCreated);
		$commentsTicks = json_encode($commentsTicks);


		$reactions = $model->getRecentReactions();
		$comments = $model->getRecentComments();
		$posts = $model->getRecentPosts();
		$pending = $model->getPendingPosts();

		$this->set('reactions', $reactions);
		$this->set('reactionTicks', $reactionTicks);
		$this->set('reactionCreated', $reactionCreated);
		$this->set('comments', $comments);
		$this->set('commentsTicks', $commentsTicks);
		$this->set('commentsCreated', $commentsCreated);
		$this->set('pending', $pending);
		$this->set('posts', $posts);
		$this->set('totalReactions', $totalReactions);
		$this->set('totalFeeds', $totalFeeds);
		$this->set('totalPending', $totalPending);
		$this->set('totalTeams', $totalTeams);
		$this->set('totalTags', $totalTags);
		$this->set('postsCreated', $postsCreated);
		$this->set('postsTicks', $postsTicks);
		$this->set('postsHistory', $postsHistory);
		$this->set('totalAuthors', $totalAuthors);
		$this->set('totalCategories', $totalCategories);
		$this->set('totalComments', $totalComments);
		$this->set('totalPosts', $totalPosts);

		parent::display('easyblog/default');
	}
}
