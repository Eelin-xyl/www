<?php 

/*
 * @version		$Id: view.html.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies PVT Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/ 

// no direct access
defined('_JEXEC') or die('Restricted access');

class YendifVideoShareViewVideos extends YendifVideoShareView {

    public function display( $tpl = null ) {
	
	    $app = JFactory::getApplication();
		$model= $this->getModel();
		
		$this->config = YendifVideoShareUtils::getConfig();
		$this->params = $app->getParams();
		$this->rows   = $this->params->get('no_of_rows', $this->config->no_of_rows);
		$this->cols   = $this->params->get('no_of_cols', $this->config->no_of_cols);
		
		$this->show_excerpt = $this->params->get('show_excerpt', $this->config->show_excerpt);
		$this->excerpt_length = $this->config->playlist_desc_limit;
		
		$this->show_views = $this->params->get('show_views', $this->config->show_views);
		if( $this->show_views == 'global' ) {
			$this->show_views = $this->config->show_views;
		}
		
		$this->show_rating = $this->params->get('show_rating', $this->config->show_rating);
		if( $this->show_rating == 'global' ) {
			$this->show_rating = $this->config->show_rating;
		}
		
		$this->enable_popup = $this->params->get('enable_popup', $this->config->enable_popup);
		if( $this->enable_popup == 'global' ) {
			$this->enable_popup = $this->config->enable_popup;	
		}
		
		$this->show_likes_dislikes = $this->params->get('show_likes_dislikes',  $this->config->show_likes);
		if( $this->show_likes_dislikes == 'global' ) {
			$this->show_likes_dislikes = $this->config->show_likes;						
		}

		$this->ratio = $this->params->get('ratio', $this->config->ratio);
		
		$filterby = $this->params->get('filterby', 'none');
		$orderby  = $this->params->get('orderby');
		
		$this->items = $model->getItems( $this->rows * $this->cols, $filterby, $orderby, $this->config->schedule_video_publishing );
		
		if( ! count( $this->items ) ) {
			$app->enqueueMessage( JText::_('YENDIF_VIDEO_SHARE_ITEM_NOT_FOUND'), 'notice' );
			return true;
		}
		
		$this->pagination = $model->getPagination( $filterby, $this->config->schedule_video_publishing );
		
		$menu = $app->getMenu()->getActive();
		$this->menu_title = $menu->title;
		
		$show_feed = $this->params->get('show_feed', $this->config->show_feed);
		if( $show_feed == 'global' ) {
			$show_feed = $this->config->show_feed;						
		}
		$this->rss_feed = $show_feed == 1 ? $this->getFeedLink() : '';
		
		$this->setHeader();
				
        parent::display($tpl);
		
    }
	
	private function setHeader() {
	
		JHtml::_('jquery.framework');
		
		$document = JFactory::getDocument();
		
		if( $this->params->get('menu-meta_description') ) $document->setDescription( $this->params->get('menu-meta_description') );
		if( $this->params->get('menu-meta_keywords') ) $document->setMetadata( 'keywords', $this->params->get('menu-meta_keywords') );
		if( $this->params->get('robots') ) $document->setMetadata( 'robots', $this->params->get('robots') );

		if( $this->config->bootstrap_version == 3 ) {
			$document->addStyleSheet( YendifVideoShareUtils::prepareURL('media/yendifvideoshare/assets/site/css/bootstrap.css','text/css','screen' ));
		}
		if( $this->enable_popup ) {
			$document->addStyleSheet( YendifVideoShareUtils::prepareURL('media/yendifvideoshare/assets/site/css/magnific-popup.css', 'text/css','screen' ));
		}
		$document->addStyleSheet( YendifVideoShareUtils::prepareURL( 'media/yendifvideoshare/assets/site/css/yendifvideoshare.css','text/css','screen' ));
		if( ! empty ( $this->config->responsive_css ) ) {
			$document->addStyleDeclaration( $this->config->responsive_css );
		}
		
		if( $this->enable_popup ) {
			$document->addScript( YendifVideoShareUtils::prepareURL('media/yendifvideoshare/assets/site/js/jquery.magnific-popup.min.js' ) );
		}
		$document->addScript( YendifVideoShareUtils::prepareURL('media/yendifvideoshare/assets/site/js/yendifvideoshare.js' ));
		
	}
	
	private function getFeedLink(){			
	
		$url = JRoute::_( 'index.php?option=com_yendifvideoshare&view=videos&format=feed&type=rss' );
		return '<a class="rss_icon" href="'.$url.'" target="_blank"><img src="'.$this->config->feed_icon.'"  /></a>';
				
	}
		
}