<?php

/*
 * @version		$Id: config.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class YendifVideoShareModelConfig extends YendifVideoShareModel {
	
	function getItem() {
		$db = JFactory::getDBO();      
		$query = "SELECT name,value FROM #__yendifvideoshare_options ";
		$db->setQuery($query);		
		$items = $db->loadObjectList(); 
					
		$item = new JObject();
		foreach( $items as $option ) {
			$item->{$option->name} = $option->value;			
		}		
	
        return $item;
	}		

	
	public function save() {
	
	  	$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		

		$post = $app->input->post->getArray();
		
		if(!array_key_exists('sef_cat', $post)) {		
					$post['sef_cat'] = 'category';					
		}
		
		if(!array_key_exists('sef_video_prefix', $post)) {		
					$post['sef_video_prefix'] = 'video';					
		}	
			
		if(!array_key_exists('feed_limit', $post)) {							
					$post['feed_limit'] = 5;
		}
		
		foreach( $post as $name=>$value ) {		
		
			if($name == 'responsive_css'){					
					$value =  $app->input->post->get( 'responsive_css', '', 'RAW' );			
			}
			
			if($name == 'share_script'){					
					$value =  $app->input->post->get( 'share_script', '', 'RAW' );			
			}	
			
			if($name == 'license'){					
					$value =  $app->input->post->get( 'license', '', 'RAW' );		
			}	

			$query = 'UPDATE #__yendifvideoshare_options SET value='.$db->Quote($value);
					 $query .= ' WHERE name='.$db->Quote($name);
					 $db->setQuery($query);
					 $db->execute();	
		}	

      	$msg = JText::_('YENDIF_VIDEO_SHARE_CHANGES_SAVED');
      	$link = 'index.php?option=com_yendifvideoshare&view=config';
  
	  	$app->redirect($link, $msg, 'message');
		
	}
	
}