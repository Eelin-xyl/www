<?php

/*
 * @version		$Id: categories.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.form.formfield');
	
class JFormFieldCategories extends JFormField {

	protected $type = 'categories';

	public function getInput() {
	
		$db = JFactory::getDBO();

		$query = 'SELECT * FROM #__yendifvideoshare_categories WHERE published = 1';
		$db->setQuery( $query );
		$mitems = $db->loadObjectList();
		$children = array();
		if( $mitems ) {
			foreach( $mitems as $v ) {
				$v->title = $v->name;
				$v->parent_id = $v->parent;
				$pt = $v->parent;
				$list = @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v );
				$children[$pt] = $list;
			}
		}
		$list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0 );
		$items = array();
		
		foreach( $list as $item ) {
			$item->treename = JString::str_ireplace('&#160;', '-', $item->treename);
			$items[] = JHTML::_('select.option',  $item->id, $item->treename );
		}
		
		array_unshift($items, JHTML::_('select.option', 0, '- '.JText::_('YENDIF_VIDEO_SHARE_DISPLAY_ALL_CATEGORIES').' -', 'value', 'text'));
		
		return JHTML::_('select.genericlist',  $items, $this->name, 'class="inputbox"', 'value', 'text', $this->value);	
			
	}
	
}