<?php

/*
 * @version		$Id: videos.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class YendifVideoShareTableLikesDislikes extends JTable {

	var $id        = null;
  	var $videoid   = null;
  	var $userid    = null;
	var $sessionid = null;
	var $likes     = null;
  	var $dislikes  = null;  	 	

	public function __construct( &$db ) {
		parent::__construct( '#__yendifvideoshare_likes_dislikes', 'id', $db );
	}

	public function check() {
		return true;
	}
	
}