<?php

/*
 * @version		$Id: router.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access'); 

class YendifVideoShareRouter extends JComponentRouterBase {

	public function build( &$query ) {
	
		$segments = array();	
		
		$menu = JFactory::getApplication()->getMenu();		
		
	    // Detect the active menu item
	    if( empty( $query['Itemid'] ) ) {		
			$menuItem = $menu->getActive();
	    } else {
			$menuItem = $menu->getItem( $query['Itemid'] );
	    }	
		
	    $option = ! empty( $menuItem->component ) ? $menuItem->component : null;        
	    $view   = ! empty( $query['view'] )       ? $query['view']       : null;
	    $layout = ! empty( $query['layout'] )     ? $query['layout']     : null;
	    $id     = ! empty( $query['id'] )         ? $query['id']         : null;		

		// JoomSEF bug workaround
	    if( isset( $query['start'] ) && isset( $query['limitstart'] ) ) {
			if( (int) $query['limitstart'] != (int) $query['start'] && (int) $query['start'] > 0 ) {
				// let's make it clear - 'limitstart' has higher priority than 'start' parameter, 
				// however ARTIO JoomSEF doesn't seem to respect that.
				$query['start'] = $query['limitstart'];
				unset( $query['limitstart'] );
			}
	    }
	    // JoomSEF workaround - end
	 	 
		if( isset( $query['view'] ) ) {
			$segments[] = $query['view'];
			unset( $query['view'] );
	    }
	    
	    if( isset( $query['task'] ) ) {
			$segments[] = $query['task'];
			unset( $query['task'] );
	    }
	    
	    if( isset( $query['action'] ) ) {
			$segments[] = $query['action'];
			unset( $query['action'] );
	    }
	    
	    if( isset( $query['id'] ) ) {
			$segments[] = $query['id'];
			unset( $query['id'] );
	    }
		
	    if( isset( $query['layout'] ) ) {
			$segments[] = $query['layout'];
			unset( $query['layout'] );
	    }
			
		$config    = $this->getYendifConfig();
		$catName   = $config->sef_cat;
		$videoName = $config->sef_video_prefix;	
		$isPos     = $config->sef_position;
		$sptr      = $config->sef_sptr ? '/' : '-';   
		
		if( $view && $option == 'com_yendifvideoshare' || $option != 'com_yendifvideoshare' ) { 
		 
			if( ! empty( $segments ) ) {
			
				if( $segments[0] == 'category' ) {
					$segments[0] = $catName;			
					$segments[1] = JString::str_ireplace( ':', $sptr, @$segments[1] );    		
					if( $isPos ) {	
						$temp = explode( $sptr, $segments[1], 2 );					
						$segments[1] = @$temp[1].$sptr.$temp[0];	
					}				
				} else if( $segments[0] == 'video' ) {

					if( $config->sef_video == '0' ) {
						$segments[0] = $videoName;	
					} else if( $config->sef_video == '1' ) {
						@$catid = explode( ':', $segments[1] );					
						$segments[0] = $this->getYendifCategorySlug( (int) $catid[0] );    				
					} else {
						$segments[0] = 'video';
					}				
					$segments[1] = JString::str_ireplace(':', $sptr, @$segments[1]);    		
					if( $isPos ) {	
						$temp = explode( $sptr, $segments[1], 2 );					
						$segments[1] = @$temp[1].$sptr.$temp[0];	
					}
							
				}

			}	    
		}

		return $segments;
		
	}

	public function parse( &$segments ) {
	
		$vars  = array();
		
		$config  = $this->getYendifConfig();
		$catName = $config->sef_cat;	
		$isPos   = $config->sef_position;
		$sptr    = $config->sef_sptr ? '/' : '-' ;		
		
		if( $config->sef_video > 0 && $segments[0] != $catName && $segments[0] != 'category' && $segments[0] != 'user' && $segments[0] != 'search' ) {
			$videoN = array_splice( $segments, 0, 1, 'video' );
			$videoName = $videoN[0];
		} else {
			$videoName = $config->sef_video_prefix;	
		}
			
		 switch( $segments[0] ) {    
			case 'categories' :
				$vars['view'] = 'categories';	
				if( isset( $segments[1] ) ) {
					$vars['id']   = $segments[1];
				}			
				break;
			case 'category' :
			case $catName   :
				$vars['view'] = 'category';
											
				if( ! isset( $segments[1] ) || $segments[1] == '' ) {
					JFactory::getApplication()->enqueueMessage( JText::_('JGLOBAL_CATEGORY_NOT_FOUND'), 'error' );
				} 

				$alias = null;
				if( $sptr == '-' ) {
					if( $isPos > 0 && $sptr == 0 ) { 
						$temp = explode( $sptr, $segments[1] );	 
						$vars['id'] = end( $temp );
						$alias = join( $sptr, explode( $sptr, $segments[1], -1 ) );
				     } else {
						$temp = explode( $sptr, $segments[1], 2 );	 
						$vars['id'] = $temp[0];	
						$alias = isset( $temp[1] ); 

				     }
				} else if( $sptr == '/' ) {
					if( $isPos > 0 && $sptr == 0 ) {
						$vars['id'] = $segments[2];
						$alias = $segments[1];
				    } else {
						$vars['id'] = $segments[1];
						$alias = $segments[2];
				    }
				}
				break;
			case 'videos' :
				$vars['view'] = 'videos';
				if( isset( $segments[1] ) ) {
					$vars['id'] = $segments[1];
				}	
				break;
			case 'video' :
			case $videoName :			 			
				$vars['view'] = 'video';				
				
				if( ! isset( $segments[1] ) || $segments[1] == '' ) {
					JFactory::getApplication()->enqueueMessage( JText::_('JERROR_PAGE_NOT_FOUND'), 'error' );
				} 

				$alias = null;
				if( $sptr == '-' ) {
					if( $isPos > 0 && $sptr == 0 ) { 
						$temp = explode( $sptr, $segments[1] );	 
						$vars['id'] = end( $temp );
						$alias = join( $sptr, explode( $sptr, $segments[1],-1 ) );
				     } else {
						$temp = explode( $sptr, $segments[1], 2);	 
						$vars['id'] = $temp[0] ;
						$alias = $temp[1]; 
				     }
				} else if( $sptr == '/' ) {
				    if( $isPos > 0 && $sptr == 0 ) {
						$vars['id'] = $segments[2];
						$alias = $segments[1];
				     } else {
						$vars['id'] = $segments[1];
						$alias = $segments[2];
				     }
				}
				break;		
			case 'user' :
				$vars['view'] = 'user';
				
				if( isset( $segments[1] ) == 'add' ) {
					$vars['task'] = $segments[1];
				}
					
				if( isset( $segments[1] ) == 'edit' && $segments[1] != 'add' ) {												
					$vars['task'] = $segments[1];
					@$vars['id']  = $segments[2];
				}
					
				if( isset( $segments[1] ) == 'delete' && $segments[1] != 'add' ) {										
					$vars['task'] = $segments[1];
					@$vars['id']  = $segments[2];					
				}								
				break;
			case 'search' :
				$vars['view'] = 'search';				
				break;
			case 'upload' :
				$vars['view'] = 'upload';				
				break;
			case 'ajax' :
				$vars['view'] = 'ajax';				
				break;
			default:
				JFactory::getApplication()->enqueueMessage( JText::_('JERROR_PAGE_NOT_FOUND') );
	    }
	
	    return $vars;
			
	}

	private function getYendifConfig() {
	
		$db = JFactory::getDBO();      
		$query = "SELECT name,value FROM #__yendifvideoshare_options";
		$db->setQuery($query);		
		$items = $db->loadObjectList(); 
					
		$item = new JObject();
		foreach( $items as $option ) {
			$item->{$option->name} = $option->value;			
		}		
	
        return $item;
		
	}
		
	private function getYendifCategorySlug( $catid ) {
	
	    $db = JFactory::getDBO();
			
       	$query  = 'SELECT v.catid, c.alias as catalias FROM #__yendifvideoshare_videos AS v';
    	$query .= ' LEFT JOIN #__yendifvideoshare_categories AS c ON v.catid = c.id';
    	$query .= ' WHERE v.id='.(int) $catid;
   		$db->setQuery( $query );
		$item = $db->loadObject();
		
		if( ! empty( $item->catalias ) ) {
			return $item->catalias;
		}
		
		return 'video';
				
	} 
	 
}
