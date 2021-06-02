<?php

/*
 * @version		$Id: user.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class YendifVideoShareControllerUser extends YendifVideoShareController {
	
	public function user() {	
		
	    $model = $this->getModel('user');
		
	    $view = $this->getView('user', 'html');        	
        $view->setModel($model, true);		
		$view->setLayout('default');
		$view->display();
		
	}
	
	public function videos() {	
		
	    $model = $this->getModel('user');
		
	    $view = $this->getView('user', 'html');        	
        $view->setModel($model, true);		
		$view->setLayout('videos');
		$view->videos();
		
	}
	
	public function add() {
	
		YendifVideoShareUtils::checkToken();
				
	    $model = $this->getModel('user');
		
	    $view = $this->getView('user', 'html');       	
        $view->setModel($model, true);		
		$view->setLayout('add');
		$view->add();
		
	}
	
	public function edit() {	
	
		YendifVideoShareUtils::checkToken();
		
		$model = $this->getModel('user');
		
	    $view = $this->getView('user', 'html');        		
        $view->setModel($model, true);		
		$view->setLayout('edit');
		$view->edit();
		
	}
	
	public function save() {	
		
		YendifVideoShareUtils::checkToken();
		
		$model = $this->getModel('user');
		$model->save();
		
	}
	
	public function delete() {
			
		YendifVideoShareUtils::checkToken();
		
		$model = $this->getModel('user');
		$model->delete();
		
	}
			
}