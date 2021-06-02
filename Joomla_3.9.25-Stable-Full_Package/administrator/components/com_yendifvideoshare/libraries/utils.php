<?php

/*
 * @version		$Id: utils.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies PVT Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class YendifVideoShareUtils  {
	
	public static function hasPermission( $access = 1, $viewLevels = '' ) {
	
		if($access == '') return true;
		
		if( $viewLevels == '' ) {
			$user = JFactory::getUser();	
			$viewLevels = $user->getAuthorisedViewLevels();
		}
		
		return in_array( $access, $viewLevels ) ? true : false;
		
	}
	
	public static function addSubMenu( $view ) {
	
		$app = JFactory::getApplication();
		
		$views = array(
			'dashboard'  => false,
			'categories' => false,
			'videos'     => false, 
			'approval'   => false,
			'ratings'    => false,
			'config'     => false
		);
		$view = $app->input->get( 'view', 'dashboard' );
		$views[ $view ] = true;		
		
		JSubMenuHelper::addEntry( JText::_('YENDIF_VIDEO_SHARE_CONTROL_PANEL'), 'index.php?option=com_yendifvideoshare', $views['dashboard'] );
		JSubMenuHelper::addEntry( JText::_('YENDIF_VIDEO_SHARE_CATEGORIES'), 'index.php?option=com_yendifvideoshare&view=categories', $views['categories'] );	
		JSubMenuHelper::addEntry( JText::_('YENDIF_VIDEO_SHARE_VIDEOS'), 'index.php?option=com_yendifvideoshare&view=videos', $views['videos'] );
		JSubMenuHelper::addEntry( JText::_('YENDIF_VIDEO_SHARE_APPROVAL_QUEUE'), 'index.php?option=com_yendifvideoshare&view=approval', $views['approval'] );
		JSubMenuHelper::addEntry( JText::_('YENDIF_VIDEO_SHARE_RATINGS'), 'index.php?option=com_yendifvideoshare&view=ratings', $views['ratings'] );
		JSubMenuHelper::addEntry( JText::_('YENDIF_VIDEO_SHARE_GLOBAL_CONFIGURATION'), 'index.php?option=com_yendifvideoshare&view=config', $views['config'] );
			
	}
	
	public static function getToken() {	
	
		return JSession::getFormToken();
		
	}
	
	public static function checkToken() {
	
		if( JSession::checkToken( 'get' ) ) {
			JSession::checkToken( 'get' ) or die( 'Invalid Token' );
		} else {
			JSession::checkToken() or die( 'Invalid Token' );
		}
		
	}
	
	public static function safeString( $value = '' ) {
	
		return htmlspecialchars( trim( $value ) );
		
	}
	
	public static function stringURLSafe( $string ) {
	
		jimport( 'joomla.filter.output' );
		
    	if( JFactory::getConfig()->get('unicodeslugs') == 1 ) {
        	$output = JFilterOutput::stringURLUnicodeSlug( $string );
    	} else {
        	$output = JFilterOutput::stringURLSafe( $string );
    	}
    	
		return $output;
		
	}
	
	public static function Truncate( $text, $length = 150 ) {
	
		$text = strip_tags( $text );
		
    	if( $length > 0 && JString::strlen( $text ) > $length ) {
        	$tmp = JString::substr( $text, 0, $length );
            $tmp = JString::substr( $tmp, 0, JString::strrpos( $tmp, ' ' ) );

            if( JString::strlen( $tmp ) >= $length - 3 ) {
            	$tmp = JString::substr( $tmp, 0, JString::strrpos( $tmp, ' ' ) );
            }
 
            $text = $tmp.'...';
        }
 
        return $text;
		
	}
	
	public static function getUsers() {
	
		$db = JFactory::getDBO();
		
		$query = 'SELECT id,username FROM #__users';
		$db->setQuery( $query );
		$items = $db->loadObjectList();
		 
        return $items;
		
	}
	
	public static function getColumn( $table, $column, $id ) {
	
		$db = JFactory::getDBO();
		
        $query = "SELECT ".$column." FROM #__yendifvideoshare_".$table." WHERE id=".$id;
		$db->setQuery($query);
		$item = $db->loadResult();
		
        return $item;
		
	}
	
	public static function getConfig( $column = '*' ) {
	
         $db = JFactory::getDBO(); 
		if($column === '*') {		
			$query = "SELECT name,value FROM #__yendifvideoshare_options ";
			$db->setQuery($query);		
			$options = $db->loadObjectList(); 		
			$config = new JObject();
			foreach( $options as $option ) {
				$config->{$option->name} = $option->value;
			}		
			
		} else {
		    $query = "SELECT value FROM #__yendifvideoshare_options WHERE name=".$db->Quote($column);
			$db->setQuery($query);
			$config = $db->loadResult();			
		}
		
		return $config;
		 
	}
	
	public static function getVideoInsertId() {
	
		jimport('joomla.filesystem.folder');	
		
		$db = JFactory::getDBO();
		$user_id = JFactory::getUser()->get('id');
		$insert_id = 0;		
		
        $query = "SELECT id FROM #__yendifvideoshare_videos WHERE title='' AND userid=$user_id ORDER BY id DESC";
		$db->setQuery($query);
		$items = $db->loadObjectList();
		
		foreach( $items as $key => $item ) {
			if( 0 == $key && $item->id > 0 ) {
				$insert_id = $item->id;
			} else {
				$query = "DELETE FROM #__yendifvideoshare_videos WHERE id=".$item->id;
            	$db->setQuery( $query );
            	$db->query();
			}	
			$del_dir = YENDIF_VIDEO_SHARE_UPLOAD_BASE.'videos'.DIRECTORY_SEPARATOR.$item->id.DIRECTORY_SEPARATOR;
			if( JFolder::exists( $del_dir) ) JFolder::delete( $del_dir );
		}
		
		if( 0 == $insert_id ) {
			$row = new JObject();
   			$row->id = NULL;
   			$row->userid = $user_id;
   			$row->title = '';
   			$db->insertObject( '#__yendifvideoshare_videos', $row );
			
			$insert_id = $db->insertid();			
		}
		
        return $insert_id;
		
	}
	
	public static function getAdvertInsertId() {
	
		jimport('joomla.filesystem.folder');	
		
		$db = JFactory::getDBO();
		$insert_id = 0;		
		
        $query = "SELECT id FROM #__yendifvideoshare_adverts WHERE title='' ORDER BY id DESC";
		$db->setQuery($query);
		$items = $db->loadObjectList();
		
		foreach( $items as $key => $item ) {
			if( 0 == $key && $item->id > 0 ) {
				$insert_id = $item->id;
			} else {
				$query = "DELETE FROM #__yendifvideoshare_adverts WHERE id=".$item->id;
            	$db->setQuery( $query );
            	$db->query();
			}	
			$del_dir = YENDIF_VIDEO_SHARE_UPLOAD_BASE.'videos'.DIRECTORY_SEPARATOR.$item->id.DIRECTORY_SEPARATOR;
			if( JFolder::exists( $del_dir) ) JFolder::delete( $del_dir );
		}
		
		if( 0 == $insert_id ) {
			$row = new JObject();
   			$row->id = NULL;
   			$row->title = '';
   			$db->insertObject( '#__yendifvideoshare_adverts', $row );
			
			$insert_id = $db->insertid();			
		}
		
        return $insert_id;
		
	}
	
	public static function getCategoryTree( $ids ) {	
		
		$db = JFactory::getDBO();	
				
		$ids = (array) $ids;
		JArrayHelper::toInteger( $ids );
		$catid  = array_unique( $ids );
		sort($ids);
		
		$array = $ids;				
		while( count( $array ) ) {
			$query = "SELECT id FROM #__yendifvideoshare_categories 
					WHERE parent IN (".implode(',', $array).") 
					AND id NOT IN (".implode(',', $array).") ";
			$db->setQuery($query);
			$array = $db->loadColumn();
			$ids = array_merge( $ids, $array );
		}
		JArrayHelper::toInteger( $ids );
		$ids = array_unique( $ids );
					
		return $ids;
		
	}
	
	public static function getMediaCount( $id ) {
			
		$db = JFactory::getDBO();
		
		$publish = self::getConfig();			
		$nullDate = $db->quote($db->getNullDate());
		$date = JFactory::getDate();
		$nowDate = $db->quote($date->toSql());
		
		$query = "SELECT COUNT(id) FROM #__yendifvideoshare_videos WHERE published=1";
		if( $publish->schedule_video_publishing ) {
			$query .= " AND ( published_up = " . $nullDate . " OR published_up <= " . $nowDate .' )';
			$query .= " AND ( published_down = " . $nullDate . " OR published_down >= " . $nowDate .' )';
		}				
		$query .= " AND  catid IN(".implode( ',', self::getCategoryTree( $id ) ).")";
		$db->setQuery( $query );
		$count = $db->loadResult();			
		
		return $count;	
		
	}
	
	public static function getSubCategoryMediaCount( $id, $check_publishing_options ) {
	
		$db = JFactory::getDBO();

		$query = "SELECT COUNT(id) FROM #__yendifvideoshare_videos WHERE published=1";
		
		if( $check_publishing_options ) {
			$date = JFactory::getDate();
			
			$nullDate = $db->quote( $db->getNullDate() );
			$nowDate  = $db->quote( $date->toSql() );
		
			$query .= " AND ( published_up = " . $nullDate . " OR published_up <= " . $nowDate .' )';
			$query .= " AND ( published_down = " . $nullDate . " OR published_down >= " . $nowDate .' )';
		}				
		
		$query .= " AND catid IN(".$id.")";
		$db->setQuery( $query );
		$count = $db->loadResult();			
		
		return $count;	
		
	}
	
	public static function getImage( $file, $format, $default ) {
	
		if( empty( $file ) ) {
		
			if ( empty ( $default ) ) {
				$default = JURI::root( true ).'/media/yendifvideoshare/assets/site/images/placeholder.jpg';
			}
			return $default;
		}
		
		// If an uploaded image file
		$isUploaded = strpos( $file, 'media/yendifvideoshare/' );		
		if( $isUploaded !== false ) {
			// Remove Protocols
			$file = explode( 'media', $file );
			$file = '/media'.$file[1];
			
			// Check if Image available for the given "format"
			$ext   = substr( $file, strrpos( $file, "." ) + 1 );
			$file2 = str_replace( $ext, $format.'.'.$ext, $file );			
			if( file_exists( JPATH_ROOT.str_replace( '/', DIRECTORY_SEPARATOR, $file2 ) ) ) {
				$file = $file2;
			}
			
			// Add the Joomla! folder
			$file = JURI::root( true ).$file;
		}
			
		// If YouTube
		$isYouTube = strpos( $file, 'youtube.com' );
		if( $isYouTube !== false ) {
			$file = str_replace( 'http:', 'https:', $file );
		}
		
		// Remove spaces
		$file = str_replace( ' ', '%20', $file );
		
		
		
		// Return
		return $file;	
		
	}
	
	public static function RatingWidget( $rating = 0, $id, $total_users ) {		
	
		$html  = '<div class="yendif-ratings">';
		$html .= '<span class="yendif-ratings-stars">';
		$html .= '<span class="yendif-current-ratings" style="width:'.$rating.'%;"></span>';
		
		$j = 0.5;
		for( $i = 0; $i < 10; $i++ ) {
			$j += 0.5;
			$html .= '<span class="yendif-ratings-star">';
			$html .= '<a class="yendif-rating-trigger yendif-star-'.($j * 10).'" title="'.JText::sprintf('YENDIF_VIDEO_SHARE_RATING_TITLE', $j, 5).'" data-id="'.$id.'" data-value="'.$j.'">1</a>';
			$html .= '</span>';
		}
		
		$html .= '</span>';
		$html .= '<span class="yendif-ratings-info">'.JText::sprintf('YENDIF_VIDEO_SHARE_RATING_INFO', ($rating * 5 ) / 100, $total_users).'</span>';
		$html .= '</div>';
		
		return $html;
		
	}
	
	public static function showRating( $rating = 0 ) {
	
		$html  = '<div class="yendif-ratings-small">';
		$html .= '<span class="yendif-ratings-stars"><span class="yendif-current-ratings" style="width:'.$rating.'%;"></span></span>';
		$html .= '</div>';
		
		return $html;
		
	}	
	
	public static function VotingWidget( $id, $allow_guest_like ) {	
	
		$status = self::getLikeDislikeStatus( $id, $allow_guest_like );
		
		$like_class_suffix = $dislike_class_suffix = '';
		if( ! empty( $status ) ) {
			if( $status->likes > 0 ) $like_class_suffix = ' active';
			if( $status->dislikes > 0 ) $dislike_class_suffix = ' active';	
		}
	
		$html  = '<div class="yendif-likes-dislikes">';
        $html .= '<span class="yendif-like-btn" data-id="'.$id.'" data-like="1" data-dislike="0">';
        $html .= '<span class="yendif-like-icon'.$like_class_suffix.'"></span>';
        $html .= '<span class="yendif-like-dislike-divider"></span>';
        $html .= '<span class="yendif-like-count">'.self::getLikes( $id ).'</span>';              
        $html .= '</span>';
        $html .= '<span class="yendif-dislike-btn" data-id="'.$id.'" data-like="0" data-dislike="1">';
        $html .= '<span class="yendif-dislike-icon'.$dislike_class_suffix.'"></span>';
        $html .= '<span class="yendif-like-dislike-divider"></span>';
        $html .= '<span class="yendif-dislike-count">'.self::getDislikes( $id ).'</span>';          
      	$html .= '</span>';
        $html .= '</div>';
		
		return $html;	
			
	}
	
	public static function getLikes( $id ) {	
		
		$db = JFactory::getDBO();
		
		$query = "SELECT COUNT(id) FROM #__yendifvideoshare_likes_dislikes WHERE videoid = ".$id." AND likes=1";
		$db->setQuery( $query );
		
		return $db->loadResult();		
		
	}
	
	public static function getDislikes( $id ) {	
		
		$db = JFactory::getDBO();
		
		$query = "SELECT COUNT(id) FROM #__yendifvideoshare_likes_dislikes WHERE videoid = ".$id." AND dislikes=1";
		$db->setQuery( $query );
		
		return $db->loadResult();	
			
	}		
	
	public static function getLikeDislikeStatus( $id, $allow_guest_like ) {
	
		$session = JFactory::getSession();	
		$db = JFactory::getDBO();
				
		$userid    = JFactory::getUser()->get('id');
		$sessionid = $session->getId();	

		$query  = "SELECT likes, dislikes FROM #__yendifvideoshare_likes_dislikes WHERE videoid = ".$id;
		$query .= $allow_guest_like == 1 ? " AND sessionid=".$db->quote( $sessionid ) : " AND userid=".$userid;
		$db->setQuery( $query );
		
		return $db->loadObject();
					
	}
	
	public static function buildRoute( $item = '', $view = 'video', $itemId = 0 ) {
	
		$id = 0;
		$alias = '';
		
		if( empty( $item ) ) {
			if( 'category' != $view && 'video' != $view ) {
				return '';
			}
		} else {
			$id = $item->id;
			$alias = $item->alias;
		}
		
		$needles   = array();		
		$needles[] = 'index.php?option=com_yendifvideoshare';
		$needles[] = 'view='.$view;
		$needles[] = 'id='.$id.':'.$alias;
		
		if( ! $itemId ) {
			$app = JFactory::getApplication();
			$itemId = $app->input->getInt('Itemid');
			
			$_item = self::_findItem( array( 'Itemid' => $itemId, 'option' => 'com_yendifvideoshare', 'view' => $view, 'layout' => 'default', 'id' => $id ) );
			$itemId = $_item['itemId'];
			if( $_item['is_exact_match'] ) {
				$needles = array();
			}
		}
		
		if( $itemId ) {
			$needles[] = 'Itemid='.$itemId;
		}
		
		// Build Route
		if( 1 == count( $needles ) ) {
			$url = 'index.php?'.$needles[0];
		} else {
			$url = implode( '&', $needles );
		}
		
		return JRoute::_( $url );
	
	}
	
	protected static function _findItem( $query = null ) {
	
		// define empty array to avoid having unset properties in my query
		$base_array = array( 'Itemid' => '', 'option' => '', 'view' => '', 'layout' => '', 'id' => '' );

		$app = JFactory::getApplication();
		
		$menu   = $app->getMenu();
		$active = $menu->getActive();		

		// start with no match found
		$match = false;
		$match_level = 0;
		$item_match = 0;
		$is_exact_match = 0;

		// load all menu items
		$items = $menu->getMenu();

		// use the current item over others if it ties for the best match
		if( ! empty( $active->query ) ) {
			$active->query += $base_array;
			
			if( $active->query['option'] == $query['option'] ) {
				$match_level = 1;
				$match = $active;
				if( $active->query['view'] == $query['view'] ) {
					$match_level = 2;
					if( $active->query['layout'] == $query['layout'] || ( $query['layout'] == 'default' && ! $active->query['layout'] ) ) {
						$match_level = 3;
						if( $active->query['id'] == $query['id'] ) {
							$match_level = 4;
							$is_exact_match = 1;
						}
					}
				}
			}
		}

		// loop through each menu item in order
		foreach( $items as $item ) {
			$item->query += $base_array;
			
			// base check is that it is for this component
			// then cycle through each possibility finding it's match level
			if( $item->query['option'] == $query['option'] ) {
				$item_match = 1;
				if( $item->query['view'] == $query['view'] ) {
					$item_match = 2;
					if( $item->query['layout'] == $query['layout'] || ( $query['layout']=='default' && ! $item->query['layout'] ) ) {
						$item_match = 3;
						if( $item->query['id'] == $query['id'] ) {
							$item_match = 4;
							$is_exact_match = 1;
						}
					}
				}
			}

			// if this item is a better match than our current match, set it as the best match
			if( $item_match > $match_level ) {
				$match_level = $item_match;
				$match = $item;
			}
		}

		// if there is a match update Itemid to match that menu item
		if( $match ) {
			$itemId = $match->id;
		}
		
		return array( 'itemId' => $itemId, 'is_exact_match' => $is_exact_match );

	}
	
	public static function prepareURL( $url, $pathonly = true ) {	
		return JURI::root( $pathonly ) . '/' . $url . '?v=1.2.8';
	}
	
}