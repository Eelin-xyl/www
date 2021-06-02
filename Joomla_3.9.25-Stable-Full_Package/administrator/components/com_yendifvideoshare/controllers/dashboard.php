<?php

/*
 * @version		$Id: dashboard.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class YendifVideoShareControllerDashboard extends YendifVideoShareController {

   public function __construct() { 
          
        $this->item_type = 'Default';
        parent::__construct();
		
    }
	
	public function dashboard() {
	
		$model = $this->getModel('dashboard');
		
	    $view = $this->getView('dashboard', 'html');       		
        $view->setModel($model, true);		
		$view->setLayout('default');
		$view->display();
		
	}
		
}