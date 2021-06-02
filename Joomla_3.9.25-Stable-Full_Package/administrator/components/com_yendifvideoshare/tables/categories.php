<?php

/*
 * @version		$Id: categories.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class YendifVideoShareTableCategories extends JTable {

	var $id               = null;
	var $name             = null;
  	var $alias            = null;
  	var $parent           = null;
	var $description      = null;
  	var $image            = null;	
  	var $access           = null;
  	var $ordering         = null;
	var $meta_keywords    = null;
  	var $meta_description = null;
	var $created_date     = null;
  	var $published        = null;  
	
	public function __construct( &$db ) {
		parent::__construct( '#__yendifvideoshare_categories', 'id', $db );
	}

	public function check() {
		return true;
	}
	
}