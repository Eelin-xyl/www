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
	
		$model = $this->getModel('videos');	
		
	    $view = $this->getView('videos', 'html');        	
        $view->setModel($model, true);		
		$view->setLayout('default');
		$view->display();
		
	}
	
	public function add() {
	
		YendifVideoShareUtils::checkToken();
				
		$model = $this->getModel('videos');
		
	    $view = $this->getView('videos', 'html');
        $model = $this->getModel('videos');		
        $view->setModel($model, true);		
		$view->setLayout('add');
		$view->add();
		
	}
	
	public function edit() {
	
		YendifVideoShareUtils::checkToken();
		
		$model = $this->getModel('videos');
		
	    $view = $this->getView('videos', 'html');        	
        $view->setModel($model, true);		
		$view->setLayout('edit');
		$view->edit();
		
	}
	
	public function delete() {
	
		YendifVideoShareUtils::checkToken();
		
		$model = $this->getModel('videos');
	 	$model->delete();
		
	}
	
	public function save()	{
	
		YendifVideoShareUtils::checkToken();
		
		$model = $this->getModel('videos');
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
		
		$model = $this->getModel('videos');
	    $model->cancel();
		
	}
	
	public function publish() {
	
		YendifVideoShareUtils::checkToken();
		
		$model = $this->getModel('videos');
        $model->publish();
		
    }
	
    public function unpublish() {
	
        $this->publish();
		
    }
	
	public function saveorder() {
	
		$model = $this->getModel('videos');
	  	$model->saveOrder();
				
	}
	
	public function orderdown() {
	
		$model = $this->getModel('videos');
	  	$model->move( 1 );	
			
	}
	
	public function orderup() {
	
		$model = $this->getModel('videos');
	  	$model->move( -1 );	
			
	}
	
	public function recreate() {
	
		$model = $this->getModel('videos');
		$model->recreate();
		
	}
		
}