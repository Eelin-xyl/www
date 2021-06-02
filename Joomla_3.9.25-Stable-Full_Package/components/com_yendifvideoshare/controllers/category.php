<?php

/*
 * @version		$Id: category.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class YendifVideoShareControllerCategory extends YendifVideoShareController {
	
	public function category() {
	
		$document = JFactory::getDocument();
		$vType = $document->getType();
		
	    $model = $this->getModel('category');			
	    $view = $this->getView('category', $vType);        	
        $view->setModel($model, true);		
		$view->setLayout('default');
		$view->display();
		
	}
			
}