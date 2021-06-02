<?php

/*
 * @version		$Id: user.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class YendifVideoShareModelUser extends YendifVideoShareModel {
	
	var $search_key;
	
	public function getItems( $_limit, $userid, $orderby ) {	
		
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
				 
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $_limit, 'int');
		$limitstart = $app->input->get('limitstart', '0', 'INT');	 
		$limitstart = $limit != 0 ? (floor($limitstart / $limit) * $limit) : 0; 
		
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);	

		$query = "SELECT v.*, c.name as category FROM #__yendifvideoshare_videos AS v";
		$query.= " LEFT JOIN #__yendifvideoshare_categories AS c ON v.catid = c.id";
		$where = array();
		
		$where[] = "v.userid=" . $userid;
		$where[] = "v.title <> ''";
		
		$this->search_key = $app->getUserStateFromRequest('yendif.search.user', 'search', '', 'string');	
		$this->search_key = JString::strtolower($this->search_key);
		if( $this->search_key ) {
			$search_key = $db->Quote( '%'.$db->escape( $this->search_key, true ).'%', false );
			$where[] = '(CONCAT(v.title,v.description,v.meta_keywords,v.meta_description) LIKE '. $search_key . ' OR c.name LIKE ' . $search_key . ')';
		}

		$where = ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );
		$query .= $where;
		
		switch( $orderby ) {
			case 'latest':
				$query .= ' ORDER BY v.created_date DESC';
				break;	
			case 'most_viewed' :
				$query .= ' ORDER BY v.views DESC';
				break;
			case 'most_rated' :
				$query .= ' ORDER BY v.rating DESC';
				break;
			case 'date_added' :
				$query .= ' ORDER BY v.created_date ASC';
				break;
			case 'a_z' :
				$query .= ' ORDER BY v.title ASC';
				break;
			case 'z_a' :
				$query .= ' ORDER BY v.title DESC';
				break;
			case 'random' :
				$query .= ' ORDER BY RAND()';
				break;
			case 'ordering' :
				$query .= ' ORDER BY v.catid, v.ordering';
				break;
			default :
				$query .= ' ORDER BY v.id DESC';
		}
		 
    	$db->setQuery ( $query, $limitstart, $limit );
    	$items = $db->loadObjectList();
		 
        return $items;
		
	}
	
	public function getTotal( $userid ) {
	
        $db = JFactory::getDBO();
		
        $query = "SELECT COUNT(v.id) FROM #__yendifvideoshare_videos AS v";
		$query.= " LEFT JOIN #__yendifvideoshare_categories AS c ON v.catid = c.id";
		$where = array();
		
		$where[] = "v.userid=" . $userid;
		$where[] = "v.title <> ''";
		 
		if( $this->search_key ) {
			$search_key = $db->Quote( '%'.$db->escape( $this->search_key, true ).'%', false );
			$where[] = '(CONCAT(v.title,v.description,v.meta_keywords,v.meta_description) LIKE '. $search_key . ' OR c.name LIKE ' . $search_key . ')';
		}

		$where = ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );
		$query .= $where;
		
        $db->setQuery( $query );
        $total = $db->loadResult();
		 
        return $total;
		
	}
	
	public function getPagination( $userid ) {
	
    	 jimport( 'joomla.html.pagination' );
		 
		 $pageNav = new JPagination($this->getTotal( $userid ), $this->getState('limitstart'), $this->getState('limit'));
         return $pageNav;
		 
	}	
	
	public function getCategories() {
	
         $db = JFactory::getDBO();
		 
		 $query = 'SELECT * FROM #__yendifvideoshare_categories WHERE published=1';
		 $db->setQuery( $query );
		 $mitems = $db->loadObjectList();
		
		 $children = array();
		 if( $mitems ) {
			foreach ( $mitems as $v ) {
				$v->title = $v->name;
				$v->parent_id = $v->parent;
				$pt = $v->parent;				
				$list = @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v );
				$children[$pt] = $list;
			}
		 }
		
		 $list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0 );		
		 return $list;
		 
	}
	
	public function getItem() {
	
		$app = JFactory::getApplication();
		
        $row = JTable::getInstance('Videos', 'YendifVideoShareTable');
        $row->load( $app->input->getInt('id') );

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
		 
		$row->title = YendifVideoShareUtils::safeString( $row->title );
	  	if( ! $row->alias ) $row->alias = $row->title;		
		$row->alias = YendifVideoShareUtils::stringURLSafe( $row->alias );
		
		if( $row->alias ) {
			$row->alias = str_replace( '-amp-', '-and-', $row->alias );
		}
		
		$row->description = $app->input->post->get( 'description', '', 'RAW' );
		
		if( ! $row->published_up ) {
			$date = JFactory::getDate();
			$nowDate = $date->toSql();
			$row->published_up = $nowDate;
		}
		
		$msg = JText::_('YENDIF_VIDEO_SHARE_SAVED');
		
		if( isset( $post['status'] ) && 'new' == $post['status'] ) {
			$row->access = YendifVideoShareUtils::getColumn('categories', 'access', $row->catid);
			$row->featured = 0;
			$config = YendifVideoShareUtils::getConfig();
			$row->published = $config->autopublish;	
			if( ! $row->published ) {
				$msg = JText::_('YENDIF_VIDEO_SHARE_WAITING_APPROVAL');
			}	
		}
		
		if( $row->type == 'youtube' ) {
			$v = $this->getYouTubeVideoId( $row->youtube );
			$row->youtube = 'https://www.youtube.com/watch?v='.$v;
			if( ! $row->image ) {
          		$row->image = 'https://img.youtube.com/vi/'.$v.'/0.jpg';
			}
	    }
		
		if( $row->type == 'rtmp' ) {
			$row->mp4 = $app->input->post->get( 'mobile', '', 'STRING' );
		}
		
		$row->userid = YENDIF_VIDEO_SHARE_USERID;
		
		$row->reorder( "catid=".$row->catid );
		 
	  	if( ! $row->store() ){
			$app->enqueueMessage( $row->getError(), 'error' );
	  	}

		$_itemId =  $app->input->getInt('Itemid', 0);
		$itemId = $_itemId > 0 ? '&Itemid=' . $_itemId : '';
		$link = JRoute::_( 'index.php?option=com_yendifvideoshare&view=user' . $itemId, false );
		 
		$app->redirect( $link, $msg, 'message' );	 
		
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
	
	public function delete() {
	
		 $app = JFactory::getApplication();
         $db = JFactory::getDBO();
		 
         $id = $app->input->getInt('id', 0);
         if( $id > 0 ) {
            $query = "DELETE FROM #__yendifvideoshare_videos WHERE id=" . $id;
            $db->setQuery( $query );
            if( ! $db->query() ) {
				echo "<script> alert('".$db->getErrorMsg()."');window.history.go(-1); </script>\n";
			} else {
				jimport('joomla.filesystem.folder');
				$delDir = YENDIF_VIDEO_SHARE_UPLOAD_BASE.'videos'.DIRECTORY_SEPARATOR.$id;
				if( JFolder::exists( $delDir ) ) JFolder::delete( $delDir );
				
				$query = "DELETE FROM #__yendifvideoshare_ratings WHERE videoid=" . $id;
				$db->setQuery( $query );
				if( ! $db->query() ) {
                	echo "<script> alert('".$db->getErrorMsg()."');window.history.go(-1); </script>\n";
            	}
			}
         }
		
         $_itemId = $app->input->getInt('Itemid', 0);
		 $itemId = $_itemId > 0 ? '&Itemid=' . $_itemId : '';
		 $link = JRoute::_( 'index.php?option=com_yendifvideoshare&view=user' . $itemId, false );
		 
		 $app->redirect( $link ); 
	}
		
}