<?php

/*
 * @version		$Id: view.html.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class YendifVideoShareViewRatings extends YendifVideoShareView {

    public function display( $tpl = null ) {
	
		$app = JFactory::getApplication();
			
		$option = $app->input->get('option');
		$view = $app->input->get('view');
		
	    $model = $this->getModel();
		
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = $app->getUserStateFromRequest($option.$view.'.limitstart', 'limitstart', 0, 'int');
		$this->limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		
		$this->items = $model->getItems();
		$this->pagination = $model->getPagination();
		$this->lists = $model->getLists();
		
		JToolBarHelper::title(JText::_('YENDIF_VIDEO_SHARE'), 'yendifvideoshare');
        JToolBarHelper::deleteList(JText::_('YENDIF_VIDEO_SHARE_ARE_YOU_SURE_WANT_TO_DELETE_SELECTED_ITEMS'), 'delete', JText::_('YENDIF_VIDEO_SHARE_DELETE'));
		
		YendifVideoShareUtils::addSubMenu('ratings');
		
        parent::display($tpl);
		
    }
	
	public function add( $tpl = null ) {
	
		$model = $this->getModel();
		
		for( $i = 0.5; $i <= 5; $i += 0.5 ) {
			$rating_options[] = JHTML::_('select.option', $i, number_format($i, 1));
		}			
		$this->ratings = JHTML::_('select.genericlist', $rating_options, 'rating', '', 'value', 'text', 5);
		
		$users = YendifVideoShareUtils::getUsers();
		foreach( $users as $user ) {
			$user_options[] = JHTML::_('select.option', $user->id, $user->username);
		}
		$this->userids = JHTML::_('select.genericlist', $user_options, 'userid', 'class="required validate-list"', 'value', 'text', JFactory::getUser()->get('id'));
		
		$video_options[] = JHTML::_('select.option', -1, JText::_('YENDIF_VIDEO_SHARE_SELECT_VIDEO'));
		$videos = $model->getVideos();
		foreach( $videos as $video ) {
			$video_options[] = JHTML::_('select.option', $video->id, $video->title);
		}			
		$this->videoids = JHTML::_('select.genericlist', $video_options, 'videoid', 'class="required validate-list"', 'value', 'text');		
		
		JToolBarHelper::title(JText::_('YENDIF_VIDEO_SHARE_ADD_NEW_RATING'), 'yendifvideoshare');
		JToolBarHelper::apply('apply', JText::_('YENDIF_VIDEO_SHARE_APPLY'));
		JToolBarHelper::save('save', JText::_('YENDIF_VIDEO_SHARE_SAVE'));
        JToolBarHelper::cancel('cancel', JText::_('YENDIF_VIDEO_SHARE_CANCEL'));
		
        parent::display($tpl);
		
    }
	
	public function edit( $tpl = null ) {
	
	    $model = $this->getModel();
		
		$this->item = $model->getItem();
		$this->assignRef('item', $item);
		
		$lists = array();		
		for( $i = 0.5; $i <= 5; $i += 0.5 ) {
			$rating_options[] = JHTML::_('select.option', $i, number_format($i, 1));
		}			
		$this->ratings = JHTML::_('select.genericlist', $rating_options, 'rating', '', 'value', 'text', $this->item->rating);
		
		$users = YendifVideoShareUtils::getUsers();
		foreach( $users as $user ) {
			$user_options[] = JHTML::_('select.option', $user->id, $user->username);
		}
		$this->userids = JHTML::_('select.genericlist', $user_options, 'userid', 'class="required validate-list"', 'value', 'text', $this->item->userid);
		
		$video_options[] = JHTML::_('select.option', -1, JText::_('YENDIF_VIDEO_SHARE_SELECT_VIDEO'));
		$videos = $model->getVideos();
		foreach ( $videos as $video ) {
			$video_options[] = JHTML::_('select.option', $video->id, $video->title);
		}			
		$this->videoids = JHTML::_('select.genericlist', $video_options, 'videoid', 'class="required validate-list"', 'value', 'text', $this->item->videoid);

		JToolBarHelper::title(JText::_('YENDIF_VIDEO_SHARE_EDIT_RATING'), 'yendifvideoshare');
		JToolBarHelper::apply('apply', JText::_('YENDIF_VIDEO_SHARE_APPLY'));
		JToolBarHelper::save('save', JText::_('YENDIF_VIDEO_SHARE_SAVE'));
        JToolBarHelper::cancel('cancel', JText::_('YENDIF_VIDEO_SHARE_CANCEL'));
		
        parent::display($tpl);
		
    }
	
}