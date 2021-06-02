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
	
	public function getItems( $_limit, $filterby, $orderby, $check_publishing_options ) {
	
		$app = JFactory::getApplication();	
		$db = JFactory::getDBO();
		 
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $_limit, 'int');
		$limitstart = $app->input->get('limitstart', '0', 'INT'); 
		$limitstart = $limit != 0 ? (floor($limitstart / $limit) * $limit) : 0;
		  
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);				 			 
		 			 
        $query = "SELECT v.*, c.name as category FROM #__yendifvideoshare_videos AS v";	
		$query.= " LEFT JOIN #__yendifvideoshare_categories AS c ON v.catid = c.id";	 
		$query .= " WHERE v.published=1";
		 
		if( $check_publishing_options ) {
		 	$date = JFactory::getDate();
			
		 	$nullDate = $db->quote( $db->getNullDate() );
		 	$nowDate  = $db->quote( $date->toSql() );
		 
		 	$query .= " AND ( v.published_up = " . $nullDate . " OR v.published_up <= " . $nowDate .' )';
			$query .= " AND ( v.published_down = " . $nullDate . " OR v.published_down >= " . $nowDate .' )';
		} 
		 
		if( $filterby == 'featured' ) {
		 	$query .= ' AND v.featured=1';
		}
		
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
		
		 	 								
        $db->setQuery( $query, $limitstart, $limit );
        $items = $db->loadObjectList();
		 		 
		return $items;
		
	}
	
	public function getTotal( $filterby, $check_publishing_options ) {	
		 
        $db = JFactory::getDBO();	

        $query = "SELECT COUNT(id) FROM #__yendifvideoshare_videos WHERE published=1";		
		 
		if( $check_publishing_options ) {
		 	$date = JFactory::getDate();
			
		 	$nullDate = $db->quote( $db->getNullDate() );
		 	$nowDate  = $db->quote( $date->toSql() );
		 
		 	$query .= " AND ( published_up = " . $nullDate . " OR published_up <= " . $nowDate .' )';
			$query .= " AND ( published_down = " . $nullDate . " OR published_down >= " . $nowDate .' )';
		} 
		
        $db->setQuery( $query );
        $total = $db->loadResult();

		return $total;
		
	}
	
	public function getPagination( $filterby, $check_publishing_options ) {
	
    	jimport( 'joomla.html.pagination' );
		 
		$pageNav = new JPagination( $this->getTotal( $filterby, $check_publishing_options ), $this->getState('limitstart'), $this->getState('limit') );
        return $pageNav;
		
	}
		
}