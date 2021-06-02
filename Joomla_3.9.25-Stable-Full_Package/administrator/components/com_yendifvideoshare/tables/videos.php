<?php

/*
 * @version		$Id: videos.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access'); 

class YendifVideoShareTableVideos extends JTable {

	var $id               = null;
  	var $title            = null;
  	var $alias            = null;
	var $description      = null;
	var $catid            = null;
  	var $type             = null;
  	var $youtube          = null;
	var $mp4              = null;
	var $mp4_hd           = null;
	var $rtmp             = null;
	var $flash            = null;
	var $hls			  = null;
	var $dash	      	  = null;
	var $webm             = null;
	var $ogg              = null;
	var $thirdparty       = null;
	var $image            = null;
	var $captions         = null;
	var $duration         = null;
    var $userid           = null;  	
  	var $access           = null;
	var $ordering         = null;
	var $views            = null;
	var $meta_keywords    = null;
  	var $meta_description = null;
	var $created_date     = null;
	var $rating           = null;
	var $featured         = null; 
	var $published        = null; 
 	var $published_up     = null; 
	var $published_down   = null;	
	var $preroll          = null;
	var $postroll         = null;

	public function __construct( &$db ) {
		parent::__construct( '#__yendifvideoshare_videos', 'id', $db );
	}

	public function check() {
		return true;
	}
	
}