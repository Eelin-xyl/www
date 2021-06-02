<?php

/*
 * @version		$Id: yendifvideoshare.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

require_once( JPATH_ROOT.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_yendifvideoshare'.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'player.php' );

require_once( JPATH_ROOT.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_yendifvideoshare'.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'utils.php' );

class plgContentYendifVideoShare extends JPlugin {

	protected $autoloadLanguage = true;

	public function onContentPrepare($context, &$article, &$params, $page=0) {	
	
		$this->onPrepareContent( $article, $params, $page );
		
	}

	public function onPrepareContent( &$row, &$params, $limitstart ) {
	
		// simple performance check to determine whether bot should process further
		if ( JString::strpos( $row->text, 'yendifplayer' ) === false ) {
			return true;
		}
		
		// expression to search for
 		$regex = '/{yendifplayer\s*.*?}/i';
		
		// find all instances of plugin and put in $matches
		preg_match_all( $regex, $row->text, $matches );

		// Number of plugins
 		$count = count( $matches[0] );
		
		$this->plgContentProcessPositions( $row, $matches, $count, $regex);

	}
	
	private function plgContentProcessPositions ( $row, $matches, $count, $regex) {
	
 		for ( $i=0; $i < $count; $i++ ) {
 			$load  = str_replace( '{yendifplayer', '', $matches[0][$i] );
 			$load  = str_replace( '}', '', $load );
			$load  = trim( $load );
			$load  = explode(" ",$load);
 			
			$modules	= $this->plgContentLoadPosition($load);
			$row->text 	= str_replace($matches[0][$i], $modules, $row->text );
 		}

  		// removes tags without matching module positions
		$row->text = preg_replace( $regex, '', $row->text );
		
	}
	
	private function plgContentLoadPosition( $properties ) {	
		
		$types = array('mp4', 'webm', 'ogg', 'youtube', 'rtmp', 'flash', 'captions', 'image');
		$params = array();
		$item = new stdClass();
		$type = 'playlist';
		$_config = YendifVideoShareUtils::getConfig();
		
		foreach( $properties as $property ) {
        	$str_pieces = explode( '=', $property );			
			$key = $str_pieces[0];
			unset( $str_pieces[0] );
			$val = join( '=', $str_pieces );

			switch( $key ) {
				case 'hd' :
					$key = 'mp4_hd';
					break;
				case 'width' :
					$key = 'player_width';
					break;
				case 'height' :
					$key = 'player_height';
					break;
				case 'title_limit' :
					$key = 'playlist_title_limit';
					break;
				case 'description_limit' :
					$key = 'playlist_desc_limit';
					break;
				case 'filterby' :
					$key = 'featured';
					break;
			}
			
			if( in_array( $key, $types ) ) {
				$item->$key = $val;
				$item->type = "custom";
				$type = 'single';	
			} else {
            	$params[$key] = $val;
			};
        }		
		
		if( isset( $params['videoid'] ) ) {			
			$db = JFactory::getDBO();
			
        	$query = "SELECT * FROM #__yendifvideoshare_videos WHERE published=1";
			
			if( $_config->schedule_video_publishing ) {	
				$date = JFactory::getDate();
					
				$nullDate = $db->quote( $db->getNullDate() );		 	
		 		$nowDate  = $db->quote( $date->toSql() );
			 
				$query .= " AND ( published_up = " . $nullDate . " OR published_up <= " . $nowDate .' )';
				$query .= " AND ( published_down = " . $nullDate . " OR published_down >= " . $nowDate .' )';	
		 	}	
			
			$query .=" AND id=".(int) $params['videoid'];
        	$db->setQuery( $query );

			$type = 'single';
		} else if( isset( $params['catid'] ) ) {
			$type = 'playlist';
		}
		
		$playerObj = YendifVideoSharePlayer::getInstance();	
		
		if( $type == 'playlist' ) {

			jimport( 'joomla.application.module.helper' );
			$document = JFactory::getDocument();
			$renderer = $document->loadRenderer('module');
			$moduleHtml= $renderer->render( JModuleHelper::getModule('mod_yendifvideoshare_playlist'), $params );
			
			return $moduleHtml;	
		} else {
			return $playerObj->build( $params, $item );
		}
		
	}
	
}