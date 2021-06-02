<?php 

/*
 * @version		$Id: adverts.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class YendifVideoShareControllerAdverts extends YendifVideoShareController {

	public function adverts() {
	
		$model = $this->getModel('adverts');	
		
	    $view = $this->getView('adverts', 'html');        	
        $view->setModel($model, true);		
		$view->setLayout('default');
		$view->display();
		
	}
	
	public function add() {
	
		YendifVideoShareUtils::checkToken();
			
		$model = $this->getModel('adverts');
		
	    $view = $this->getView('adverts', 'html');
        $model = $this->getModel('adverts');		
        $view->setModel($model, true);		
		$view->setLayout('add');
		$view->add();
		
	}
	
	public function edit() {
	
		YendifVideoShareUtils::checkToken();
		
		$model = $this->getModel('adverts');
		
	    $view = $this->getView('adverts', 'html');        	
        $view->setModel($model, true);		
		$view->setLayout('edit');
		$view->edit();
		
	}
	
	public function delete() {
	
		YendifVideoShareUtils::checkToken();
		
		$model = $this->getModel('adverts');
	 	$model->delete();
		
	}
	
	public function save()	{
	
		YendifVideoShareUtils::checkToken();
		
		$model = $this->getModel('adverts');
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
		
		$model = $this->getModel('adverts');
	    $model->cancel();
		
	}
	
	public function publish() {
		YendifVideoShareUtils::checkToken();
		
		
		$model = $this->getModel('adverts');
        $model->publish();
		
    }
	
    public function unpublish() {
	
        $this->publish();
		
    }
		
}