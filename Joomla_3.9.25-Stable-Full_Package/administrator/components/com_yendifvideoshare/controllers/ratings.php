<?php

/*
 * @version		$Id: ratings.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class YendifVideoShareControllerRatings extends YendifVideoShareController {
	
	public function ratings() {
	
		$model = $this->getModel('ratings');	
		
	    $view = $this->getView('ratings', 'html');        	
        $view->setModel($model, true);		
		$view->setLayout('default');
		$view->display();
		
	}
	
	public function add() {
	
		YendifVideoShareUtils::checkToken();
		
		$model = $this->getModel('ratings');
		
	    $view = $this->getView('ratings', 'html');        		
        $view->setModel($model, true);		
		$view->setLayout('add');
		$view->add();
		
	}
	
	public function edit()	{
	
		YendifVideoShareUtils::checkToken();
			
		$model = $this->getModel('ratings');	
		
	    $view = $this->getView('ratings', 'html');        	
        $view->setModel($model, true);		
		$view->setLayout('edit');
		$view->edit();
		
	}
		
	public function delete() {
	
		YendifVideoShareUtils::checkToken();
		
		$model = $this->getModel('ratings');
	 	$model->delete();
		
	}
	
	public function save() {
	
		YendifVideoShareUtils::checkToken();
		
		$model = $this->getModel('ratings');
	  	$model->save();
		
	}
	
	public function apply() {
	
		$this->save();
		
	}
	
	public function cancel() {
	
		YendifVideoShareUtils::checkToken();
		
		$model = $this->getModel('ratings');
	    $model->cancel();
		
	}
		
}