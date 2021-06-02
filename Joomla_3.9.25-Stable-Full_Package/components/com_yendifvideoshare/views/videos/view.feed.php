<?php

/*
 * @version		$Id: view.feed.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies PVT Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class YendifVideoShareViewVideos extends YendifVideoShareView {

	public function display( $tpl = null ) {
	
		jimport( 'joomla.application.pathway' );
		
		$app = JFactory::getApplication();	
		$document = JFactory::getDocument();		

		$document->editor = $app->getCfg('fromname');
		$document->editorEmail = $app->getCfg('mailfrom');	
		
		$model = $this->getModel();
		
		$config = YendifVideoShareUtils::getConfig();	
		$params = $app->getParams();
						
        $items = $model->getItems( $config->feed_limit, $params->get('filterby', 'none'), $params->get('orderby'), $config->schedule_video_publishing );
			
		foreach( $items as $item ){
			$title = $this->escape( $item->title );
			$title = html_entity_decode( $title, ENT_COMPAT, 'UTF-8' );
			
			$target_url = YendifVideoShareUtils::buildRoute( $item, 'video' );
			
			$description  = $item->description;				
			$description .= '<img src="'.$item->image.'" />';
						
			$date = $item->created_date ? date( 'r', strtotime( $item->created_date ) ) : '';

			// load individual item creator class
			$feeditem = new JFeedItem();
			
			$feeditem->title		= $title;
			$feeditem->link			= $target_url;				
			$feeditem->description	= $description;
			$feeditem->date			= $date;
			$feeditem->category		= $item->category;								
			
			// loads item info into rss array
			$document->addItem( $feeditem );				
		}	
			
    }	
	
}