<?php

/*
 * @version		$Id: categories.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class YendifVideoShareControllerCategories extends YendifVideoShareController {
	
	public function categories() {
	
		$model = $this->getModel('categories');	
		
	    $view = $this->getView('categories', 'html');        	
        $view->setModel($model, true);		
		$view->setLayout('default');
		$view->display();
		
	}
	
	public function add() {
	
		YendifVideoShareUtils::checkToken();
			
		$model = $this->getModel('categories');
		
	    $view = $this->getView('categories', 'html');        		
        $view->setModel($model, true);		
		$view->setLayout('add');
		$view->add();
		
	}
	
	public function edit() {
	
		YendifVideoShareUtils::checkToken();
			
		$model = $this->getModel('categories');	
		
	    $view = $this->getView('categories', 'html');        	
        $view->setModel($model, true);		
		$view->setLayout('edit');
		$view->edit();
		
	}
		
	public function delete() {
	
		YendifVideoShareUtils::checkToken();
		
		$model = $this->getModel('categories');
	 	$model->delete();
		
	}
	
	public function publish() {
	
		YendifVideoShareUtils::checkToken();
		
		$model = $this->getModel('categories');
        $model->publish();
		
    }
	
    public function unpublish() {
	
        $this->publish();
		
    }	
	
	public function save() {
	
		YendifVideoShareUtils::checkToken();
		
		$model = $this->getModel('categories');
	  	$model->save();
		
	}
	
	public function apply() {
	
		$this->save();
		
	}
	
	public function save2new() {
	
		$this->save();
		
	}
	
	public function cancel() {
	
		YendifVideoShareUtils::checkToken();
		
		$model = $this->getModel('categories');
	    $model->cancel();
		
	}
	
	public function saveorder() {
	
		$model = $this->getModel('categories');
	  	$model->saveOrder();
			
	}
	
	public function orderup() {
	
		$model = $this->getModel('categories');
		$model->order( -1 );
		
	}
	
	public function orderdown() {	

		$model = $this->getModel('categories');
		$model->order( 1 );
		
	}
	
	public function recreate() {
	
		$model = $this->getModel('categories');
		$model->recreate();
		
	}
		
}