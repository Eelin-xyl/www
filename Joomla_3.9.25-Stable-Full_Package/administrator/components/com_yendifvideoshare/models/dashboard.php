<?php

/*
 * @version		$Id: dashboard.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class YendifVideoShareModelDashboard extends YendifVideoShareModel {
	
	public function getServerDetails() { 
	 
		$params = array(        
			array(
				'name'  => JText::_('YENDIF_VIDEO_SHARE_ALLOW_FILE_UPLOADS'),
		        'value' => ini_get('file_uploads') ? JText::_('YENDIF_VIDEO_SHARE_YES') : JText::_('YENDIF_VIDEO_SHARE_NO')
			),
			array(
				'name'  => JText::_('YENDIF_VIDEO_SHARE_UPLOAD_MAX_FILESIZE'),
				'value' => ini_get('upload_max_filesize')
			),
			array(
				'name'  => JText::_('YENDIF_VIDEO_SHARE_MAX_INPUT_TIME'),
		        'value' => ini_get('max_input_time')
			),
			array(
				'name'  => JText::_('YENDIF_VIDEO_SHARE_MEMORY_LIMIT'),
		        'value' => ini_get('memory_limit')
			),
			array(
				'name'  => JText::_('YENDIF_VIDEO_SHARE_MAX_EXECUTION_TIME'),
		        'value' => ini_get('max_execution_time')
			),
			array(
				'name'  => JText::_('YENDIF_VIDEO_SHARE_POST_MAX_SIZE'),
		        'value' => ini_get('post_max_size')
			),
			array(
				'name'  => JText::_('YENDIF_VIDEO_SHARE_UPLOAD_DIRECTORY_PERMISSION'),
				'value' => (is_writable(JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR)) ? JText::_('YENDIF_VIDEO_SHARE_YES') : JText::_('YENDIF_VIDEO_SHARE_NO')
			)
		);
          
    	return $params;
		
	}
	
	public function getLatestVideos() {
	
         $db = JFactory::getDBO();
		 
         $query  = "SELECT v.*, c.name as category FROM #__yendifvideoshare_videos AS v";
		 $query .= " LEFT JOIN #__yendifvideoshare_categories AS c ON v.catid = c.id";
		 $query .= " WHERE v.title <> ''";
		 $query .= " ORDER BY v.id DESC LIMIT 10";
         $db->setQuery( $query );
         $items = $db->loadObjectList();
		 
         return $items;
		 
	}
	
	public function getPopularVideos() {
	
         $db = JFactory::getDBO();
		 
		 $query  = "SELECT v.*, c.name as category FROM #__yendifvideoshare_videos AS v";
		 $query .= " LEFT JOIN #__yendifvideoshare_categories AS c ON v.catid = c.id";
		 $query .= " WHERE v.title <> '' AND v.views != 0 ORDER BY v.views DESC LIMIT 10";
         $db->setQuery( $query );
         $items = $db->loadObjectList();
		 
         return $items;
		 
	}
	
}