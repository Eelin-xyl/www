<?php

/*
 * @version		$Id: video.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class YendifVideoShareModelVideo extends YendifVideoShareModel {
	
	public function getItem( $check_publishing_options ) {
	
        $db = JFactory::getDBO();
		$app = JFactory::getApplication();
		
        $query  = "SELECT * FROM #__yendifvideoshare_videos";
		$query .= " WHERE id=" . $app->input->getInt('id');		
		
		if( YENDIF_VIDEO_SHARE_USERID == 0 ) {
			$query .= " AND published=1";
			
			if( $check_publishing_options ) {
				$date = JFactory::getDate();
				
				$nullDate = $db->quote( $db->getNullDate() );
				$nowDate  = $db->quote( $date->toSql() );
				
				$query .= " AND (published_up = " . $nullDate . " OR published_up <= " . $nowDate .')';
				$query .= " AND (published_down = " . $nullDate . " OR published_down >= " . $nowDate .')';
			}
		} else {
			$query .= " AND (published=1 OR userid=".YENDIF_VIDEO_SHARE_USERID.")";
		}
		  			 
        $db->setQuery( $query );
        $item = $db->loadObject();
		 
        return $item;
		
	}
	
	public function getVideos( $_limit, $catid, $filterby, $orderby, $check_publishing_options ) {
	
		 $db = JFactory::getDBO();
		 $app = JFactory::getApplication();
		 
		 $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $_limit, 'int');
		 $limitstart = $app->input->get('limitstart', '0', 'INT'); 
		 $limitstart = $limit != 0 ? (floor($limitstart / $limit) * $limit) : 0;
 
		 $this->setState('limit', $limit);
		 $this->setState('limitstart', $limitstart);
		 
		 $userid = $app->input->getInt('userid', 0);

         $query  = "SELECT * FROM #__yendifvideoshare_videos WHERE id!=" . $app->input->getInt('id');
		 $query .= $userid > 0 ? " AND userid=".$userid : " AND catid=" . (int) $catid . " AND published=1";
		 
		 if( $check_publishing_options && $userid == 0 ) {
			$date = JFactory::getDate();
			
			$nullDate = $db->quote( $db->getNullDate() );
		 	$nowDate  = $db->quote( $date->toSql() );
			
		 	$query .= " AND ( published_up = " . $nullDate . " OR published_up <= " . $nowDate .')';
			$query .= " AND ( published_down = " . $nullDate . " OR published_down >= " . $nowDate .')';
		}
				 
		 if( $filterby == 'featured' ) {
		 	$query .= ' AND featured=1';
		 }
		 
		 switch( $orderby ) {
		 	case 'latest':
				$query .= ' ORDER BY created_date DESC';
				break;	
			case 'most_viewed' :
				$query .= ' ORDER BY views DESC';
				break;
			case 'most_rated' :
				$query .= ' ORDER BY rating DESC';
				break;
			case 'date_added' :
				$query .= ' ORDER BY created_date ASC';
				break;
			case 'a_z' :
				$query .= ' ORDER BY title ASC';
				break;
			case 'z_a' :
				$query .= ' ORDER BY title DESC';
				break;
			case 'random' :
				$query .= ' ORDER BY RAND()';
				break;
			case 'ordering' :
				$query .= ' ORDER BY ordering';
				break;
			default :
				$query .= ' ORDER BY id DESC';
		 }
		 
         $db->setQuery( $query, $limitstart, $limit );
         $items = $db->loadObjectList();
		 
		 return $items;
		 
	}
	
	public function getTotal( $catid, $filterby, $check_publishing_options ) {	
	
         $db = JFactory::getDBO();
		 $app = JFactory::getApplication();	
		 
		 $userid = $app->input->getInt('userid', 0);

         $query  = "SELECT COUNT(*) FROM #__yendifvideoshare_videos WHERE id!=" . $app->input->getInt('id');
		 $query .= $userid > 0 ? " AND userid=".$userid : " AND catid=" . (int) $catid . " AND published=1";
		 
		 if( $check_publishing_options && $userid == 0 ) {
			$date = JFactory::getDate();
			
			$nullDate = $db->quote( $db->getNullDate() );
		 	$nowDate  = $db->quote( $date->toSql() );
			
		 	$query .= " AND ( published_up = " . $nullDate . " OR published_up <= " . $nowDate .')';
			$query .= " AND ( published_down = " . $nullDate . " OR published_down >= " . $nowDate .')';
		}		 	
		 
		if( $filterby == 'featured' ) {
		 	$query .= ' AND featured=1';
		}
		
        $db->setQuery( $query );
        $total = $db->loadResult();
		 
		return $total;
		
	}
	
	public function getPagination( $catid, $filterby, $check_publishing_options ) {
	
    	jimport( 'joomla.html.pagination' );
		 
		$pageNav = new JPagination( $this->getTotal( $catid, $filterby, $check_publishing_options ), $this->getState('limitstart'), $this->getState('limit') );
    	return $pageNav;
		
	}
	
	public function getCategory( $id ) {
	
         $db = JFactory::getDBO();
		 
         $query = "SELECT * FROM #__yendifvideoshare_categories WHERE published=1 AND id=$id";
         $db->setQuery( $query );
         $item = $db->loadObject();
		 
         return $item;
		 
	}
	
	public function getTotalRatedUsers() {
	
		$db = JFactory::getDBO();
		$app = JFactory::getApplication();	
		 
    	$query = "SELECT COUNT(id) FROM #__yendifvideoshare_ratings WHERE videoid=".$app->input->getInt('id');
        $db->setQuery( $query );
		
		return $db->loadResult();
			
	}
		
}