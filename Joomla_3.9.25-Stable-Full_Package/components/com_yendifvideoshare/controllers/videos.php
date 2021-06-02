<?php

/*
 * @version		$Id: videos.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access'); 

class YendifVideoShareControllerVideos extends YendifVideoShareController {
	
	public function videos() {
	
	    $document = JFactory::getDocument();
		$vType = $document->getType();
		
	    $model = $this->getModel('videos');			
	    $view = $this->getView('videos', $vType);        	
        $view->setModel($model, true);		
		$view->setLayout('default');
		$view->display();
		
	}
			
}