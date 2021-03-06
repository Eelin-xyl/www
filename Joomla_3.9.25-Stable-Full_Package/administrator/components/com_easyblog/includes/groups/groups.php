<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 - 2017 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

class EasyBlogGroups
{
	public function useGroups()
	{
		return $this->isEnabled();
	}

	public function isEnabled()
	{
		// Since we only support jomsocial now, load up their form
		if (JPluginHelper::isEnabled('system', 'groupeasyblog') && $this->testExists('jomsocial')) {
			return true;
		}

		return false;
	}

	public function testExists( $source )
	{
		switch( $source )
		{
			case 'jomsocial':
				return JFile::exists( JPATH_ROOT . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_community' . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'core.php' );
			break;
		}
	}

	public function getGroupSourceType()
	{
		$source = 'jomsocial';

		return $source;
	}

	public function addCommentStream( $blog , $comment , $external )
	{
		return $this->addCommentStreamJomsocial( $blog , $comment , $external );
	}

	/**
	 * Creates a stream item for the respective 3rd party plugin
	 *
	 * @param	TableBlog $blog
	 */
	public function addStream( $blog , $isNew , $key , $source )
	{
		// Since we only support jomsocial now, load up their form
		return $this->addStreamJomsocial( $blog , $isNew , $key , $source );
	}

	/**
	 * Sends a notification item for the respective 3rd party plugins
	 */
	public function sendNotifications( $blog , $isNew , $key , $source , $author )
	{
		return $this->sendNotificationsJomsocial( $blog , $isNew , $key , $source , $author );
	}

	private function sendNotificationsJomsocial( $blog , $isNew , $key , $source , $author )
	{
		JFactory::getLanguage()->load( 'com_easyblog' , JPATH_ROOT );

		// @rule: Send email notifications out to subscribers.
		$author 			= EB::user($blog->created_by);

		$data[ 'blogTitle']				= $blog->title;
		$data[ 'blogAuthor']			= $author->getName();
		$data[ 'blogAuthorAvatar' ]		= $author->getAvatar();
		$data[ 'blogAuthorLink' ]		= EBR::getRoutedURL( 'index.php?option=com_easyblog&view=blogger&layout=listings&id=' . $author->id , false , true );
		$data[ 'blogAuthorEmail' ]		= $author->user->email;
		$data[ 'blogIntro' ]			= $blog->intro;
		$data[ 'blogContent' ]			= $blog->content;
		$data[ 'blogLink' ]				= EBR::getRoutedURL( 'index.php?option=com_easyblog&view=entry&id='. $blog->id, false, true);

		$date							= EasyBlogDateHelper::dateWithOffSet( $blog->created );
		$data[ 'blogDate' ]				= EasyBlogDateHelper::toFormat( $date , JText::_('DATE_FORMAT_LC1') );

		// If blog post is being posted from the back end and SH404 is installed, we should just use the raw urls.
		$sh404exists	= EBR::isSh404Enabled();

		if( EB::isFromAdmin() && $sh404exists )
		{
			$data[ 'blogLink' ]			= JURI::root() . 'index.php?option=com_easyblog&view=entry&id=' . $blog->id;
			$data[ 'blogAuthorLink' ]	= JURI::root() . 'index.php?option=com_easyblog&view=blogger&layout=listings&id=' . $author->id;
		}

		// Get group members emails
		if( !class_exists( 'CommunityModelGroups' ) )
		{
			jimport( 'joomla.application.component.model' );
			JLoader::import( 'groups' , JPATH_ROOT . DIRECTORY_SEPARATOR . 'com_community' . DIRECTORY_SEPARATOR . 'models' );
		}

		$model		= JModelLegacy::getInstance( 'Groups' , 'CommunityModel' );

		if( is_array($key) )
		{
			$key	= $key[0];
		}

		if( !method_exists( $model , 'getAllMember' ) )
		{
			// Snippet taken from getAllMember
			$db		= EB::db();
			$query	= 'SELECT a.'.$db->nameQuote('memberid').' AS id, a.'.$db->nameQuote('approved').' , b.'.$db->nameQuote('name').' as name , a.'. $db->nameQuote('permissions') .' as permission FROM '
					. $db->nameQuote('#__community_groups_members') . ' AS a '
					. ' INNER JOIN ' . $db->nameQuote('#__users') . ' AS b '
					. ' WHERE b.'.$db->nameQuote('id').'=a.'.$db->nameQuote('memberid')
					. ' AND a.'.$db->nameQuote('groupid').'=' . $db->Quote( $key )
					. ' AND b.'.$db->nameQuote('block').'=' . $db->Quote( '0' ) . ' '
					. ' AND a.'.$db->nameQuote('permissions').' !=' . $db->quote( -1 );
			$db->setQuery( $query );

			$members	= $db->loadObjectList();
		}
		else
		{
			$members	= $model->getAllMember( $key );
		}

		$emails		= array();

		$jsCoreFile	= JPATH_ROOT . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_community' . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'core.php';
		foreach($members as $member) {
			$user = CFactory::getUser($member->id);

			$userParam = $user->getParams();
			$enabled = $userParam->get( 'etype_groups_sendmail', 0 );
			if (! $enabled) {
				continue;
			}

			if ($author->user->email != $user->email) {
				$obj 				= new stdClass();
				$obj->email 		= $user->email;
				$emails[]			= $obj;
			}
		}

		$config = EB::config();
		$notification = EB::notification();
		$emailBlogTitle = EBString::substr( $blog->title , 0 , $config->get( 'main_mailtitle_length' ) );
		$emailTitle 	= JText::sprintf( 'COM_EASYBLOG_EMAIL_TITLE_NEW_BLOG_ADDED_WITH_TITLE' ,  $emailBlogTitle ) . ' ...';
		$notification->send( $emails , $emailTitle , 'email.blog.new' , $data );
	}

	private function addCommentStreamJomsocial($blog, $comment, $external)
	{
		$jsCoreFile	= JPATH_ROOT . '/components/com_community/libraries/core.php';
		$config	= EB::config();

		if (!JFile::exists($jsCoreFile)) {
			return false;
		}

		// We do not want to add activities if new blog activity is disabled.
		if (!$config->get('integrations_jomsocial_comment_new_activity')) {
			return false;
		}

		require_once($jsCoreFile);

		JFactory::getLanguage()->load('com_easyblog', JPATH_ROOT);

		JTable::addIncludePath(JPATH_ROOT . '/components/com_community/tables');

		$group = JTable::getInstance('Group', 'CTable');
		$group->load($external->id);

		$command = 'easyblog.comment.add';

		$blogTitle = EBString::substr($blog->title, 0, 30) . '...';
		$blogLink = EBR::getRoutedURL('index.php?option=com_easyblog&view=entry&id='. $comment->post_id, false, true);

		$content = '';

		if ($config->get('integrations_jomsocial_submit_content')) {
			$content = $comment->comment;
			$content = EB::comment()->parseBBCode($content);
			$content = nl2br($content);
			$content = strip_tags($content);
			$content = EBString::substr($content, 0, $config->get('integrations_jomsocial_comments_length'));
		}

		$obj = new stdClass();
		$obj->title = JText::sprintf('COM_EASYBLOG_JS_ACTIVITY_COMMENT_ADDED', $blogLink, $blogTitle);
		$obj->content = ($config->get('integrations_jomsocial_submit_content')) ? $content : '';
		$obj->cmd = $command;
		$obj->actor = $comment->created_by;
		$obj->target = 0;
		$obj->app = 'easyblog';
		$obj->cid = $comment->id;
		$obj->group_access = $group->approvals;
		$obj->groupid = $group->id;

		if ($config->get('integrations_jomsocial_activity_likes')) {
			$obj->like_id = $comment->id;
			$obj->like_type = 'com_easyblog.comments';
		}

		if ($config->get('integrations_jomsocial_activity_comments')) {
			$obj->comment_id = $comment->id;
			$obj->comment_type = 'com_easyblog.comments';
		}

		// add JomSocial activities
		CFactory::load ('libraries', 'activities');
		CActivityStream::add($obj);
	}

	public function getGroupContribution( $postId, $sourcetype = 'jomsocial', $type = 'id')
	{
		$db = EB::db();

		$externalTblName    = '';


		if( $sourcetype == 'jomsocial' )
		{
			$externalTblName    = '#__community_groups';
		}

		$query  = '';

		if( $type == 'name' || $type == 'title' )
		{
			$query  = 'SELECT b.`name` FROM `#__easyblog_post` as a ';
			$query  .= ' INNER JOIN ' . $db->NameQuote( $externalTblName ) . ' as b ON a.`source_id` = b.`id`';
		}
		else
		{
			$query  = 'SELECT a.`source_id` as `group_id` FROM `#__easyblog_post` as a';
		}

		$query  .= ' WHERE a.`source_type` = ' . $db->Quote(EASYBLOG_POST_SOURCE_JOMSOCIAL_GROUP);
		$query  .= ' AND a.`id` = ' . $db->Quote($postId);


		$db->setQuery( $query );
		$result = $db->loadResult();

		return $result;
	}
}
