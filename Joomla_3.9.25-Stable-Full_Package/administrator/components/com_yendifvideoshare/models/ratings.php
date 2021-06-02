<?php

/*
 * @version		$Id: ratings.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class YendifVideoShareModelRatings extends YendifVideoShareModel {
	
	public function getItems() {
	
		$app = JFactory::getApplication();	
		
		$option = $app->input->get('option');
		$view = $app->input->get('view');
		 
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = $app->getUserStateFromRequest($option.$view.'.limitstart', 'limitstart', 0, 'int');
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);		
		$filter_user = $app->getUserStateFromRequest($option.$view.'filter_user', 'filter_user', -1, 'int');
		$filter_rating = $app->getUserStateFromRequest($option.$view.'filter_rating', 'filter_rating', -1, 'float');
		$search = $app->getUserStateFromRequest($option.$view.'search', 'search', '', 'string');
		$search = JString::strtolower($search);
		 
	    $db = JFactory::getDBO();
        $query = "SELECT r.*, v.title as video FROM #__yendifvideoshare_ratings AS r";
		$query.= " LEFT JOIN #__yendifvideoshare_videos AS v ON r.videoid = v.id";
		$where = array();
		 
		if( $filter_user > -1 ) {
			$where[] = "r.userid=".$filter_user;
		}
		 
		if( $filter_rating > -1 ) {
			$where[] = 'r.rating='.$filter_rating;
		}
		
		if( $search ) {			
		 	$escaped = $db->escape( $search, true );
			$where[] = 'LOWER(v.title) LIKE '. $db->Quote( '%'.$escaped.'%', false );
		}

		$where = count($where) ? ' WHERE '. implode(' AND ', $where) : '';
		 
		$query .= $where;
		$query .= " ORDER BY id DESC";
        $db->setQuery( $query, $limitstart, $limit );
        $items = $db->loadObjectList();
		 
        return $items;
		
	}
	
	public function getPagination() {
	
		 $app = JFactory::getApplication();	
		 
		 $option = $app->input->get('option');
		 $view = $app->input->get('view');
		 
		 $total = $this->getTotal();
		 $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		 $limitstart = $app->getUserStateFromRequest($option.$view.'.limitstart', 'limitstart', 0, 'int');
     
    	 jimport( 'joomla.html.pagination' );
		 $pageNav = new JPagination($total, $limitstart, $limit);
		 
         return $pageNav;
		 
	}
	
	public function getTotal() {
	
		 $app = JFactory::getApplication();	
		 
		 $option = $app->input->get('option');
		 $view = $app->input->get('view');
		 
		 $filter_user = $app->getUserStateFromRequest($option.$view.'filter_user', 'filter_user', -1, 'int');
		 $filter_rating = $app->getUserStateFromRequest($option.$view.'filter_rating', 'filter_rating', -1, 'float');
		 $search = $app->getUserStateFromRequest($option.$view.'search', 'search', '', 'string');
		 $search = JString::strtolower($search);
		 
         $db = JFactory::getDBO();
         $query = "SELECT COUNT(r.id) FROM #__yendifvideoshare_ratings AS r";
		 $query.= " LEFT JOIN #__yendifvideoshare_videos AS v ON r.videoid = v.id";
		 $where = array();

		 if( $filter_user > -1 ) {
			$where[] = "r.userid=".$filter_user;
		 }
		 
		 if( $filter_rating > -1 ) {
			$where[] = 'r.rating='.$filter_rating;
		 }
		
		 if( $search ) {
		 	$escaped = $db->escape( $search, true );
			$where[] = 'LOWER(v.title) LIKE '.$db->Quote( '%'.$escaped.'%', false );
		 }

		 $where = count($where) ? ' WHERE '. implode(' AND ', $where) : '';		 
		 $query .= $where;
		 
         $db->setQuery( $query );
         $count = $db->loadResult();
		 
         return $count;
		 
	}
	
	public function getLists( $page = 'list' ) {
	
		 $app = JFactory::getApplication();	
		 
		 $option = $app->input->get('option');
		 $view = $app->input->get('view');
		 
		 $filter_user = $app->getUserStateFromRequest($option.$view.'filter_user', 'filter_user', -1, 'int');
		 $filter_rating = $app->getUserStateFromRequest($option.$view.'filter_rating', 'filter_rating', -1, 'float');
		 $search = $app->getUserStateFromRequest($option.$view.'search', 'search', '', 'string');
		 $search = JString::strtolower( $search );
     
    	 $lists = array();
		 $lists['search'] = $search;
		 
		 $user_options[] = JHTML::_('select.option', -1, '-- '.JText::_('YENDIF_VIDEO_SHARE_SELECT_USER').' --');
		 $users = YendifVideoShareUtils::getUsers();
		 foreach( $users as $user ) {
			$user_options[] = JHTML::_('select.option', $user->id, $user->username);
		 }			
	 	 $lists['users'] = JHTML::_('select.genericlist', $user_options, 'filter_user', 'onchange="this.form.submit();"', 'value', 'text', $filter_user);
			
		 $rating_options[] = JHTML::_('select.option', -1, '-- '.JText::_('YENDIF_VIDEO_SHARE_SELECT_RATING').' --');
		 for( $i = 0.5; $i <= 5; $i += 0.5 ) {
			$rating_options[] = JHTML::_('select.option', $i, number_format($i, 1));
		 }			
		 $lists['ratings'] = JHTML::_('select.genericlist', $rating_options, 'filter_rating', 'onchange="this.form.submit();"', 'value', 'text', $filter_rating);	
		 
         return $lists;
		 
	}

	public function getItem() {
	
		$db = JFactory::getDBO();
		$app = JFactory::getApplication();	
		
		$cid = $app->input->get('cid', array(0), 'ARRAY');
		
        $query  = "SELECT r.*, v.title as video FROM #__yendifvideoshare_ratings AS r";
		$query .= " LEFT JOIN #__yendifvideoshare_videos AS v ON r.videoid = v.id";
		$query .= " WHERE r.id = " . $cid[0];
        $db->setQuery( $query );
        $item = $db->loadObject();

        return $item;
				
	}
	
	public function getVideos() {
	
		$db = JFactory::getDBO();
		
		$nowDate = JHTML::_('date', 'now', 'Y-m-d H:i:s', false);
        $query = "SELECT * FROM #__yendifvideoshare_videos WHERE published=1";
		$query .= " AND published_up <=".$db->Quote($nowDate);
		$db->setQuery( $query );
        $items = $db->loadObjectList();
		 
        return $items;
		
	}	
	
    public function save() {
	
		$app = JFactory::getApplication();
		
		$cid = $app->input->get('cid', array(0), 'ARRAY');
		$id = $cid[0];
		
	  	$row = JTable::getInstance('Ratings', 'YendifVideoShareTable');
      	$row->load($id);
	
		$post = $app->input->post->getArray();
      	if( ! $row->bind( $post ) ) {
			$app->enqueueMessage( $row->getError(), 'error' );
	  	}
		
		jimport( 'joomla.filter.output' );
		$row->rating = JString::trim($row->rating);
		
	  	if( ! $row->store() ) {
			$app->enqueueMessage( $row->getError(), 'error' );
	  	}
		
		$this->updateRating($row->videoid);

		$task = $app->input->get('task');
	  	switch( $task ) {
        	case 'apply':
            	$msg  = JText::_('YENDIF_VIDEO_SHARE_CHANGES_SAVED');
             	$link = 'index.php?option=com_yendifvideoshare&view=ratings&task=edit&'. YendifVideoShareUtils::getToken() .'=1&'.'cid[]='.$row->id;
             	break;
        	case 'save':
        	default:
              	$msg  = JText::_('YENDIF_VIDEO_SHARE_SAVED');
              	$link = 'index.php?option=com_yendifvideoshare&view=ratings';
              	break;
      	}

	  	$app->redirect($link, $msg, 'message');
		
	}

    public function cancel() {
	
		$app = JFactory::getApplication();
		 
		$link = 'index.php?option=com_yendifvideoshare&view=ratings';
	    $app->redirect($link);
		
    }
	
	public function delete() {
	
		$db = JFactory::getDBO();
		$app = JFactory::getApplication();
		
		$cid = $app->input->get('cid', array(), 'ARRAY');
        $cids = implode(',', $cid);
		
        if( count( $cid ) ) {
			$query = "SELECT videoid FROM #__yendifvideoshare_ratings WHERE id IN ( $cids )";
        	$db->setQuery( $query );
			$items = $db->loadObjectList();
			
            $query = "DELETE FROM #__yendifvideoshare_ratings WHERE id IN ($cids)";
            $db->setQuery( $query );
            if( ! $db->query() ) {
                echo "<script> alert('".$db->getErrorMsg()."');window.history.go(-1); </script>\n";
            }			
		
			foreach( $items as $item ) {
				$this->updateRating( $item->videoid );
			}
        }	
		
        $app->redirect('index.php?option=com_yendifvideoshare&view=ratings');
		
	}
	
	public function updateRating( $videoid ) {
	
		$db	= JFactory::getDBO();
		
		$query = "SELECT SUM(rating) as total_ratings, COUNT(id) as total_users FROM #__yendifvideoshare_ratings WHERE videoid=".$videoid;
        $db->setQuery( $query );
		$result = $db->loadObject();				
		$rating = ( $result->total_ratings / ($result->total_users * 5) ) * 100;
				
		$query  = "UPDATE #__yendifvideoshare_videos SET rating=".$rating." WHERE id=".$videoid;
		$db->setQuery( $query );
		$db->query();
		
	}
	
}