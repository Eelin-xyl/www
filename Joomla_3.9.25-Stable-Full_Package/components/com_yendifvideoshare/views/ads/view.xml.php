<?php
/*
 * @version		$Id: view.xml.php 3.3.0 2019-01-10 $
 * @package		All Video Share
 * @copyright   Copyright (C) 2012-2019 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class YendifVideoShareViewAds extends YendifVideoShareView {

    public function vast( $tpl = null ) {        
        $this->setHeader();
        
        $config = JFactory::getConfig();
        $this->sitename = $config->get( 'sitename' );
		
		$this->config = YendifVideoShareUtils::getConfig();
        
        $model = $this->getModel();        
        $this->ad = $model->getAd();

        if ( empty( $this->ad ) ) {
            return;
        }
        
        parent::display( $tpl );		
    }

    public function vmap( $tpl = null ) {         
        $this->setHeader();

        $model = $this->getModel();
        $this->player = $model->getPlayer();
		$this->config = YendifVideoShareUtils::getConfig();


        $this->player->hasPreroll = 0;
        if ( $this->player->preroll ) {
            $this->player->prerollId = $model->getPrerollId();
            if ( ! empty( $this->player->prerollId ) && ( $this->config->enable_adverts == 'preroll_only' || $this->config->enable_adverts == 'both' ) ) {
                $this->player->hasPreroll = 1;
            }
        }

        $this->player->hasPostroll = 0;
        if ( $this->player->postroll ) {
            $this->player->postrollId = $model->getPostrollId();
            if ( ! empty( $this->player->prerollId ) && ( $this->config->enable_adverts == 'postroll_only' || $this->config->enable_adverts == 'both' ) ) {
                $this->player->hasPostroll = 1;
            }
        }
        
        parent::display( $tpl );		
    }

    public function setHeader() {
        $u = JURI::getInstance( JURI::base() );
		if ( $u->getScheme() ) {
			$origin = $u->getScheme() . '://imasdk.googleapis.com';
        } else {
            $origin = 'https://imasdk.googleapis.com';
        }

        $app = JFactory::getApplication();
        $app->setHeader( 'Access-Control-Allow-Origin', $origin );
        $app->setHeader( 'Access-Control-Allow-Credentials', 'true' );
    }
	    
}