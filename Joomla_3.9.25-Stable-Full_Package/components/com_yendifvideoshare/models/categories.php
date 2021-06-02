<?php

/*
 * @version		$Id: categories.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class YendifVideoShareModelCategories extends YendifVideoShareModel {

	public function getItems( $_limit, $orderby ) {
	
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		 
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $_limit, 'int');
		$limitstart = $app->input->get('limitstart', '0', 'INT'); 
		$limitstart = $limit != 0 ? (floor($limitstart / $limit) * $limit) : 0;
 
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
			 
        $query = "SELECT * FROM #__yendifvideoshare_categories WHERE published=1 AND parent=".$app->input->getInt('id', 0);
		 
		switch( $orderby ) {	
		 	case 'latest':
				$query .= ' ORDER BY created_date DESC';
				break;	
			case 'date_added' :
				$query .= ' ORDER BY created_date ASC';
				break;
			case 'a_z' :
				$query .= ' ORDER BY name ASC';
				break;
			case 'z_a' :
				$query .= ' ORDER BY name DESC';
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
	
	public function getTotal() {
				 
		$app = JFactory::getApplication();
        $db = JFactory::getDBO();
		 
        $query = "SELECT COUNT(id) FROM #__yendifvideoshare_categories WHERE published=1 AND parent=".$app->input->getInt('id', 0);	
        $db->setQuery($query);
        $total = $db->loadResult();
		 
		return $total;
		 
	}
	
	public function getPagination() {
    	jimport( 'joomla.html.pagination' );
		 
		$pageNav = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
        return $pageNav;
		 
	}
				
}