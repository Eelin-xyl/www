<?php

/*
 * @version		$Id: videos.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access'); 

class YendifVideoShareModelVideos extends YendifVideoShareModel {
	
	public function getItems() {	
		
		$app = JFactory::getApplication();	
		
		$option = $app->input->get('option');
		$view = $app->input->get('view');
		 
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = $app->getUserStateFromRequest($option.$view.'.limitstart', 'limitstart', 0, 'int');
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
				
		$filter_state = $app->getUserStateFromRequest($option.$view.'filter_state', 'filter_state', -1, 'int');
		$filter_featured = $app->getUserStateFromRequest($option.$view.'filter_featured', 'filter_featured', -1, 'int');
		$filter_user = $app->getUserStateFromRequest($option.$view.'filter_user', 'filter_user', -1, 'int');
		$filter_category = $app->getUserStateFromRequest($option.$view.'filter_category', 'filter_category', -1, 'int');
		$search = $app->getUserStateFromRequest($option.$view.'search', 'search', '', 'string');
		$search = JString::strtolower($search);
		 
	    $db = JFactory::getDBO();
        $query = "SELECT v.*, c.name as category FROM #__yendifvideoshare_videos AS v";
		$query.= " LEFT JOIN #__yendifvideoshare_categories AS c ON v.catid = c.id";
		$where = array();
		 
		$where[] = "v.title <> ''";
		 
		if( $filter_state > -1 ) {
			$where[] = "v.published=".$filter_state;
		}
		
		if( $filter_featured > -1 ) {
			$where[] = "v.featured=".$filter_featured;
		}
		
		if( $filter_user > -1 ) {
			$where[] = "v.userid=".$filter_user;
		}
		 
		if( $filter_category > -1 ) {
			$where[] = 'v.catid='.$filter_category;
		} else {
			$where[] = 'v.catid!=0';
		}
		
		if( $search ) {			
		 	$escaped = $db->escape( $search, true );
			$searchKey = $db->Quote( '%'.$escaped.'%', false );
			$where[] = 'LOWER(v.title) LIKE '. $searchKey . ' OR c.name LIKE ' . $searchKey;
		}	

		$where = count( $where ) ? ' WHERE '. implode(' AND ', $where) : '';
		 
		$query .= $where;		
		$query .= " ORDER BY v.catid,v.ordering";									
				
        $db->setQuery( $query, $limitstart, $limit );
        $items = $db->loadObjectList();
						
        return $items;
		
	}
	
	public function getTotal() {
	
		$db = JFactory::getDBO();
		$app = JFactory::getApplication();	
		
		$option = $app->input->get('option');
		$view = $app->input->get('view');	 		
		
		$filter_state = $app->getUserStateFromRequest($option.$view.'filter_state', 'filter_state', -1, 'int');
		$filter_featured = $app->getUserStateFromRequest($option.$view.'filter_featured', 'filter_featured', -1, 'int');
		$filter_user = $app->getUserStateFromRequest($option.$view.'filter_user', 'filter_user', -1, 'int');
		$filter_category = $app->getUserStateFromRequest($option.$view.'filter_category', 'filter_category', -1, 'int');
		$search = $app->getUserStateFromRequest($option.$view.'search', 'search', '', 'string');
		$search = JString::strtolower($search);

        $query = "SELECT COUNT(v.id) FROM #__yendifvideoshare_videos AS v";
		$query.= " LEFT JOIN #__yendifvideoshare_categories AS c ON v.catid = c.id";
		$where = array();
		 
		$where[] = "v.title <> ''";
		
		if( $filter_state > -1 ) {
			$where[] = "v.published=".$filter_state;
		}
		
		if( $filter_featured > -1 ) {
			$where[] = "v.featured=".$filter_featured;
		}
		
		if( $filter_user > -1 ) {
			$where[] = "v.userid=".$filter_user;
		}

		if( $filter_category > -1 ) {
			$where[] = 'v.catid='.$filter_category;
		} else {
			$where[] = 'v.catid!=0';
		}
		 
		if( $search ) {
		 	$escaped = $db->escape( $search, true );
			$searchKey = $db->Quote( '%'.$escaped.'%', false );
			$where[] = 'LOWER(v.title) LIKE '. $searchKey . ' OR c.name LIKE ' . $searchKey;
		}

		$where = count( $where ) ? ' WHERE '. implode(' AND ', $where) : '';
		$query .= $where;

        $db->setQuery( $query );
        $count = $db->loadResult();
		
        return $count;
		
	}
	
	public function getPagination() {
	
		$app = JFactory::getApplication();	
		
		$option = $app->input->get('option');
		$view = $app->input->get('view');
		
		$total = $this->getTotal();
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = $app->getUserStateFromRequest($option.$view.'.limitstart', 'limitstart', 0, 'int');
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
     
    	jimport( 'joomla.html.pagination' );
		$pageNav = new JPagination($total, $limitstart, $limit);
		
        return $pageNav;
		
	}
	
	public function getLists() {
	
		$app = JFactory::getApplication();	
		
		$option = $app->input->get('option');
		$view = $app->input->get('view');
		 
		$filter_state = $app->getUserStateFromRequest($option.$view.'filter_state', 'filter_state', -1, 'int' );
		$filter_featured = $app->getUserStateFromRequest($option.$view.'filter_featured', 'filter_featured', -1, 'int' );
		$filter_user = $app->getUserStateFromRequest($option.$view.'filter_user', 'filter_user', -1, 'int');
		$filter_category = $app->getUserStateFromRequest($option.$view.'filter_category', 'filter_category', -1, 'int');
		$search = $app->getUserStateFromRequest($option.$view.'search','search','','string');
		$search = JString::strtolower( $search );		
		     
    	$lists = array ();
		$lists['search'] = $search;
            
		$filter_state_options[] = JHTML::_('select.option', -1, '-- '.JText::_('YENDIF_VIDEO_SHARE_SELECT_STATUS').' --');
		$filter_state_options[] = JHTML::_('select.option', 1, JText::_('YENDIF_VIDEO_SHARE_PUBLISHED'));
		$filter_state_options[] = JHTML::_('select.option', 0, JText::_('YENDIF_VIDEO_SHARE_UNPUBLISHED'));
		$lists['state'] = JHTML::_('select.genericlist', $filter_state_options, 'filter_state', 'onchange="this.form.submit();"', 'value', 'text', $filter_state);
		
		$filter_featured_options[] = JHTML::_('select.option', -1, '-- '.JText::_('YENDIF_VIDEO_SHARE_SELECT_FEATURED').' --');
		$filter_featured_options[] = JHTML::_('select.option', 1, JText::_('YENDIF_VIDEO_SHARE_FEATURED_ONLY'));
		$filter_featured_options[] = JHTML::_('select.option', 0, JText::_('YENDIF_VIDEO_SHARE_ALL_VIDEOS'));
		$lists['featured'] = JHTML::_('select.genericlist', $filter_featured_options, 'filter_featured', 'onchange="this.form.submit();"', 'value', 'text', $filter_featured);
		
		$user_options[] = JHTML::_('select.option', -1, '-- '.JText::_('YENDIF_VIDEO_SHARE_SELECT_USER').' --');
		$users = YendifVideoShareUtils::getUsers();
		foreach( $users as $user ) {
			$user_options[] = JHTML::_('select.option', $user->id, $user->username);
		}	
		$lists['users'] = JHTML::_('select.genericlist', $user_options, 'filter_user', 'onchange="this.form.submit();"', 'value', 'text', $filter_user); 
		 
		$category_options[] = JHTML::_('select.option', -1, '-- '.JText::_('YENDIF_VIDEO_SHARE_SELECT_CATEGORY').' --');
		$categories = $this->getCategories(0);		 
		foreach( $categories as $item ) {
			$item->treename = JString::str_ireplace('&#160;', '-', $item->treename);
			$category_options[] = JHTML::_('select.option', $item->id, $item->treename );
		}
		$lists['categories'] = JHTML::_('select.genericlist', $category_options, 'filter_category', 'onchange="this.form.submit();"', 'value', 'text', $filter_category);
				 
        return $lists;
		
	}
	
	public function getCategories( $published = 1 ) {
	
        $db = JFactory::getDBO();
		
		$query = 'SELECT * FROM #__yendifvideoshare_categories';
		if( $published == 1 ) $query .= ' WHERE published=1';
		$db->setQuery( $query );
		$mitems = $db->loadObjectList();
		
		$children = array();
		if( $mitems ) {
			foreach( $mitems as $v ) {
				$v->title = $v->name;
				$v->parent_id = $v->parent;
				$pt = $v->parent;				
				$list = @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v );
				$children[$pt] = $list;
			}
		}
		
		$list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0);	
			
		return $list;
		
	}
	
	public function getItem() {
	
     	$db = JFactory::getDBO();
		$app = JFactory::getApplication();
		
		$cid = $app->input->get('cid', array(0), 'ARRAY');
		$id = $cid[0];
		
        $row = JTable::getInstance('Videos', 'YendifVideoShareTable');
        $row->load($id);

        return $row;
		
	}
	
	public function save() {
	
		$app = JFactory::getApplication();
		
		$cid = $app->input->get('cid', array(0), 'ARRAY');
		$id = $cid[0];
		
	  	$row = JTable::getInstance('Videos', 'YendifVideoShareTable');		
     	$row->load($id);
	
		$post = $app->input->post->getArray();
      	if( ! $row->bind( $post ) ) {
			$app->enqueueMessage( $row->getError(), 'error' );
	  	}
	  
		$row->title = YendifVideoShareUtils::safeString($row->title);
	  	if( ! $row->alias ) $row->alias = $row->title;
		$row->alias  = YendifVideoShareUtils::stringURLSafe($row->alias);
		
		if( $row->alias ) {
			$row->alias = str_replace( '-amp-', '-and-', $row->alias );
		}
		
	  	$row->description = $app->input->post->get( 'description', '', 'RAW' );
		$row->thirdparty = $app->input->post->get( 'thirdparty', '', 'RAW' );
		
		if( $row->access == '' ) {
			$row->access = YendifVideoShareUtils::getColumn('categories', 'access', $row->catid);
		}		
		
		$row->reorder( "catid=".$row->catid );
		
		if( $row->type == 'youtube' ) {
			$v = $this->getYoutubeVideoId( $row->youtube );
			$row->youtube = 'https://www.youtube.com/watch?v='.$v;
			if( ! $row->image ) {
          		$row->image = 'https://img.youtube.com/vi/'.$v.'/0.jpg';
			}
	    }	
			
		if( $row->type == 'rtmp' ) {
			$row->mp4 = $app->input->post->get( 'mobile', '', 'STRING' );
		}
		
		if( ! $row->published_up ) {
			$date = JFactory::getDate();
			$nowDate = $date->toSql();
			$row->published_up = $nowDate;
		}				
							
	  	if( ! $row->store() ) {
			$app->enqueueMessage( $row->getError(), 'error' );
	  	}
		
		$task = $app->input->get('task');
	  	switch( $task ) {
        	case 'apply':
            	$msg  = JText::_('YENDIF_VIDEO_SHARE_CHANGES_SAVED');
             	$link = 'index.php?option=com_yendifvideoshare&view=videos&task=edit&'. YendifVideoShareUtils::getToken() .'=1&'.'cid[]='.$row->id;				
             	break;
			case 'save2new':
				$msg  = JText::_('YENDIF_VIDEO_SHARE_SAVED');
             	$link = 'index.php?option=com_yendifvideoshare&view=videos&task=add&'. YendifVideoShareUtils::getToken() .'=1';
              	break;
        	case 'save':
        	default:
				$msg  = JText::_('YENDIF_VIDEO_SHARE_SAVED');
             	$link = 'index.php?option=com_yendifvideoshare&view=videos';
              	break;
      	}
		 
		$app->redirect($link, $msg, 'message'); 
			 
	}
	
	public function getYoutubeVideoId($url) {
	
    	$video_id = false;
    	$url = parse_url( $url );
    	if( strcasecmp( $url['host'], 'youtu.be' ) === 0 ) {
        	$video_id = substr( $url['path'], 1 );
    	} else if( strcasecmp( $url['host'], 'www.youtube.com' ) === 0 ) {
        	if( isset( $url['query'] ) ) {
           		parse_str( $url['query'], $url['query'] );
            	if( isset( $url['query']['v'] ) ) {
               		$video_id = $url['query']['v'];
            	}
        	}
			
        	if( $video_id == false ) {
            	$url['path'] = explode('/', substr($url['path'], 1));
            	if( in_array( $url['path'][0], array('e', 'embed', 'v') ) ) {
                	$video_id = $url['path'][1];
            	}
        	}
    	}
		
    	return $video_id;
		
	}
	
	public function cancel() {
	
		$app = JFactory::getApplication();
		 
		$link = 'index.php?option=com_yendifvideoshare&view=videos';
	    $app->redirect($link);
		
	}	

	public function delete() {
	
		$db = JFactory::getDBO();
		$app = JFactory::getApplication();
		
		$cid = $app->input->get('cid', array(), 'ARRAY');
        $cids = implode(',', $cid);
		
        if( count( $cid ) ) {
            $query = "DELETE FROM #__yendifvideoshare_videos WHERE id IN ($cids)";
            $db->setQuery( $query );
            if( ! $db->query() ) {
                echo "<script> alert('".$db->getErrorMsg()."');window.history.go(-1); </script>\n";
            } else {
				jimport('joomla.filesystem.folder');
				$n = count($cid);				
				for( $i = 0; $i < $n; $i++ ) {
					$delDir = YENDIF_VIDEO_SHARE_UPLOAD_BASE.'videos'.DIRECTORY_SEPARATOR.$cid[$i];
					if( JFolder::exists($delDir) ) JFolder::delete($delDir);
				}
				
				$query = "DELETE FROM #__yendifvideoshare_ratings WHERE videoid IN ($cids)";
				$db->setQuery( $query );
				if( ! $db->query() ) {
                	echo "<script> alert('".$db->getErrorMsg()."');window.history.go(-1); </script>\n";
            	}
			}			
        }
		
        $app->redirect('index.php?option=com_yendifvideoshare&view=videos');
		
	}
	
	public function publish() {
	
		$app = JFactory::getApplication();
		$cid = $app->input->get('cid', array(), 'ARRAY');   
        $publish = $app->input->get('task') == 'publish' ? 1 : 0;
			
        $row = JTable::getInstance('Videos', 'YendifVideoShareTable');
        $row->publish($cid, $publish);
        $app->redirect('index.php?option=com_yendifvideoshare&view=videos');
		
    }
	
	public function saveOrder() {
	
		$app = JFactory::getApplication();
		
		$cid = $app->input->get('cid', array(0), 'ARRAY'); 
		$total = count( $cid );
		
		$order = $app->input->get('order', array(0), 'ARRAY'); 
		JArrayHelper::toInteger($order, array(0));
		 
		$row = JTable::getInstance('Videos', 'YendifVideoShareTable');
		$groupings = array();
		for( $i=0; $i < $total; $i++ ) {
			$row->load( (int) $cid[$i] );
			$groupings[] = $row->catid;
 			if( $row->ordering != $order[$i] ) {
				$row->ordering  = $order[$i];
				if( ! $row->store() ) {
					$app->enqueueMessage( $row->getError(), 'error' );
				}
			}
		}
 
		$groupings = array_unique( $groupings );
		 foreach( $groupings as $group ) {
			$row->reorder( 'catid='.$group );
		 }
 
		$app->redirect('index.php?option=com_yendifvideoshare&view=videos', JText::_('YENDIF_VIDEO_SHARE_NEW_ORDERING_SAVED'), 'message');
		
	}
	
	public function move($direction) {
	
		$app = JFactory::getApplication();
		
		$cid = $app->input->get('cid', array(0), 'ARRAY');
		$id = $cid[0];
		
		$row = JTable::getInstance('Videos', 'YendifVideoShareTable');
		$row->load($id);
		$row->move($direction, 'catid='.$row->catid);
		$row->reorder('catid='.$row->catid);
	  	$app->redirect('index.php?option=com_yendifvideoshare&view=videos', JText::_('YENDIF_VIDEO_SHARE_NEW_ORDERING_SAVED'), 'message');
		
	}
	
	public function recreate() {
	
		$db = JFactory::getDBO();
		$app = JFactory::getApplication();
		
        $cid = $app->input->get('cid', array(), 'ARRAY');
        $cids = implode(',', $cid);
		
        if( count( $cid ) ) {			
			$query = "SELECT id, image FROM #__yendifvideoshare_videos WHERE id IN ( $cids )";
            $db->setQuery($query);
			$items = $db->loadObjectList();	

			$uploader = YendifVideoShareUpload::getInstance();
			foreach( $items as $item ) {
				$uploader->doRecreateImages( $item->image, 'videos'.DIRECTORY_SEPARATOR.$item->id );
			}
        }
		
        $app->redirect('index.php?option=com_yendifvideoshare&view=videos', JText::_('YENDIF_VIDEO_SHARE_IMAGES_RECREATED_SUCCESSFULLY'), 'message');
		
	}		
	
}