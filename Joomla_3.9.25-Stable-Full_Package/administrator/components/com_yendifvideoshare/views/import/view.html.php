<?php

/*
 * @version		$Id: view.html.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class YendifVideoShareViewImport extends YendifVideoShareView {

    public function display( $tpl = null ) {
	
	    $app = JFactory::getApplication();	
		
		$option = $app->input->get('option');
		$view = $app->input->get('view');
		
	    $model = $this->getModel();
		
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = $app->getUserStateFromRequest($option.$view.'.limitstart', 'limitstart', 0, 'int');
		$this->limitstart = $limit != 0 ? (floor($limitstart / $limit) * $limit) : 0;
		
		$this->config = YendifVideoShareUtils::getConfig();
		$this->lists = $model->getLists();
		
		JToolBarHelper::title( JText::_('YENDIF_VIDEO_SHARE'), 'yendifvideoshare' );
		YendifVideoShareUtils::addSubMenu( 'import' );		
		
        parent::display($tpl);
		
    }
	
}