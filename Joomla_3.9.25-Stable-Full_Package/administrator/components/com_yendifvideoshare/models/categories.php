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
	
	var $_items = array();
	
	public function getItems() {
	
		$app = JFactory::getApplication();	
		
		$option = $app->input->get('option');
		$view = $app->input->get('view');
		 
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = $app->getUserStateFromRequest($option.$view.'.limitstart', 'limitstart', 0, 'int');
		$limitstart = $limit != 0 ? floor( $limitstart / $limit ) * $limit : 0;
		
		$this->buildList( $this->getParentId(), $spcr = '' );		
		if( ! $limit ) {
			return $this->_items;
		} else {
			return array_slice( $this->_items, $limitstart, $limit );
		}
		
	}
	
	public function getParentId() {
	
		$app = JFactory::getApplication();
		
		$option = $app->input->get('option');
		$view = $app->input->get('view');
		
		$filter_category = $app->getUserStateFromRequest($option.$view.'filter_category', 'filter_category', -1, 'int');		
		if( $filter_category > -1 ) {
			$db = JFactory::getDBO();
			$query = "SELECT parent FROM #__yendifvideoshare_categories WHERE id=" . $filter_category;
			$db->setQuery($query);
			$parent = $db->loadResult();
			
			return $parent;
		}
		
		return 0;	
				
	}
	
	public function buildList( $parent, $spcr = '' ) {	
	
		$db = JFactory::getDBO();
		$app = JFactory::getApplication();
		
		$option = $app->input->get('option');
		$view = $app->input->get('view');

		$filter_state = $app->getUserStateFromRequest($option.$view.'filter_state', 'filter_state', -1, 'int');
		$filter_category = $app->getUserStateFromRequest($option.$view.'filter_category', 'filter_category', -1, 'int');
		$search = $app->getUserStateFromRequest($option.$view.'search', 'search', '', 'string');
		$search = JString::strtolower( $search );
		$set_parent = 1;
		 
		$query = "SELECT * FROM #__yendifvideoshare_categories";				
		$where = array();		 
				 
		if( $filter_state > - 1 ) {
			$where[] = "published=".$filter_state;
			$set_parent = 0;			
		}
		
		if( $filter_category > -1 ) {
			$tree = $this->getCategoryTree( $filter_category );
	    	$where[] = 'id IN ('.implode( ',', $tree ).')';
		 }
		 
		if( $search ) {
			$escaped = $db->escape( $search, true );
			$where[] = 'LOWER(name) LIKE '.$db->Quote( '%'.$escaped.'%', false );
			$set_parent = 0;	
		}
		
		if( $set_parent ) {
			$where[] = "parent=".$parent;
		}	

		$where = count($where) ? ' WHERE '. implode( ' AND ', $where ) : '';		 
		$query .= $where;		 
		$query .= " ORDER BY ordering ASC"; 
		 	    
        $db->setQuery( $query );
   		$cats= $db->loadObjectList();
		
		$c = 0;
		$count = count( $cats );

        if( $count ) {		
            foreach( $cats as $cat ){
				$cat->up = $c == 0 ? 0 : 1;
                $cat->down = ( $c + 1 ) == $count ? 0 : 1;
				$cat->spcr = $spcr."<sup>L</sup>&nbsp;&nbsp;";
		
				$this->_items[] = $cat;
				$c++;
				if( $set_parent ) {
                	$this->buildList( $cat->id, $spcr."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" );
				}
            }
        }
		
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
		 
		 $filter_state = $app->getUserStateFromRequest($option.$view.'filter_state', 'filter_state', -1, 'int');
		 $filter_category = $app->getUserStateFromRequest($option.$view.'filter_category', 'filter_category', -1, 'int');
		 $search = $app->getUserStateFromRequest($option.$view.'search', 'search', '', 'string');
		 $search = JString::strtolower( $search );
		 
         $db = JFactory::getDBO();
         $query = "SELECT COUNT(*) FROM #__yendifvideoshare_categories";
		 $where = array();
		 
		 if( $filter_state > -1 ) {
			$where[] = "published=".$filter_state;
		 }

		if( $filter_category > -1 ) {
			$tree = $this->getCategoryTree($filter_category);
	    	$where[] = 'id IN ('.implode(',', $tree).')';
		 }
		 
		 if( $search ) {
		 	$escaped = $db->escape( $search, true );
			$where[] = 'LOWER(name) LIKE '.$db->Quote( '%'.$escaped.'%', false );
		 }

		 $where = count( $where ) ? ' WHERE '. implode(' AND ', $where) : '';		 
		 $query .= $where;
		 
         $db->setQuery( $query );
         $count = $db->loadResult();
		 
         return $count;
		 
	}
	
	public function getLists() {
	
		 $app = JFactory::getApplication();	
		 
		 $option = $app->input->get('option');
		 $view = $app->input->get('view');
		 
		 $filter_state = $app->getUserStateFromRequest($option.$view.'filter_state','filter_state',-1,'int' );
		 $filter_category = $app->getUserStateFromRequest($option.$view.'filter_category', 'filter_category', -1, 'int');
		 $search = $app->getUserStateFromRequest($option.$view.'search','search','','string');
		 $search = JString::strtolower( $search );
     
    	 $lists = array ();
		 $lists['search'] = $search;
            
		 $filter_state_options[] = JHTML::_('select.option', -1, '-- '.JText::_('YENDIF_VIDEO_SHARE_SELECT_STATUS').' --');
		 $filter_state_options[] = JHTML::_('select.option', 1, JText::_('YENDIF_VIDEO_SHARE_PUBLISHED'));
		 $filter_state_options[] = JHTML::_('select.option', 0, JText::_('YENDIF_VIDEO_SHARE_UNPUBLISHED'));
		 $lists['state'] = JHTML::_('select.genericlist', $filter_state_options, 'filter_state', 'onchange="this.form.submit();"', 'value', 'text', $filter_state);
		 
		 $category_options[] = JHTML::_('select.option', -1, '-- '.JText::_('YENDIF_VIDEO_SHARE_SELECT_CATEGORY').' --');
		 $categories = $this->getCategories();
		 foreach( $categories as $item ) {
			$item->treename = JString::str_ireplace('&#160;', '-', $item->treename);			
			$category_options[] = JHTML::_('select.option', $item->id, $item->treename );
		 }
		 $lists['categories'] = JHTML::_('select.genericlist', $category_options, 'filter_category', 'onchange="this.form.submit();"', 'value', 'text', $filter_category);
		 
         return $lists;
		 
	}
	
	public function getCategories( $name = '' ) {
	
        $db = JFactory::getDBO();
		
		$query = 'SELECT * FROM #__yendifvideoshare_categories';
		if( $name ) {
			$query .= ' WHERE name!=' . $db->quote($name);
		}
		$query .= ' ORDER BY ordering ASC';
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
	
		$app = JFactory::getApplication();
		
		$cid = $app->input->get('cid', array(0), 'ARRAY');
		$id = $cid[0];
		
        $row = JTable::getInstance('Categories', 'YendifVideoShareTable');
        $row->load($id);

        return $row;
		
	}
	
    public function save() {
	
		$app = JFactory::getApplication();
		
		$cid = $app->input->get('cid', array(0), 'ARRAY');
		$id = $cid[0];
		
	  	$row = JTable::getInstance('Categories', 'YendifVideoShareTable');
      	$row->load($id);
	
		$post = $app->input->post->getArray();
      	if( ! $row->bind( $post ) ) {
			$app->enqueueMessage( $row->getError(), 'error' );
	  	}

		$row->name = JString::trim($row->name);
	  	if( ! $row->alias ) $row->alias = $row->name;
		$row->alias = YendifVideoShareUtils::stringURLSafe($row->alias);
		
		if( $row->access == '' && $row->parent > 0 ) {
			$row->access = YendifVideoShareUtils::getColumn('categories', 'access', $row->parent);
		}	  

		$row->reorder( "parent=".$row->parent );
		
	  	if( ! $row->store() ) {
			$app->enqueueMessage( $row->getError(), 'error' );
	  	}

		$task = $app->input->get('task');
	  	switch( $task ) {
        	case 'apply':
            	$msg  = JText::_('YENDIF_VIDEO_SHARE_CHANGES_SAVED');
             	$link = 'index.php?option=com_yendifvideoshare&view=categories&task=edit&'. YendifVideoShareUtils::getToken() .'=1&cid[]='.$row->id;
             	break;
			case 'save2new':
				$msg  = JText::_('YENDIF_VIDEO_SHARE_SAVED');
             	$link = 'index.php?option=com_yendifvideoshare&view=categories&task=add&'. YendifVideoShareUtils::getToken() .'=1';
              	break;
        	case 'save':
        	default:
              	$msg  = JText::_('YENDIF_VIDEO_SHARE_SAVED');
              	$link = 'index.php?option=com_yendifvideoshare&view=categories';
              	break;
      	}

	  	$app->redirect($link, $msg, 'message');
		
	}

    public function cancel() {
	
		$app = JFactory::getApplication();
		 
		$link = 'index.php?option=com_yendifvideoshare&view=categories';
	    $app->redirect($link);
		
    }
	
	public function delete() {
	
		$db = JFactory::getDBO();
		$app = JFactory::getApplication();
		
		$cid = $app->input->get('cid', array(), 'ARRAY');        
        $cids = implode(',', $this->getCategoryTree($cid));
		
        if( count( $cid ) ) {
            $query = "DELETE FROM #__yendifvideoshare_categories WHERE id IN ($cids)";
            $db->setQuery( $query );
            if( ! $db->query() ) {
                echo "<script>alert('".$db->getErrorMsg()."'); window.history.go(-1);</script>\n";
            }
        }
		
        $app->redirect('index.php?option=com_yendifvideoshare&view=categories');
			
	}

    public function publish() {
	
		$app = JFactory::getApplication();
		$cid = $app->input->get('cid', array(), 'ARRAY');
        $publish = $app->input->get('task') == 'publish' ? 1 : 0;
			
        $row = JTable::getInstance('Categories', 'YendifVideoShareTable');
        $row->publish($cid, $publish);
        $app->redirect('index.php?option=com_yendifvideoshare&view=categories');
			        
    }

	public function order( $inc ) {
	
		$app = JFactory::getApplication();	
		
		$cid = $app->input->get('cid', array(0), 'ARRAY');
		$id = $cid[0];
		
    	$row = JTable::getInstance('Categories', 'YendifVideoShareTable');
		$row->load($id);
		$row->move($inc, 'parent='.$row->parent);
		
		$app->redirect( 'index.php?option=com_yendifvideoshare&view=categories', JText::_('YENDIF_VIDEO_SHARE_NEW_ORDERING_SAVED'), 'message');
		
    }
	
    public function saveOrder() {
	
		$app = JFactory::getApplication();
		
		$cid = $app->input->get('cid', array(0), 'ARRAY');
		$total = count( $cid );
		
		$order = $app->input->get('order', array(0), 'ARRAY');
		JArrayHelper::toInteger($order, array(0));
		 
		$row = JTable::getInstance('Categories', 'YendifVideoShareTable');
		$groupings = array();
		for( $i=0; $i < $total; $i++ ) {
			$row->load( (int) $cid[$i] );
			$groupings[] = $row->parent;
 			if( $row->ordering != $order[$i] ) {
				$row->ordering  = $order[$i];
				if( ! $row->store() ) {
					$app->enqueueMessage( $row->getError(), 'error' );
				}
			}
		} 
		$groupings = array_unique( $groupings );
	
		foreach( $groupings as $group ) {
			$row->reorder('parent="'.$group.'"');
		}
 
		$app->redirect('index.php?option=com_yendifvideoshare&view=categories', JText::_('YENDIF_VIDEO_SHARE_NEW_ORDERING_SAVED'), 'message');  
		              
    }
	
	public function getCategoryTree( $ids ) {
	
		$db = JFactory::getDBO();	
				
		$ids = (array) $ids;
		JArrayHelper::toInteger($ids);
		$catid  = array_unique($ids);
		sort($ids);
		
		$array = $ids;				
		while( count( $array ) ){
			$query = "SELECT id FROM #__yendifvideoshare_categories WHERE parent IN (".implode(',', $array).") AND id NOT IN (".implode(',', $array).") ";
			$db->setQuery($query);
			$array = $db->loadColumn();
			$ids = array_merge($ids, $array);
		}
		JArrayHelper::toInteger($ids);
		$ids = array_unique($ids);			
			
		return $ids;
		
	}
	
	public function recreate() {
	
		$db = JFactory::getDBO();
		$app = JFactory::getApplication();
		
        $cid = $app->input->get('cid', array(), 'ARRAY');
        $cids = implode(',', $cid);
		
        if( count( $cid ) ) {			
			$query = "SELECT image FROM #__yendifvideoshare_categories WHERE id IN ( $cids )";
            $db->setQuery($query);
			$items = $db->loadObjectList();	

			$uploader = YendifVideoShareUpload::getInstance();
			foreach( $items as $item ) {
				$uploader->doRecreateImages($item->image, 'categories');
			}
        }
		
        $app->redirect('index.php?option=com_yendifvideoshare&view=categories', JText::_('YENDIF_VIDEO_SHARE_IMAGES_RECREATED_SUCCESSFULLY'), 'message');
		
	}

}