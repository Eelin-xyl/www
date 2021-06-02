<?php

/*
 * @version		$Id: search.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class YendifVideoShareModelSearch extends YendifVideoShareModel {

	var $search_key;

	public function getItems( $_limit, $check_publishing_options ) {
	
		 $app = JFactory::getApplication();
		 $db = JFactory::getDBO();
		 	  
		 $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $_limit, 'int');
		 $limitstart = $app->input->get('limitstart', '0', 'INT'); 		 
		 $limitstart = $limit != 0 ? (floor($limitstart / $limit) * $limit) : 0; 
		 
		 $this->setState('limit', $limit);
		 $this->setState('limitstart', $limitstart);

         $query = "SELECT v.* FROM #__yendifvideoshare_videos AS v";
		 $query.= " LEFT JOIN #__yendifvideoshare_categories AS c ON v.catid = c.id";
		 $query.= " WHERE v.published=1 AND c.published=1";
		 
		 if( $check_publishing_options ) {
		 	$date = JFactory::getDate();
			
		 	$nullDate = $db->quote( $db->getNullDate() );
		 	$nowDate  = $db->quote( $date->toSql() );
		 
		 	$query .= " AND ( v.published_up = " . $nullDate . " OR v.published_up <= " . $nowDate .' )';
			$query .= " AND ( v.published_down = " . $nullDate . " OR v.published_down >= " . $nowDate .' )';
		 }
		 
		 $this->search_key = $app->getUserStateFromRequest('yendif.search.public', 'search', '', 'string');	
		 $this->search_key = JString::strtolower($this->search_key);
		 $search_key = $db->Quote( '%'.$db->escape( $this->search_key, true ).'%', false );
		 $query.= " AND (CONCAT(v.title,v.description,v.meta_keywords,v.meta_description) LIKE " . $search_key . ' OR c.name LIKE ' . $search_key . ')';
		 
		 $query.= " ORDER BY v.id DESC";
		 
         $db->setQuery( $query, $limitstart, $limit );
         $items = $db->loadObjectList();	
		 		
		 return $items;
		 
    }
	
	public function getTotal( $check_publishing_options ) {
	
         $db = JFactory::getDBO();

         $query  = "SELECT COUNT(v.id) FROM #__yendifvideoshare_videos AS v";
		 $query .= " LEFT JOIN #__yendifvideoshare_categories AS c ON v.catid = c.id";		
		 $query .= " WHERE v.published=1 AND c.published=1";
		 
		 if( $check_publishing_options ) {
		 	$date = JFactory::getDate();
			
		 	$nullDate = $db->quote( $db->getNullDate() );		 
		 	$nowDate  = $db->quote( $date->toSql() );
		 
		 	$query .= " AND ( v.published_up = " . $nullDate . " OR v.published_up <= " . $nowDate .' )';
			$query .= " AND ( v.published_down = " . $nullDate . " OR v.published_down >= " . $nowDate .' )';
		 }		
		  	
		 $search_key = $db->Quote( '%'.$db->escape( $this->search_key, true ).'%', false );
		 $query.= " AND (CONCAT(v.title,v.description,v.meta_keywords,v.meta_description) LIKE " . $search_key . ' OR c.name LIKE ' . $search_key . ')';
		 
         $db->setQuery( $query );
         $total = $db->loadResult();	
		 
		 return $total;
		 
    }
	
	public function getPagination( $check_publishing_options ) {
	
    	 jimport( 'joomla.html.pagination' );
		 
		 $pageNav = new JPagination( $this->getTotal( $check_publishing_options ), $this->getState('limitstart'), $this->getState('limit') );
         return $pageNav;
		 
	}
			
}