<?php

/*
 * @version		$Id: view.html.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class YendifVideoShareViewCategories extends YendifVideoShareView {

    public function display( $tpl = null ) {
	
		$app = JFactory::getApplication();	
		
		$option = $app->input->get('option');
		$view = $app->input->get('view');
		
	    $model = $this->getModel();
		
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = $app->getUserStateFromRequest($option.$view.'.limitstart', 'limitstart', 0, 'int');
		$this->limitstart = $limit != 0 ? (floor($limitstart / $limit) * $limit) : 0;
		
		$this->items = $model->getItems();
		$this->pagination = $model->getPagination();
		$this->lists = $model->getLists();
		
		JToolBarHelper::title(JText::_('YENDIF_VIDEO_SHARE'), 'yendifvideoshare');
		JToolBarHelper::custom('recreate', 'refresh', 'refresh', JText::_('YENDIF_VIDEO_SHARE_RECREATE_IMAGES'), true);
		JToolBarHelper::publishList('publish', JText::_('YENDIF_VIDEO_SHARE_PUBLISH'));
        JToolBarHelper::unpublishList('unpublish', JText::_('YENDIF_VIDEO_SHARE_UNPUBLISH'));
        JToolBarHelper::deleteList(JText::_('YENDIF_VIDEO_SHARE_ARE_YOU_SURE_WANT_TO_DELETE_SELECTED_ITEMS_CATEGORY'),'delete', JText::_('YENDIF_VIDEO_SHARE_DELETE'));
        JToolBarHelper::editList('edit', JText::_('YENDIF_VIDEO_SHARE_EDIT'));
        JToolBarHelper::addNew('add', JText::_('YENDIF_VIDEO_SHARE_NEW'));
		
		YendifVideoShareUtils::addSubMenu('categories');
		
        parent::display($tpl);
		
    }
	
	public function add( $tpl = null ) {
	
		$model = $this->getModel();
		
		$parent_options[] = JHTML::_('select.option', 0, JText::_('YENDIF_VIDEO_SHARE_ROOT'));
		$categories = $model->getCategories();
		foreach( $categories as $category ) {
			$category->treename = JString::str_ireplace('&#160;', '-', $category->treename);			
			$parent_options[] = JHTML::_('select.option', $category->id, $category->treename );
		}
		$this->categories = JHTML::_('select.genericlist', $parent_options, 'parent', '', 'value', 'text', 0);		 
		
		JToolBarHelper::title(JText::_('YENDIF_VIDEO_SHARE_ADD_NEW_CATEGORY'), 'yendifvideoshare');
		JToolBarHelper::apply('apply', JText::_('YENDIF_VIDEO_SHARE_APPLY'));
		JToolBarHelper::save('save', JText::_('YENDIF_VIDEO_SHARE_SAVE'));
		JToolBarHelper::save2new('save2new', JText::_('YENDIF_VIDEO_SHARE_SAVE_AND_NEW'));
        JToolBarHelper::cancel('cancel', JText::_('YENDIF_VIDEO_SHARE_CANCEL'));
		
        parent::display($tpl);
		
    }
	
	public function edit( $tpl = null ) {
	
	    $model = $this->getModel();
		
		$this->item = $model->getItem();
		
		$parent_options[] = JHTML::_('select.option', 0, JText::_('YENDIF_VIDEO_SHARE_ROOT'));
		$categories = $model->getCategories( $this->item->name );
		foreach( $categories as $category ) {
			$category->treename   = JString::str_ireplace('&#160;', '-', $category->treename);			
			$parent_options[] = JHTML::_('select.option', $category->id, $category->treename );
		}
		$this->categories = JHTML::_('select.genericlist', $parent_options, 'parent', '', 'value', 'text', $this->item->parent);		 

		JToolBarHelper::title(JText::_('YENDIF_VIDEO_SHARE_EDIT_CATEGORY'), 'yendifvideoshare');
		JToolBarHelper::apply('apply', JText::_('YENDIF_VIDEO_SHARE_APPLY'));
		JToolBarHelper::save('save', JText::_('YENDIF_VIDEO_SHARE_SAVE'));
		JToolBarHelper::save2new('save2new', JText::_('YENDIF_VIDEO_SHARE_SAVE_AND_NEW'));
        JToolBarHelper::cancel('cancel', JText::_('YENDIF_VIDEO_SHARE_CANCEL'));
		
        parent::display($tpl);
		
    }
	
}