<?php

/*
 * @version		$Id: view.html.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class YendifVideoShareViewSearch extends YendifVideoShareView {

    public function display( $tpl = null ) {
	
		$app = JFactory::getApplication();
		
		$model = $this->getModel();
		
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
		
		$this->ratio = $this->params->get('ratio', $this->config->ratio);
		
		$this->items = $model->getItems( $this->rows * $this->cols, $this->config->schedule_video_publishing );
		$this->search_key = $model->search_key;
		$this->pagination = $model->getPagination( $this->config->schedule_video_publishing );		
		$this->rss_feed = $this->config->feed_search == 1 ? $this->getFeedLink() : '';
		
		$this->setHeader();
		
        parent::display( $tpl );
		
    }
	
	private function setHeader() {
	
		JHtml::_('jquery.framework');
		
		$document = JFactory::getDocument();

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
			$document->addScript( YendifVideoShareUtils::prepareURL('media/yendifvideoshare/assets/site/js/jquery.magnific-popup.min.js' ));
		}
		$document->addScript( YendifVideoShareUtils::prepareURL('media/yendifvideoshare/assets/site/js/yendifvideoshare.js' ));
		
	}
	
	private function getFeedLink(){			
	
		$url = JRoute::_( 'index.php?option=com_yendifvideoshare&view=search&format=feed&type=rss' );
		return '<a class="rss_icon" href="'.$url.'" target="_blank"><img src="'.$this->config->feed_icon.'" /></a>';
				
	}
	
}