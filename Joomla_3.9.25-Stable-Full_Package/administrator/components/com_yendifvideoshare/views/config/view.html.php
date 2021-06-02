<?php

/*
 * @version		$Id: view.html.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class YendifVideoShareViewConfig extends YendifVideoShareView {

    public function display( $tpl = null ) {
	
	    $model = $this->getModel();
		
		$this->item = $model->getItem();

		$options[] = JHTML::_('select.option', 'image_ratio', JText::_('YENDIF_VIDEO_SHARE_IMAGE_RATIO'));	
		$options[] = JHTML::_('select.option', 'image_ratio_crop', JText::_('YENDIF_VIDEO_SHARE_IMAGE_RATIO_CROP'));
		$options[] = JHTML::_('select.option', 'image_ratio_fill', JText::_('YENDIF_VIDEO_SHARE_IMAGE_RATIO_FILL'));
		$options[] = JHTML::_('select.option', 'image_ratio_no_zoom_in', JText::_('YENDIF_VIDEO_SHARE_IMAGE_RATIO_NO_ZOOM_IN'));
		$options[] = JHTML::_('select.option', 'image_ratio_no_zoom_out', JText::_('YENDIF_VIDEO_SHARE_IMAGE_RATIO_NO_ZOOM_OUT'));	
		$options[] = JHTML::_('select.option', 'image_ratio_x', JText::_('YENDIF_VIDEO_SHARE_IMAGE_RATIO_X'));	
		$options[] = JHTML::_('select.option', 'image_ratio_y', JText::_('YENDIF_VIDEO_SHARE_IMAGE_RATIO_Y'));			
		$this->resize_methods = JHTML::_('select.genericlist', $options, 'resize_method', '', 'value', 'text', $this->item->resize_method);
		
		JToolBarHelper::title(JText::_('YENDIF_VIDEO_SHARE'), 'yendifvideoshare');	
		JToolBarHelper::save('save', JText::_('YENDIF_VIDEO_SHARE_APPLY'));
		
		YendifVideoShareUtils::addSubMenu('config');
		
        parent::display($tpl);
		
    }
	
}