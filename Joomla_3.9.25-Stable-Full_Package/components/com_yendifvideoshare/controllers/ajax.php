<?php

/*
 * @version		$Id: ajax.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class YendifVideoShareControllerAjax extends YendifVideoShareController {
	
	public function ratings() {
	
		$app = JFactory::getApplication();
		$session = JFactory::getSession();
		$db = JFactory::getDBO();
				
		$videoid   = $app->input->getInt('videoid');	
		$rating    = $app->input->getFloat('rating');
		$userid    = JFactory::getUser()->get('id');
		$sessionid = $session->getId();
		
		$allow_guest_rating = YendifVideoShareUtils::getColumn('config', 'allow_guest_rating', 1);
		
        $query  = "SELECT COUNT(id) FROM #__yendifvideoshare_ratings WHERE videoid=".$videoid;
		$query .= $allow_guest_rating == 1 ? " AND sessionid=".$db->quote( $sessionid ) : " AND userid=".$userid;
        $db->setQuery( $query );
        $count = $db->loadResult();
		
		if( $count ) {
			$query  = "UPDATE #__yendifvideoshare_ratings SET rating=".$rating." WHERE videoid=".$videoid;
			$query .= $allow_guest_rating == 1 ? " AND sessionid=".$db->quote( $sessionid ) : " AND userid=".$userid;
			$db->setQuery( $query );
			$db->query();			
		} else {
			$row = new JObject();
   			$row->id = NULL;
			$row->rating = $rating;
			if( $userid > 0 ) $row->userid = $userid;				
			if( $allow_guest_rating == 1 ) $row->sessionid = $sessionid;
   			$row->videoid = $videoid;		
   			$db->insertObject( '#__yendifvideoshare_ratings', $row );
		}	
		
		$query = "SELECT SUM(rating) as total_ratings, COUNT(id) as total_users FROM #__yendifvideoshare_ratings WHERE videoid=".$videoid;
		$db->setQuery( $query );
		$result = $db->loadObject();				
		$rating = ( $result->total_ratings / ( $result->total_users * 5 ) ) * 100;
					
		$query  = "UPDATE #__yendifvideoshare_videos SET rating=".$rating." WHERE id=".$videoid;
		$db->setQuery( $query );
		$db->query();	
				
		echo YendifVideoShareUtils::RatingWidget( $rating, $videoid, $result->total_users );
			
	}	
	
	public function likes_dislikes() {
	
		$app = JFactory::getApplication();
		$session = JFactory::getSession();
		$db = JFactory::getDBO();

		$videoid   = $app->input->getInt('videoid');	
		$likes     = $app->input->getInt('likes');
		$dislikes  = $app->input->getInt('dislikes');			
		$userid    = JFactory::getUser()->get('id');
		$sessionid = $session->getId();
		
		$allow_guest_like = YendifVideoShareUtils::getColumn('config', 'allow_guest_like', 1);

		$query  = "SELECT COUNT(id) FROM #__yendifvideoshare_likes_dislikes WHERE videoid=".$videoid;
		$query .= $allow_guest_like == 1 ? " AND sessionid=".$db->quote( $sessionid ) : " AND userid=".$userid;
		$db->setQuery( $query );			
		$count = $db->loadResult();				
		
		if( $count ) {
			$query  = "UPDATE #__yendifvideoshare_likes_dislikes SET userid=".$userid.", likes=".$likes.", dislikes=".$dislikes." WHERE videoid=".$videoid;
			$query .= $allow_guest_like == 1 ? " AND sessionid=".$db->quote( $sessionid ) : " AND userid=".$userid;
			$db->setQuery( $query );
			$db->query();
		} else {	
			$row = new JObject();	
			if( $userid > 0 ) $row->userid = $userid;
			$row->videoid = $videoid;
			if( $allow_guest_like == 1 ) $row->sessionid = $sessionid;	
			$row->likes = $likes;
			$row->dislikes = $dislikes;			
			$result = $db->insertObject( '#__yendifvideoshare_likes_dislikes', $row );												
		}
		
		echo YendifVideoShareUtils::VotingWidget( $videoid, $allow_guest_like );
					
	}
	
	public function updateviews() {	
	
		$app = JFactory::getApplication();
		$session = JFactory::getSession();
		$db = JFactory::getDBO(); 
			
		$videoid    = $app->input->getInt('id');
		$ses_videos = $session->get('yendif_videos', array());

		if( ! in_array( $videoid, $ses_videos ) ) {
		    $ses_videos[] = $videoid;

		 	$query = "SELECT views FROM #__yendifvideoshare_videos WHERE id=" . (int) $videoid;
    	 	$db->setQuery ( $query );
    	 	$result = $db->loadObject();
		 
		 	$count = $result ? $result->views + 1 : 1;	 
		 	$query = "UPDATE #__yendifvideoshare_videos SET views=".$count." WHERE id=" . (int) $videoid;
    	 	$db->setQuery ( $query );
		 	$db->query();
		 
		 	$session->set('yendif_videos', $ses_videos);			
		}
		
		echo 'success';
		exit();
		
	}
	
	public function updateimpressions() {	
	
		$app = JFactory::getApplication();
		$db = JFactory::getDBO(); 
		$session = JFactory::getSession();	
		
		$videoid    = $app->input->getInt('id');
		$ses_videos = $session->get('yendif_impressions', array());

		if( ! in_array( $videoid, $ses_videos ) ) {
		    $ses_videos[] = $videoid;
		 	    	    
		 	$query = "SELECT impressions FROM #__yendifvideoshare_adverts WHERE id=" . (int) $videoid;
    	 	$db->setQuery ( $query );
    	 	$result = $db->loadObject();
		 
		 	$count = $result ? $result->impressions + 1 : 1;	 
		 	$query = "UPDATE #__yendifvideoshare_adverts SET impressions=".$count." WHERE id=" . (int) $videoid;
    	 	$db->setQuery ( $query );
		 	$db->query();
		 
		 	$session->set('yendif_impressions', $ses_videos);			
		}
		
		echo 'success';
		exit();
		
	}
	
	public function updateclicks() {
		
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();   
		$session = JFactory::getSession();	
		
		$videoid    = $app->input->getInt('id');
		$ses_videos = $session->get('yendif_clicks', array());

		if( ! in_array( $videoid, $ses_videos ) ) {
		    $ses_videos[] = $videoid;
		 	  	    
		 	$query = "SELECT clicks FROM #__yendifvideoshare_adverts WHERE id=" . (int) $videoid;
    	 	$db->setQuery ( $query );
    	 	$result = $db->loadObject();
		 
		 	$count = $result ? $result->clicks + 1 : 1;	 
		 	$query = "UPDATE #__yendifvideoshare_adverts SET clicks=".$count." WHERE id=" . (int) $videoid;
    	 	$db->setQuery ( $query );
		 	$db->query();
		 
		 	$session->set('yendif_clicks', $ses_videos);			
		}
		
		echo 'success';
		exit();
		
	}	
			
}