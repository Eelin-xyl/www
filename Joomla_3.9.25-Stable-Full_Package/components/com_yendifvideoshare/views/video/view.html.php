<?php

/*
 * @version		$Id: view.html.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies PVT Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Import libraries
require_once( JPATH_ROOT.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_yendifvideoshare'.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'player.php' );

class YendifVideoShareViewVideo extends YendifVideoShareView {

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
		
		$this->show_likes_dislikes = $this->params->get('show_likes_dislikes', $this->config->show_likes);
		if( $this->show_likes_dislikes == 'global' ) {
			$this->show_likes_dislikes = $this->config->show_likes;						
		}
		
		$this->ratio = $this->params->get('ratio', $this->config->ratio);
		
		$this->item = $model->getItem( $this->config->schedule_video_publishing );
		
		if( empty( $this->item ) ) {
			$app->enqueueMessage( JText::_('YENDIF_VIDEO_SHARE_ITEM_NOT_FOUND'), 'notice' );
			return true;
		}
		
		$playerObj = YendifVideoSharePlayer::getInstance( $this->config );		
		$player_params = (array) json_decode( $this->params );
		$player_params['videoid'] = $app->input->getInt('id');		
		if( $app->input->get('tmpl') == 'component' ) {
			echo $playerObj->embedPlayer( $player_params );
			exit();
		} else {
			//$this->player = $playerObj->singlePlayer( $player_params );
			$this->player = $playerObj->build( $player_params );
		}
		
		$filterby = $this->params->get('filterby', 'none');
		$orderby  = $this->params->get('orderby');
		
		$this->videos = $model->getVideos( $this->rows * $this->cols, $this->item->catid, $filterby, $orderby, $this->config->schedule_video_publishing );
		
		$this->pagination = $model->getPagination( $this->item->catid, $filterby, $this->config->schedule_video_publishing );
		
		$this->category = $model->getCategory( $this->item->catid );
		
		$this->access = YendifVideoShareUtils::hasPermission( $this->item->access );
		
		if( $this->access && $this->show_rating ) {
			$total_rated_users = $model->getTotalRatedUsers();			
			$this->rating_widget = YendifVideoShareUtils::RatingWidget( $this->item->rating, $this->item->id, $total_rated_users );
		};				
		
		$this->setHeader();
		$this->addBreadcrumbs();
				
        parent::display( $tpl );
		
    }
	
	private function setHeader() {
	
		JHtml::_('jquery.framework');
		
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		
		$document->setTitle( $document->getTitle() . ' - ' . $this->item->title );

		$description = '';
		if( $this->params->get('menu-meta_description') ) $description = $this->params->get('menu-meta_description');
		if( ! empty( $this->item->meta_description ) ) $description = $this->item->meta_description;
		if( empty( $description ) && ! empty( $this->item->description ) ) $description = YendifVideoShareUtils::Truncate( $this->item->description );
		if( ! empty( $description ) ) $document->setDescription( $description );
		
		if( $this->params->get('menu-meta_keywords') ) $document->setMetadata( 'keywords', $this->params->get('menu-meta_keywords') );
		if( ! empty( $this->item->meta_keywords ) ) $document->setMetaData( 'keywords' , $this->item->meta_keywords );
		
		if( $this->params->get('robots') ) $document->setMetadata( 'robots', $this->params->get('robots') );
		
		$doc_data = $document->getHeadData();
		$url        = JURI::root();
		$sch        = parse_url( $url, PHP_URL_SCHEME );
		$server     = parse_url( $url, PHP_URL_HOST );
		$canonical_url = YendifVideoShareUtils::buildRoute( $this->item, 'video' ); 
		$newtag     = '<link rel="canonical" href="'.$sch.'://'.$server.$canonical_url.'"/>';

		$replaced = false;
		foreach( $doc_data['custom'] as $key => $c ) {
    		if( strpos( $c, 'rel="canonical"' ) !== FALSE ) {
        		$doc_data['custom'][ $key ] = $newtag;
        		$replaced = true;
    		}
		}
		
		if( ! $replaced ) {
    		$doc_data['custom'][] = $newtag;
		}

		$document->setHeadData( $doc_data );

		if( $this->config->bootstrap_version == 3 ) {
			$document->addStyleSheet( YendifVideoShareUtils::prepareURL('media/yendifvideoshare/assets/site/css/bootstrap.css','text/css','screen' ));
		}
		if( $this->enable_popup ) {
			$document->addStyleSheet( YendifVideoShareUtils::prepareURL('media/yendifvideoshare/assets/site/css/magnific-popup.css', 'text/css','screen' ));
		}
		$document->addStyleSheet( YendifVideoShareUtils::prepareURL( 'media/yendifvideoshare/assets/site/css/yendifvideoshare.css','text/css','screen' ));
		if( ! empty( $this->config->responsive_css ) ) {
			$document->addStyleDeclaration( $this->config->responsive_css );
		}
		
		if( $this->enable_popup ) {
			$document->addScript( YendifVideoShareUtils::prepareURL('media/yendifvideoshare/assets/site/js/jquery.magnific-popup.min.js' ) );
		}
		$document->addScript( YendifVideoShareUtils::prepareURL('media/yendifvideoshare/assets/site/js/yendifvideoshare.js' ));
		if( $this->config->comments == 'facebook' ) {
			$document->addCustomTag("
				<script type='text/javascript'>
					(function(d, s, id) {
	  					var js, fjs = d.getElementsByTagName(s)[0];
	  					if (d.getElementById(id)) return;
	  					js = d.createElement(s); js.id = id;
	  					js.src = '//connect.facebook.net/".JText::_('YENDIF_VIDEO_SHARE_FACEBOOK_LANGUAGE')."/all.js#appId=".$this->config->fb_app_id."&xfbml=1';
	 					fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk')); 
				</script> 
			");
			
			if( $this->config->fb_app_id ) $document->addCustomTag( '<meta property="fb:app_id" content="'.$this->config->fb_app_id.'">' );
		};
	}
	
	private function addBreadcrumbs() {
	
		jimport( 'joomla.application.pathway' );
		
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();

		$breadcrumbs = $app->getPathway();
		$crumbs = array();
		$index = 0;	
		
		// hack to force the correct sef url for the component menu
		$brd = $breadcrumbs->getPathway();
		if( ! empty( $brd[0]->link ) ) {
			$brd[0]->link = preg_replace( '/&?option=com_yendifvideoshare/', '', $brd[0]->link );
			$brd[0]->link = preg_replace( '/&?view=video/', '', $brd[0]->link );
			$breadcrumbs->setPathway( $brd );
		}
		// end hack
		
		if( $this->category ) {
			if( $this->category->parent != 0 ) {
				$query = 'SELECT * FROM #__yendifvideoshare_categories WHERE id = '.$this->category->parent;
				$db->setquery( $query );
				$parent_category = $db->loadObject();			
						
				if( $parent_category->parent != 0 ) {
					$query = 'SELECT * FROM #__yendifvideoshare_categories WHERE id = '.$parent_category->parent;
					$db->setquery($query);
					$top_category = $db->loadObject();
					
					$crumbs[$index][0] = $top_category->name;
					$crumbs[$index][1] = YendifVideoShareUtils::buildRoute( $top_category, 'category' );
					$index++;	
				}
				$crumbs[$index][0] = $parent_category->name;
				$crumbs[$index][1] = YendifVideoShareUtils::buildRoute( $parent_category, 'category' );
				$index++;
			}
			
			$crumbs[$index][0] = $this->category->name;		
			$crumbs[$index][1] = YendifVideoShareUtils::buildRoute( $this->category, 'category' );
			$index++;
		}
		
		$crumbs[$index][0] = $this->item->title;
		$crumbs[$index][1] = YendifVideoShareUtils::buildRoute( $this->item, 'video' );	

		for( $i = 0, $n = count( $crumbs ); $i < $n; $i++ ) {
			$breadcrumbs->addItem( $crumbs[$i][0], $crumbs[$i][1] );
		}
    }
	
}