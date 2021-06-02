<?php

/*
 * @version		$Id: adverts.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access'); 

class YendifVideoShareModelAdverts extends YendifVideoShareModel {
	
	public function getItems() {
			
		$app = JFactory::getApplication();	
				
		$option = $app->input->get('option');
		$view = $app->input->get('view');
		 
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = $app->getUserStateFromRequest($option.$view.'.limitstart', 'limitstart', 0, 'int');
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);	    
		$filter_state = $app->getUserStateFromRequest($option.$view.'filter_state', 'filter_state', -1, 'int');
		$filter_type = $app->getUserStateFromRequest($option.$view.'filter_type', 'filter_type', '', 'string');
		$search = $app->getUserStateFromRequest($option.$view.'search', 'search', '', 'string');
		$search = JString::strtolower($search);
		 
	    $db = JFactory::getDBO();
        $query = "SELECT * FROM #__yendifvideoshare_adverts";
		$where = array();
		
		$where[] = "title <> ''";
		
		if( $filter_state > -1 ) {
			$where[] = "published=".$filter_state;
		}
		
		if( $filter_type ) {
			$where[] = "type=".$db->quote($filter_type);
		}
		
		if( $search ) {			
		 	$escaped = $db->escape( $search, true );
			$searchKey = $db->Quote( '%'.$escaped.'%', false );
			$where[] = 'LOWER(title) LIKE '. $searchKey;
		}	

		$where = ( count($where) ? ' WHERE '. implode(' AND ', $where) : '' );
		 
		$query .= $where;		
		$query .= " ORDER BY id DESC";									
				
        $db->setQuery( $query, $limitstart, $limit );
        $items = $db->loadObjectList();
						
        return $items;
		
	}
	
	public function getTotal() {
	
		$app = JFactory::getApplication();
					
		$option = $app->input->get('option');
		$view = $app->input->get('view');		 		
		
		$filter_state = $app->getUserStateFromRequest($option.$view.'filter_state', 'filter_state', -1, 'int');
		$filter_type = $app->getUserStateFromRequest($option.$view.'filter_type', 'filter_type', '', 'string');
		$search = $app->getUserStateFromRequest($option.$view.'search', 'search', '', 'string');
		$search = JString::strtolower($search);
		 
        $db = JFactory::getDBO();
        $query = "SELECT COUNT(id) FROM #__yendifvideoshare_adverts";
		$where = array();
		 
		$where[] = "title <> ''";
		
		if( $filter_state > -1 ) {
			$where[] = "published=".$filter_state;
		}
		
		if( $filter_type ) {
			$where[] = "type=".$db->quote($filter_type);
		}
		
		if( $search ) {
		 	$escaped = $db->escape( $search, true );
			$searchKey = $db->Quote( '%'.$escaped.'%', false );
			$where[] = 'LOWER(title) LIKE '. $searchKey;
		}

		$where = ( count($where) ? ' WHERE '. implode(' AND ', $where) : '' );
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
        return($pageNav);
		
	}
	
	public function getLists() {
	
		$app = JFactory::getApplication();	
				
		$option = $app->input->get('option');
		$view = $app->input->get('view');
		 
		$filter_state = $app->getUserStateFromRequest($option.$view.'filter_state', 'filter_state', -1, 'int' );
		$filter_type = $app->getUserStateFromRequest($option.$view.'filter_type', 'filter_type', '', 'string' );
		$search = $app->getUserStateFromRequest($option.$view.'search','search','','string');
		$search = JString::strtolower($search);		
		     
    	$lists = array ();
		$lists['search'] = $search;
            
		$filter_type_options[] = JHTML::_('select.option', '', '-- '.JText::_('YENDIF_VIDEO_SHARE_SELECT_TYPE').' --');
		$filter_type_options[] = JHTML::_('select.option', 'preroll', JText::_('YENDIF_VIDEO_SHARE_PREROLL'));
		$filter_type_options[] = JHTML::_('select.option', 'postroll', JText::_('YENDIF_VIDEO_SHARE_POSTROLL'));
		$lists['type'] = JHTML::_('select.genericlist', $filter_type_options, 'filter_type', 'onchange="this.form.submit();"', 'value', 'text', $filter_type);
		
		$filter_state_options[] = JHTML::_('select.option', -1, '-- '.JText::_('YENDIF_VIDEO_SHARE_SELECT_STATUS').' --');
		$filter_state_options[] = JHTML::_('select.option', 1, JText::_('YENDIF_VIDEO_SHARE_PUBLISHED'));
		$filter_state_options[] = JHTML::_('select.option', 0, JText::_('YENDIF_VIDEO_SHARE_UNPUBLISHED'));
		$lists['state'] = JHTML::_('select.genericlist', $filter_state_options, 'filter_state', 'onchange="this.form.submit();"', 'value', 'text', $filter_state);
				 
        return $lists;
		
	}
	
	public function getItem() {
	
		$app = JFactory::getApplication();
		
		$cid = $app->input->get('cid', array(0), 'ARRAY');
		$id = $cid[0];
		
        $row = JTable::getInstance('Adverts', 'YendifVideoShareTable');
        $row->load($id);

        return $row;
		
	}
	
	public function save() {
	
		$app = JFactory::getApplication();
		
		$cid = $app->input->get('cid', array(0), 'ARRAY');
		$id = $cid[0];
		
	  	$row = JTable::getInstance('Adverts', 'YendifVideoShareTable');		
      	$row->load($id);
	
		$post = $app->input->post->getArray();
      	if( ! $row->bind( $post ) ) {
			$app->enqueueMessage( $row->getError(), 'error' );
	  	}
	  
	   	jimport( 'joomla.filter.output' );
		$row->title = YendifVideoShareUtils::safeString( $row->title );
			
	  	if( ! $row->store() ) {
			$app->enqueueMessage( $row->getError(), 'error' );
	  	}
		
		$task = $app->input->get('task');
	  	switch( $task ) {
        	case 'apply':
            	$msg  = JText::_('YENDIF_VIDEO_SHARE_CHANGES_SAVED');
             	$link = 'index.php?option=com_yendifvideoshare&view=adverts&task=edit&'. YendifVideoShareUtils::getToken() .'=1&'.'cid[]='.$row->id;				
             	break;
			case 'save2new':
				$msg  = JText::_('YENDIF_VIDEO_SHARE_SAVED');
             	$link = 'index.php?option=com_yendifvideoshare&view=adverts&task=add&'. YendifVideoShareUtils::getToken() .'=1';
              	break;
        	case 'save':
        	default:
				$msg  = JText::_('YENDIF_VIDEO_SHARE_SAVED');
             	$link = 'index.php?option=com_yendifvideoshare&view=adverts';
              	break;
      	}
		 
		$app->redirect($link, $msg, 'message'); 	
		 
	}
	
	public function cancel() {
	
		$app = JFactory::getApplication();
		 
		$link = 'index.php?option=com_yendifvideoshare&view=adverts';
	    $app->redirect($link);
		
	}	

	public function delete() {
	
		$db = JFactory::getDBO();
		$app = JFactory::getApplication();
				
        $cid = $app->input->get('cid', array(), 'ARRAY');        
        $cids = implode(',', $cid);
		
        if( count( $cid ) ) {
            $query = "DELETE FROM #__yendifvideoshare_adverts WHERE id IN ($cids)";
            $db->setQuery( $query );
            if (!$db->query()) {
                echo "<script> alert('".$db->getErrorMsg()."');window.history.go(-1); </script>\n";
            } else {
				jimport('joomla.filesystem.folder');
				$n = count($cid);				
				for( $i = 0; $i < $n; $i++ ) {
					$delDir = YENDIF_VIDEO_SHARE_UPLOAD_BASE.'adverts'.DIRECTORY_SEPARATOR.$cid[$i];
					if( JFolder::exists( $delDir ) ) JFolder::delete( $delDir );
				}
			}			
        }
		
        $app->redirect('index.php?option=com_yendifvideoshare&view=adverts');
		
	}
	
	public function publish() {
	
		$app = JFactory::getApplication();
		
		$cid = $app->input->get('cid', array(), 'ARRAY');
        $publish = $app->input->get('task') == 'publish' ? 1 : 0;
			
        $row = JTable::getInstance('Adverts', 'YendifVideoShareTable');
        $row->publish($cid, $publish);
        $app->redirect('index.php?option=com_yendifvideoshare&view=adverts');
		
    }
	
}