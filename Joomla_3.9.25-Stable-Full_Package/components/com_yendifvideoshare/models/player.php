<?php
/*
 * @version		$Id: player.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class YendifVideoShareModelPlayer extends YendifVideoShareModel {
    
  public function getVideo( $id ) {
        $db = JFactory::getDBO();        
        
		$query = 'SELECT * FROM #__yendifvideoshare_videos WHERE published=1 AND id=' . (int) $id;
        $db->setQuery( $query );
        $video = $db->loadObject();

        return $video;        
    }
	
   
}