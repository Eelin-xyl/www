<?php

/*
 * @version		$Id: view.html.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class YendifVideoShareViewDashboard extends YendifVideoShareView {

    public function display( $tpl = null ) {
	
	    $model = $this->getModel();
		
		$this->server_details = $model->getServerDetails();
		$this->latest_videos = $model->getLatestVideos();
		$this->popular_videos = $model->getPopularVideos();
		
		JToolBarHelper::title( JText::_('YENDIF_VIDEO_SHARE'), 'yendifvideoshare' );
		
		if( JFactory::getUser()->authorise( 'core.admin', 'com_yendifvideoshare' ) ) {
        	JToolBarHelper::preferences( 'com_yendifvideoshare' );
    	}
		
		YendifVideoShareUtils::addSubMenu( 'dashboard' );		
		
        parent::display($tpl);
		
    }
	
}