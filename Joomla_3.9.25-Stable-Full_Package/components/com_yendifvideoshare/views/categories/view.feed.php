<?php

/*
 * @version		$Id: view.html.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class YendifVideoShareViewCategories extends YendifVideoShareView {

	public function display( $tpl = null ) {
	
		jimport( 'joomla.application.pathway' );
		
		$app = JFactory::getApplication();	
		$db = JFactory::getDBO();	
		$document = JFactory::getDocument();	
			
		$document->editor = $app->getCfg('fromname');
		$document->editorEmail = $app->getCfg('mailfrom');	
				
		$model = $this->getModel();
		
		$config = YendifVideoShareUtils::getConfig();	
		$params = $app->getParams();
				
		$query  = 'SELECT v.*, c.name as category FROM #__yendifvideoshare_videos AS v';
		$query .= ' LEFT JOIN #__yendifvideoshare_categories AS c ON v.catid = c.id';
		$query .= ' WHERE v.published=1';
		
		if( $config->schedule_video_publishing ) {
			$date = JFactory::getDate();
			
		 	$nullDate = $db->quote( $db->getNullDate() );
			$nowDate  = $db->quote( $date->toSql() );
		 
		 	$query .= " AND ( v.published_up = " . $nullDate . " OR v.published_up <= " . $nowDate .' )';
			$query .= " AND ( v.published_down = " . $nullDate . " OR v.published_down >= " . $nowDate . ' )';	
		}
			
		$catid = $app->input->getInt('id');		
		if( $catid > 0 ) {
		 	$query .= ' AND v.catid='.$catid;
		 }
		 
		$filterby = $params->get('filterby', 'none');
		if( $filterby == 'featured' ) {
		 	$query .= ' AND v.featured=1';
		}
		 
		$orderby = $params->get('orderby');
		switch( $orderby ) {
			case 'most_viewed' :
				$query .= ' ORDER BY v.views DESC';
				break;
			case 'most_rated' :
				$query .= ' ORDER BY v.rating DESC';
				break;
			case 'data_added' :
				$query .= ' ORDER BY v.id ASC';
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
				$query .= ' ORDER BY v.ordering';
				break;
			default :
				$query .= ' ORDER BY v.id DESC';
		}
		 
		$query .= " LIMIT $config->feed_limit";		
        $db->setQuery( $query );
		
        $items = $db->loadObjectList();	
				 
		foreach( $items as $item ) {
			$title = $this->escape( $item->title );
			$title = html_entity_decode( $title, ENT_COMPAT, 'UTF-8' );
			
			$target_url = YendifVideoShareUtils::buildRoute( $item, 'video' );
			
			$description  = $item->description;				
			$description .= '<img src="'.$item->image.'" />';
										
			$date = $item->created_date ? date( 'r', strtotime( $item->created_date ) ) : '';
							
			// load individual item creator class
			$feeditem = new JFeedItem();
			
			$feeditem->title	   = $title;
			$feeditem->link		   = $target_url;				
			$feeditem->description = $description;
			$feeditem->date		   = $date;
			$feeditem->category	   = $item->category;		
										
			// loads item info into rss array
			$document->addItem( $feeditem );									
		}				
		
    }
	
}