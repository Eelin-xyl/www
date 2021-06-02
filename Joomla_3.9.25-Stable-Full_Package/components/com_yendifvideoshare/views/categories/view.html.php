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
	
	    $app = JFactory::getApplication();
		
		$model = $this->getModel();
				
		$this->config = YendifVideoShareUtils::getConfig();
		$this->params = $app->getParams();
		$this->rows   = $this->params->get('no_of_rows', $this->config->no_of_rows);
		$this->cols   = $this->params->get('no_of_cols', $this->config->no_of_cols);
		
		$this->show_excerpt = $this->params->get('show_excerpt', $this->config->show_excerpt);
		$this->excerpt_length = $this->config->playlist_desc_limit;
		
		$this->show_videos_count = $this->params->get('show_videos_count', $this->config->show_media_count);
		if( $this->show_videos_count == 'global' ) {
			$this->show_videos_count = $this->config->show_media_count;
		}
				
		$this->items = $model->getItems( $this->rows * $this->cols, $this->params->get('orderby') );
		
		if( ! count( $this->items ) ) {
			$app->enqueueMessage( JText::_('YENDIF_VIDEO_SHARE_ITEM_NOT_FOUND'), 'notice' );
			return true;
		}
		
		$this->pagination = $model->getPagination();
		
		$menu = $app->getMenu()->getActive();
		$this->menu_title = $menu->title;
		
		$show_feed = $this->params->get('show_feed', $this->config->show_feed);
		if( $show_feed == 'global' ) {
			$show_feed = $this->config->show_feed;
		}
		$this->rss_feed = $show_feed ? $this->getFeedLink() : '';
		
		$this->setHeader();
				
        parent::display( $tpl );
		
    }
	
	private function setHeader() {
	
		$document = JFactory::getDocument();
		
		if( $this->params->get('menu-meta_description') ) $document->setDescription( $this->params->get('menu-meta_description') );
		if( $this->params->get('menu-meta_keywords') ) $document->setMetadata( 'keywords', $this->params->get('menu-meta_keywords') );
		if( $this->params->get('robots') ) $document->setMetadata( 'robots', $this->params->get('robots') );
				
		if( $this->config->bootstrap_version == 3 ) {
			$document->addStyleSheet( JURI::root( true ) . "/media/yendifvideoshare/assets/site/css/bootstrap.css?v=1.2.6",'text/css',"screen" );
		}
		$document->addStyleSheet( JURI::root( true ) . "/media/yendifvideoshare/assets/site/css/yendifvideoshare.css?v=1.2.6",'text/css',"screen" );
		if( ! empty( $this->config->responsive_css ) ) {
			$document->addStyleDeclaration( $this->config->responsive_css );
		}
		
	}
	
	private function getFeedLink() {
		
		$app = JFactory::getApplication();
		
		$catid = $app->input->getInt('id');		
		$url   = JRoute::_( 'index.php?option=com_yendifvideoshare&view=categories&format=feed&type=rss' );
		
		return '<a class="rss_icon" href="'.$url.'" target="_blank"><img src="'.$this->config->feed_icon.'" /></a>';
				
	}		
	
}