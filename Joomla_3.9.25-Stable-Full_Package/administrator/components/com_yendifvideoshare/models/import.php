<?php

/*
 * @version		$Id: import.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class YendifVideoShareModelImport extends YendifVideoShareModel {
	
	public function getLists() {
	
    	$lists = array();
          
		// publishing state  
		$options = array();
		$options[] = JHTML::_('select.option', '', '-- '.JText::_('YENDIF_VIDEO_SHARE_SELECT_STATUS').' --');
		$options[] = JHTML::_('select.option', 1, JText::_('YENDIF_VIDEO_SHARE_PUBLISHED'));
		$options[] = JHTML::_('select.option', 0, JText::_('YENDIF_VIDEO_SHARE_UNPUBLISHED'));
		$lists['state'] = JHTML::_('select.genericlist', $options, 'import_state', 'class="required yendif-no-margin"', 'value', 'text');
		 
		// categories list  
		$options = array();
		$options[] = JHTML::_('select.option', '', '-- '.JText::_('YENDIF_VIDEO_SHARE_SELECT_CATEGORY').' --');
		$categories = $this->getCategories();
		foreach( $categories as $item ) {
			$item->treename = JString::str_ireplace('&#160;', '-', $item->treename);
			$options[] = JHTML::_('select.option', $item->id, $item->treename );
		}
		$lists['categories'] = JHTML::_('select.genericlist', $options, 'import_category', 'class="required yendif-no-margin"', 'value', 'text');
		 
		// return
        return $lists;
		
	}
	
	public function getCategories() {
	
        $db = JFactory::getDBO();
		
		$query = 'SELECT * FROM #__yendifvideoshare_categories ORDER BY ordering ASC';
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
	
	public function saveApiKey() {
	
	  	$app = JFactory::getApplication();
		$db  = JFactory::getDBO();
		
		$api_key = $app->input->get('google_api_key');
				 
	  	$query = "UPDATE #__yendifvideoshare_config SET google_api_key=".$db->Quote( $api_key )." WHERE id=1";
    	$db->setQuery ( $query );
		$db->query();

      	$msg = JText::_('YENDIF_VIDEO_SHARE_CHANGES_SAVED');
      	$link = 'index.php?option=com_yendifvideoshare&view=import';
  
	  	$app->redirect($link, $msg, 'message');
		
	}
	
	public function insertVideos( $ids, $titles ) {
	
		 jimport( 'joomla.filter.output' );	
		 	 
		 $db = JFactory::getDBO();
		 $app = JFactory::getApplication();
		 
		 // vars
		 $catid    = $app->input->getInt( 'import_category' );
		 $userid   = JFactory::getUser()->get('id');
		 $status   = $app->input->getInt( 'import_state' );
		 $messages = array();
		 
		 // insert videos
		 foreach( $ids as $key => $id ) {	
			 
			 $sql = "SELECT COUNT(id) from #__yendifvideoshare_videos where type=".$db->Quote( 'youtube' )." and youtube LIKE ".$db->Quote( '%'.$id )." LIMIT 1";
			 $db->setQuery( $sql );
			 $count = $db->loadResult();
			 
			 if( $count > 0 ) {
			 
			 	$messages[] = '<span><i class="icon-cancel"></i> '.JText::sprintf( 'YENDIF_VIDEO_SHARE_ALREADY_EXISTS', $titles[ $key ] )."</span>";
			 
			 } else {
			 
				$title = YendifVideoShareUtils::safeString( str_replace( "'", "\'", $titles[ $key ] ) );
				 
				$row = new JObject();
				
   				$row->id        = NULL;
				$row->title     = $title;
   				$row->alias     = YendifVideoShareUtils::stringURLSafe( $title );
				$row->catid     = $catid;
				$row->type      = 'youtube';
				$row->youtube   = "https://www.youtube.com/watch?v=$id";
				$row->image     = "https://img.youtube.com/vi/$id/0.jpg";	
				$row->userid    = $userid;
				$row->published = $status;
				$row->preroll 	= '-1';
				$row->postroll	= '-1';
					
   				$db->insertObject( '#__yendifvideoshare_videos', $row );
				
				$messages[] = '<span><i class="icon-ok"></i> '.JText::sprintf( 'YENDIF_VIDEO_SHARE_IMPORTED_SUCCESSFULLY', $titles[ $key ] )."</span>";
				 
			 }
			 
		 }
		 
		 echo implode( '<br>', $messages );
		 exit;
		 
	}
	
}