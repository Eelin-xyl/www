<?php

/*
 * @version		$Id: script.yendifvideoshare.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class Com_YendifVideoShareInstallerScript {

	function postflight( $type, $parent ) {
	
		$db = JFactory::getDBO();
		$status = new JObject();
		$status->modules = array();
		$status->plugins = array();
		$src = $parent->getParent()->getPath('source');
        $manifest = $parent->getParent()->manifest;		
		$nullDate = $db->quote( $db->getNullDate() );
		$date = JFactory::getDate();
		$nowDate = $date->toSql();
		
		$plugins = $manifest->xpath('plugins/plugin');
        foreach( $plugins as $plugin ) {
            $name = (string) $plugin->attributes()->plugin;
            $group = (string) $plugin->attributes()->group;
            $path = $src.'/plugins/'.$group.'/'.$name;
            $installer = new JInstaller;
            $result = $installer->install( $path );
            $query = "UPDATE #__extensions SET enabled=1 WHERE type='plugin' AND element=".$db->Quote( $name )." AND folder=".$db->Quote( $group );
            $db->setQuery( $query );
            $db->query();
            $status->plugins[] = array('name' => $name, 'group' => $group, 'result' => $result);
        }
		
        $modules = $manifest->xpath('modules/module');
        foreach( $modules as $module ) {
            $name = (string) $module->attributes()->module;
            $client = (string) $module->attributes()->client;
            $path = $src.'/modules/'.$name;
            $installer = new JInstaller;
            $result = $installer->install( $path );
            $status->modules[] = array( 'name' => $name, 'client' => $client, 'result' => $result );
        }
		
		$query = "SELECT COUNT(*) FROM #__yendifvideoshare_options";
		$db->setQuery($query);
		$num = $db->loadResult();
	if( $num == 0 ) {
		/** Insert default config data **/	
		$insertQuery = "INSERT INTO `#__yendifvideoshare_options` (`id`, `name`, `value`) VALUES
			(1, 'id', '1'),
			(2, 'allow_guest_like', '0'),
			(3, 'allow_guest_rating', '0'),
			(4, 'allow_rtmp_upload', '1'),
			(5, 'allow_hls_dash', '1'),
			(6, 'allow_guest_like', '1'),
			(7, 'allow_subtitle', '1'),
			(8, 'allow_upload', '1'),
			(9, 'allow_youtube_upload', '0'),
			(10, 'allowed_extensions', 'jpg,jpeg,png,gif,flv,mp4,m4v,webm,ogg,ogv,vtt'),
			(11, 'analytics', ''),
			(12, 'autoplay', '1'),
			(13, 'autoplaylist', '1'),
			(14, 'autopublish', '1'),
			(15, 'ad_engine', 'custom'),
			(16, 'bootstrap_version', '2'),
			(17, 'can_skip_adverts', '1'),
			(18, 'comments', 'none'),
			(19, 'controlbar', '1'),
			(20, 'currenttime', '1'),
			(21, 'default_image', '".JURI::root( true ) ."/media/yendifvideoshare/assets/site/images/placeholder.jpg'),
			(22, 'download', '1'),
			(23, 'duration', '1'),
			(24, 'embed', '1'),
			(25, 'enable_adverts', 'none'),
			(26, 'enable_popup', '0'),
			(27, 'engine', 'html5'),
			(28, 'fb_app_id', ''),
			(29, 'fb_color_scheme', 'light'),
			(30, 'fb_comments_width', '0'),
			(31, 'fb_post_count', '5'),
			(32, 'fb_show_count', '0'),
			(33, 'feed_icon', '".JURI::root( true ) ."/media/yendifvideoshare/assets/site/images/rss.png'),
			(34, 'feed_limit', '5'),
			(35, 'feed_name', 'My Feed'),
			(36, 'feed_search', '1'),
			(37, 'fullscreen', '1'),
			(38, 'gallery_thumb_height', '80'),
			(39, 'gallery_thumb_width', '145'),
			(40, 'google_api_key', ''),
			(41, 'ignored_extensions', 'php,txt'),
			(42, 'illegal_mime_types', 'text/html'),
			(43, 'vasturl', ''),
			(44, 'jcomments_show_count', '0'),
			(45, 'jquery', 'none'),
			(46, 'keyboard', '1'),
			(47, 'komento_show_count', '0'),
			(48, 'legal_mime_types', 'image/jpeg,image/gif,image/png,video/mp4,video/webm,video/ogg'),
			(49, 'license', ''),
			(50, 'logo', ''),
			(51, 'loop', '0'),
			(52, 'max_upload_size', '104857600'),
			(53, 'no_of_cols', '3'),
			(54, 'no_of_rows', '3'),
			(55, 'playbtn', '1'),
			(56, 'player_height', '360'),
			(57, 'player_width', '640'),
			(58, 'playlist_desc_limit', '100'),
			(59, 'playlist_height', '150'),
			(60, 'playlist_position', 'right'),
			(61, 'playlist_title_limit', '75'),
			(62, 'playlist_width', '250'),
			(63, 'playpause', '1'),
			(64, 'poster_image_height', '450'),
			(65, 'poster_image_width', '600'),
			(66, 'progress', '1'),
			(67, 'progress_bar_color', '#00b1ff'),
			(68, 'ratio', '0.5625'),
			(69, 'resize_method', 'image_ratio'),
			(70, 'responsive', '1'),
			(71, 'responsive_css', ''),
			(72, 'schedule_video_publishing', '0'),
			(73, 'sef_cat', 'category'),
			(74, 'sef_sptr', '0'),
			(75, 'sef_position', '0'),
			(76, 'sef_video', '0'),
			(77, 'sef_video_prefix', 'video'),
			(78, 'share', '1'),
			(79, 'share_script', ''),
			(80, 'show_adverts_timeframe', '1'),
			(81, 'show_category', '1'),
			(82, 'show_date', '1'),
			(83, 'show_description', '1'),
			(84, 'show_excerpt', '0'),
			(85, 'show_feed', '1'),
			(86, 'show_media_count', '1'),
			(87, 'show_rating', '1'),
			(88, 'show_likes', '1'),
			(89, 'show_related', '1'),
			(90, 'show_search', '1'),
			(91, 'show_skip_adverts_on', '1'),
			(92, 'show_title', '1'),
			(93, 'show_user', '1'),
			(94, 'show_views', '1'),
			(95, 'show_views', '1'),
			(96, 'show_consent', '0'),
			(97, 'theme', 'black'),
			(98, 'thumb_height', '85'),
			(99, 'thumb_width', '140'),
			(100, 'video_desc_limit', '0'),
			(101, 'volume', '50'),
			(102, 'volumebtn', '1');";
		 	$db->setQuery($insertQuery);
			$db->execute();
			
			// Insert default category
			$query = "INSERT INTO `#__yendifvideoshare_categories` (`id`, `name`, `alias`, `parent`, `image`, `access`, `ordering`, `meta_keywords`, `meta_description`, `created_date`, `published`) VALUES (1, 'My First Category', 'my-first-category', 0, '', '', 0, '', '', '".$nowDate."', 1)";
			$db->setQuery( $query );
			$db->Query();
			
			// Insert default video
			$userid = JFactory::getUser()->get('id');			
			$query = "INSERT INTO `#__yendifvideoshare_videos` (`id`, `title`, `alias`, `catid`, `description`, `type`, `youtube`, `mp4`, `rtmp`, `flash`, `webm`, `ogg`, `thirdparty`, `image`, `captions`, `duration`, `userid`, `access`, `ordering`, `views`, `meta_keywords`, `meta_description`, `created_date`, `rating`, `featured`, `published`,`published_up`, `published_down`) VALUES(1, 'My First Video', 'my-first-video', 1, '', 'youtube', 'http://www.youtube.com/watch?v=9edlC2lMDIQ', '', '', '', '', '', '', 'http://img.youtube.com/vi/9edlC2lMDIQ/0.jpg', '', '', ".$userid.", '', 1, 0, '', '', '".$nowDate."', 0.00, 0, 1,'".$nowDate."', ".$nullDate.")";
			$db->setQuery( $query );
			$db->Query();
		}		
			$this->installationResults($status);
 }
	
	public function update( $type ) {
	
		$db = JFactory::getDBO();
		$fields_categories = $db->getTableColumns('#__yendifvideoshare_categories');
		$fields_videos = $db->getTableColumns('#__yendifvideoshare_videos');
		$fields_config = $db->getTableColumns('#__yendifvideoshare_config');
		$fields_ratings = $db->getTableColumns('#__yendifvideoshare_ratings');
		
		$date = JFactory::getDate();
		$nowDate = $date->toSql();						
		
		// Version 1.1.0
		if( ! array_key_exists( 'feed_icon', $fields_config ) ) {			
			$query = "ALTER TABLE #__yendifvideoshare_config
				ADD `fb_show_count` TINYINT(4) NOT NULL DEFAULT '1' AFTER `fb_post_count`,
				ADD `feed_icon` VARCHAR(255) NOT NULL DEFAULT '" . JURI::root( true ) . "/media/yendifvideoshare/assets/site/images/rss.png' AFTER `fb_show_count`,
				ADD `feed_limit` INT(5) NOT NULL DEFAULT '5' AFTER `feed_icon`,
				ADD `feed_name` VARCHAR(50) NOT NULL DEFAULT 'My Feed' AFTER `feed_limit`,
				ADD `feed_search` TINYINT(4) NOT NULL DEFAULT '1' AFTER `feed_name`,
				ADD `jcomments_show_count` TINYINT(4) NOT NULL DEFAULT '1' AFTER `illegal_mime_types`,
				ADD `komento_show_count` TINYINT(4) NOT NULL DEFAULT '1' AFTER `keyboard`,
				ADD `show_media_count` TINYINT(4) NOT NULL DEFAULT '1' AFTER `show_description`,
				ADD `schedule_video_publishing` TINYINT(4) NOT NULL DEFAULT '0' AFTER `responsive_css`,
				ADD `allow_subtitle` TINYINT(4) NOT NULL DEFAULT '1' AFTER `id`,
				ADD `allow_rtmp_upload` TINYINT(4) NOT NULL DEFAULT '1' AFTER `allow_subtitle`,
				ADD `allow_youtube_upload` TINYINT(4) NOT NULL DEFAULT '1' AFTER `allow_rtmp_upload`,
				ADD `sef_cat` VARCHAR(255) NOT NULL DEFAULT 'category' AFTER `volumebtn`,		
				ADD `sef_video` TINYINT(4) NOT NULL  DEFAULT '0' AFTER `sef_cat`,
				ADD `sef_video_prefix` VARCHAR(255) NOT NULL  DEFAULT 'video' AFTER `sef_video`,			
				ADD `sef_sptr` TINYINT(4) NOT NULL DEFAULT '0' AFTER `sef_video_prefix`,
				ADD `sef_position` TINYINT(4) NOT NULL DEFAULT '0' AFTER `sef_sptr`";
			$db->setQuery( $query );
			$db->query();			
		}		
		
		if( ! array_key_exists( 'published_up', $fields_videos ) ) {					
			$query = "ALTER TABLE #__yendifvideoshare_videos ADD `published_up` TIMESTAMP NOT NULL DEFAULT '$nowDate' AFTER `published`";
			$db->setQuery( $query );
			$db->query();			
		}
		
		// Version 1.2.0
		if( !array_key_exists( 'enable_popup', $fields_config ) ) {		
			$query = "UPDATE #__yendifvideoshare_videos SET `created_date` = '$nowDate' WHERE `youtube` ='http://www.youtube.com/watch?v=cfaE_mcixpQ' AND id=1";
			$db->setQuery( $query );
			$db->query();
			
			$query = "ALTER TABLE #__yendifvideoshare_config
				ADD `enable_popup` TINYINT(4) NOT NULL DEFAULT '0' AFTER `embed`,
				ADD `show_feed` TINYINT(4) NOT NULL DEFAULT '1' AFTER `show_description`,
				ADD `show_likes` TINYINT(4) NOT NULL DEFAULT '1' AFTER `show_feed`,
				ADD `allow_guest_like` TINYINT(4) NOT NULL DEFAULT '0' AFTER `id`,
				ADD `allow_guest_rating` TINYINT(4) NOT NULL DEFAULT '0' AFTER `allow_guest_like`,
				ADD `video_desc_limit` int(5) NOT NULL DEFAULT '0' AFTER `thumb_width`";			
			$db->setQuery( $query );
			$db->query();
			
			$query = "CREATE TABLE IF NOT EXISTS `#__yendifvideoshare_likes_dislikes` (
				`id` int(5) NOT NULL AUTO_INCREMENT,
				`userid` int(5) NOT NULL,
				`videoid` int(5) NOT NULL,
				`sessionid` varchar(50) NOT NULL,
				`likes` int(10) NOT NULL,
				`dislikes` int(10) NOT NULL,		
				PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
			$db->setQuery( $query );
			$db->query();
		}		
				
		if( ! array_key_exists( 'published_down', $fields_videos ) ) {					
			$query = "ALTER TABLE #__yendifvideoshare_videos ADD `published_down` TIMESTAMP NOT NULL AFTER `published_up`";
			$db->setQuery( $query );
			$db->query();			
		}
		
		if( ! array_key_exists( 'sessionid', $fields_ratings ) ) {
			$query = "ALTER TABLE #__yendifvideoshare_ratings ADD `sessionid` VARCHAR(50) NOT NULL AFTER `videoid`";
			$db->setQuery( $query );
			$db->query();	
		}
		
		// Version 1.2.2
		$query  = "CREATE TABLE IF NOT EXISTS `#__yendifvideoshare_adverts` (
  			`id` INT(5) NOT NULL AUTO_INCREMENT,
  			`title` VARCHAR(255) NOT NULL,
			`cat_ids` TEXT NOT NULL,
  			`type` VARCHAR(25) NOT NULL,
  			`mp4` VARCHAR(255) NOT NULL,
			`link` VARCHAR(255) NOT NULL,
			`impressions` INT(10) NOT NULL,
			`clicks` INT(10) NOT NULL,
  			`published` TINYINT(4) NOT NULL,
  			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		$db->setQuery($query);
		$db->query();		
		
		if( ! array_key_exists( 'enable_adverts', $fields_config ) ) {					
			$query = "ALTER TABLE #__yendifvideoshare_config ADD `enable_adverts` VARCHAR(25) NOT NULL DEFAULT 'none' AFTER `embed`";
			$db->setQuery( $query );
			$db->query();			
		}		
		
		if( ! array_key_exists( 'show_adverts_timeframe', $fields_config ) ) {					
			$query = "ALTER TABLE #__yendifvideoshare_config ADD `show_adverts_timeframe` TINYINT(4) NOT NULL DEFAULT '1' AFTER `share_script`";
			$db->setQuery( $query );
			$db->query();			
		}
		
		if( ! array_key_exists( 'can_skip_adverts', $fields_config ) ) {					
			$query = "ALTER TABLE #__yendifvideoshare_config ADD `can_skip_adverts` TINYINT(4) NOT NULL DEFAULT '1' AFTER `autopublish`";
			$db->setQuery( $query );
			$db->query();			
		}
		
		if( ! array_key_exists( 'show_skip_adverts_on', $fields_config ) ) {					
			$query = "ALTER TABLE #__yendifvideoshare_config ADD `show_skip_adverts_on` INT(5) NOT NULL DEFAULT '5' AFTER `show_search`";
			$db->setQuery($query);
			$db->query();			
		}
		
		if( ! array_key_exists( 'preroll', $fields_videos ) ) {					
			$query = "ALTER TABLE #__yendifvideoshare_videos ADD `preroll` INT(10) NOT NULL DEFAULT '-1' AFTER `published_down`";
			$db->setQuery( $query );
			$db->query();			
		}
		
		if( ! array_key_exists( 'postroll', $fields_videos ) ) {					
			$query = "ALTER TABLE #__yendifvideoshare_videos ADD `postroll` INT(10) NOT NULL DEFAULT '-1' AFTER `preroll`";
			$db->setQuery( $query );
			$db->query();			
		}
		
		// Version 1.2.3	
		if( ! array_key_exists( 'download', $fields_config ) ) {					
			$query = "ALTER TABLE #__yendifvideoshare_config ADD `download` TINYINT(4) NOT NULL DEFAULT '0' AFTER `default_image`";
			$db->setQuery( $query );
			$db->query();			
		}
		
		if( ! array_key_exists( 'share', $fields_config ) ) {					
			$query = "ALTER TABLE #__yendifvideoshare_config ADD `share` TINYINT(4) NOT NULL DEFAULT '1' AFTER `schedule_video_publishing`";
			$db->setQuery( $query );
			$db->query();			
		}
		
		// Version 1.2.5	
		if( ! array_key_exists( 'bootstrap_version', $fields_config ) ) {					
			$query = "ALTER TABLE #__yendifvideoshare_config ADD `bootstrap_version` INT(5) NOT NULL DEFAULT '2' AFTER `autopublish`";
			$db->setQuery( $query );
			$db->query();			
		}
		
		// Version 1.2.5
		if( ! array_key_exists( 'google_api_key', $fields_config ) ) {					
			$query = "ALTER TABLE #__yendifvideoshare_config ADD `google_api_key` VARCHAR(255) NOT NULL AFTER `gallery_thumb_width`";
			$db->setQuery( $query );
			$db->query();			
		}
		
		if( ! array_key_exists( 'mp4_hd', $fields_videos ) ) {					
			$query = "ALTER TABLE #__yendifvideoshare_videos ADD `mp4_hd` VARCHAR(255) NOT NULL AFTER `mp4`";
			$db->setQuery( $query );
			$db->query();			
		}
		
		// Version 1.2.6
		if( ! array_key_exists( 'description', $fields_categories ) ) {					
			$query = "ALTER TABLE #__yendifvideoshare_categories ADD `description` TEXT NOT NULL AFTER `parent`";
			$db->setQuery( $query );
			$db->query();			
		}
		
		if( ! array_key_exists( 'show_excerpt', $fields_config ) ) {					
			$query = "ALTER TABLE #__yendifvideoshare_config ADD `show_excerpt` TINYINT(4) NOT NULL DEFAULT '0' AFTER `show_description`";
			$db->setQuery( $query );
			$db->query();			
		}	
		
		// Version 1.2.8
		if( ! array_key_exists ( 'show_consent', $fields_config ) ) {
			$query = "ALTER TABLE #__yendifvideoshare_config ADD `show_consent` TINYINT(4) NOT NULL DEFAULT '0' AFTER `sef_position`";
			$db->setQuery( $query );
			$db->query();
		}
            

		if( ! array_key_exists ( 'progress_bar_color', $fields_config ) ) {
			$query = "SELECT theme FROM `#__yendifvideoshare_config` WHERE id=1";
            $db->setQuery($query);
			$theme = $db->loadResult();
			
			if( $theme == "black" ) {
				$pbcolor = '#2b333f';
			} else {
				$pbcolor = '#00b1ff';
			}
			
			$query = "ALTER TABLE #__yendifvideoshare_config ADD `progress_bar_color` VARCHAR(25) NOT NULL DEFAULT '".$pbcolor."'  AFTER `show_consent`";
			$db->setQuery( $query );
			$db->query();
		}
		
		// Version 1.2.8		
		if( ! array_key_exists( 'hls', $fields_videos ) ) {					
			$query = "ALTER TABLE #__yendifvideoshare_videos ADD `hls` VARCHAR(255) NOT NULL AFTER `flash`";
			$db->setQuery( $query );
			$db->query();			
		}
		
		if( ! array_key_exists( 'dash', $fields_videos ) ) {					
			$query = "ALTER TABLE #__yendifvideoshare_videos ADD `dash` VARCHAR(255) NOT NULL AFTER `hls`";
			$db->setQuery( $query );
			$db->query();			
		}
		
		
		$sitePath = JPATH_SITE;					
		//remove unwanted files	
		$yendifplayerJSFile = $sitePath.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'yendifvideoshare'.DIRECTORY_SEPARATOR.'player'.DIRECTORY_SEPARATOR.'yendifplayer.js';
		$yendifplayerCSSFile = $sitePath.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'yendifvideoshare'.DIRECTORY_SEPARATOR.'player'.DIRECTORY_SEPARATOR.'yendifplayer.css';
		$yendifplayerSWFFile = $sitePath.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'yendifvideoshare'.DIRECTORY_SEPARATOR.'player'.DIRECTORY_SEPARATOR.'player.swf';
		
		if( JFile::exists($yendifplayerJSFile )) {
               JFile::delete( $yendifplayerJSFile );
         }
		 
		 if( JFile::exists($yendifplayerCSSFile )) {
               JFile::delete( $yendifplayerCSSFile );
         }
		 
		 if( JFile::exists($yendifplayerSWFFile )) {
               JFile::delete( $yendifplayerSWFFile );
         }
		
		
		$query = " CREATE TABLE IF NOT EXISTS `#__yendifvideoshare_options`(
				`id` BIGINT(20) NOT NULL AUTO_INCREMENT,
				`name` VARCHAR(255) NOT NULL,
				`value` LONGTEXT NOT NULL,
				 PRIMARY KEY (`id`)
				) ENGINE = MyISAM DEFAULT CHARSET = utf8;";
		$db->setQuery($query);
		$db->execute();	
		
		$query = "SELECT COUNT(*) FROM #__yendifvideoshare_options";
		$db->setQuery($query);
		$num = $db->loadResult();
		$fields_options = $db->getTableColumns('#__yendifvideoshare_options');	
		
		if ( $num == 0 ) {
			$fields_config = $db->getTableColumns('#__yendifvideoshare_config');
			
			if( ! array_key_exists( 'ad_engine', $fields_config ) ) {					
				$query = "ALTER TABLE #__yendifvideoshare_config ADD `ad_engine` VARCHAR(25) NOT NULL DEFAULT 'custom' AFTER `autopublish`";
				$db->setQuery( $query );
				$db->query();			
			}
	
			if( ! array_key_exists( 'vasturl', $fields_config ) ) {					
				$query = "ALTER TABLE #__yendifvideoshare_config ADD `vasturl` TEXT NOT NULL AFTER `illegal_mime_types`";
				$db->setQuery( $query );
				$db->query();			
			}
			
			
			$query = "SELECT * FROM #__yendifvideoshare_config WHERE id=1";
			$db->setQuery($query);
			$config_datas = $db->loadObject();
			
			$insertQuery = 'INSERT INTO `#__yendifvideoshare_options` (`id`, `name`, `value`) VALUES ';	
			$fields =array();
			$i = 1;
			foreach($config_datas as $key=>$value)	 {			
				$fields[] = '('.$i++.','.$db->quote($key).','.$db->quote($value).')';			
			}
			
			$insertQuery .= implode(',', $fields);
			$db->setQuery($insertQuery);
			$db->execute();

		}
		
		$query = "SELECT `name` FROM #__yendifvideoshare_options";			
		$db->setQuery($query);
		$options_values = $db->loadObjectList();	
		$options_value = array();
		 foreach ($options_values as $options) {
		 	 $options_value[] = $options->name;
		 }

		
			
	}
	
	public function uninstall( $parent ) {
	
		$db = JFactory::getDBO();
		$status = new JObject();
		$status->modules = array();
		$status->plugins = array();	
		$manifest = $parent->getParent()->manifest;
		
		$plugins = $manifest->xpath('plugins/plugin');
        foreach( $plugins as $plugin ) {
            $name  = (string) $plugin->attributes()->plugin;
            $group = (string) $plugin->attributes()->group;
            $query = "SELECT `extension_id` FROM #__extensions WHERE `type`='plugin' AND element = ".$db->Quote( $name )." AND folder = ".$db->Quote( $group );
            $db->setQuery($query);
            $extensions = $db->loadColumn();
            if( count( $extensions ) ) {
                foreach( $extensions as $id ) {
                    $installer = new JInstaller;
                    $result = $installer->uninstall('plugin', $id);
                }
                $status->plugins[] = array( 'name' => $name, 'group' => $group, 'result' => $result );
            }
            
        }
		
        $modules = $manifest->xpath('modules/module');
        foreach( $modules as $module ) {
            $name = (string) $module->attributes()->module;
            $client = (string) $module->attributes()->client;
            $db = JFactory::getDBO();
            $query = "SELECT `extension_id` FROM `#__extensions` WHERE `type`='module' AND element = ".$db->Quote( $name );
            $db->setQuery($query);
            $extensions = $db->loadColumn();
            if( count( $extensions ) ) {
                foreach( $extensions as $id ) {
                    $installer = new JInstaller;
                    $result = $installer->uninstall('module', $id);
                }
                $status->modules[] = array( 'name' => $name, 'client' => $client, 'result' => $result );
            }
            
        }
		
		// version 1.2.0 comments deletion, Komento and JComments
		$jcomments = JPATH_SITE . DIRECTORY_SEPARATOR .'components' . DIRECTORY_SEPARATOR . 'com_jcomments' . DIRECTORY_SEPARATOR . 'jcomments.php';
  		if( file_exists( $jcomments ) ) {
    		$db = JFactory::getDbo();
			$query = "DELETE #__jcomments, #__jcomments_objects FROM #__jcomments, #__jcomments_objects WHERE #__jcomments.object_group = #__jcomments_objects.object_group";
			$query .= " AND #__jcomments.object_group = 'com_yendifvideoshare'";
			$db->setQuery( $query );
			$db->execute();
  		}
		
		$komento = JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_komento' . DIRECTORY_SEPARATOR . 'bootstrap.php';
		if( file_exists( $komento ) ) {
			$query = "DELETE FROM #__komento_comments WHERE #__komento_comments.component = 'com_yendifvideoshare'";			
			$db->setQuery( $query );
			$db->execute();
		}
		
        $this->unInstallationResults( $status );
		
	}
	
	private function installationResults( $status ) {
	
		$language = JFactory::getLanguage();
        $language->load('com_yendifvideoshare'); ?>
        
  		<table class="table table-striped">
    	  <thead>
      		<tr>
        	  <th colspan="2"><?php echo JText::_('YENDIF_VIDEO_SHARE_EXTENSION'); ?></th>
        	  <th width="30%"><?php echo JText::_('YENDIF_VIDEO_SHARE_STATUS'); ?></th>
     		</tr>
    	  </thead>
    	  <tbody>
      		<tr>
        	  <td colspan="2"><?php echo 'Yendif Video Share - '.JText::_('YENDIF_VIDEO_SHARE_COMPONENT'); ?></td>
        	  <td><?php echo JText::_('YENDIF_VIDEO_SHARE_INSTALLED'); ?></td>
      		</tr>
      		<?php if( count( $status->modules ) ) : ?>
      			<tr>
        	  		<th><?php echo JText::_('YENDIF_VIDEO_SHARE_MODULE'); ?></th>
        	  		<th><?php echo JText::_('YENDIF_VIDEO_SHARE_CLIENT'); ?></th>
        	  		<th></th>
      			</tr>
      			<?php foreach( $status->modules as $module ) : ?>
      				<tr>
        	  			<td><?php echo $module['name']; ?></td>
        	  			<td><?php echo ucfirst($module['client']); ?></td>
        	  			<td><?php echo $module['result'] ? JText::_('YENDIF_VIDEO_SHARE_INSTALLED') : JText::_('YENDIF_VIDEO_SHARE_NOT_INSTALLED'); ?></td>
      				</tr>
      			<?php endforeach;?>
      		<?php endif;?>
      		
			<?php if( count( $status->plugins ) ) : ?>
      			<tr>
        	  		<th><?php echo JText::_('YENDIF_VIDEO_SHARE_PLUGIN'); ?></th>
        	  		<th><?php echo JText::_('YENDIF_VIDEO_SHARE_GROUP'); ?></th>
        	  		<th></th>
      			</tr>
      			<?php foreach( $status->plugins as $plugin ) : ?>
      				<tr>
       		  			<td><?php echo $plugin['name']; ?></td>
        	  			<td><?php echo ucfirst($plugin['group']); ?></td>
        	  			<td><?php echo $plugin['result'] ? JText::_('YENDIF_VIDEO_SHARE_INSTALLED') : JText::_('YENDIF_VIDEO_SHARE_NOT_INSTALLED'); ?></td>
      				</tr>
      			<?php endforeach; ?>
      		<?php endif; ?>
    	  </tbody>
  		</table>
	<?php }
	
	private function unInstallationResults( $status ) {
	
		$language = JFactory::getLanguage();
        $language->load('com_yendifvideoshare'); ?>
           
  		<table class="table table-striped">
    	  <thead>
      	    <tr>
        	  <th colspan="2"><?php echo JText::_('YENDIF_VIDEO_SHARE_EXTENSION'); ?></th>
        	  <th width="30%"><?php echo JText::_('YENDIF_VIDEO_SHARE_STATUS'); ?></th>
      		</tr>
    	  </thead>
    	  <tbody>
      		<tr>
        	  <td colspan="2"><?php echo 'Yendif Video Share - '.JText::_('YENDIF_VIDEO_SHARE_COMPONENT'); ?></td>
        	  <td><?php echo JText::_('YENDIF_VIDEO_SHARE_REMOVED'); ?></td>
      		</tr>
      		<?php if( count( $status->modules ) ) : ?>
      			<tr>
              		<th><?php echo JText::_('YENDIF_VIDEO_SHARE_MODULE'); ?></th>
              		<th><?php echo JText::_('YENDIF_VIDEO_SHARE_CLIENT'); ?></th>
        	  		<th></th>
      			</tr>
      			<?php foreach( $status->modules as $module ) : ?>
      				<tr>
        	  			<td><?php echo $module['name']; ?></td>
        	  			<td><?php echo ucfirst($module['client']); ?></td>
        	  			<td><?php echo $module['result'] ? JText::_('YENDIF_VIDEO_SHARE_REMOVED') : JText::_('YENDIF_VIDEO_SHARE_NOT_REMOVED'); ?></td>
      				</tr>
      			<?php endforeach;?>
      		<?php endif;?>
            
      		<?php if( count( $status->plugins ) ) : ?>
      			<tr>
        	  		<th><?php echo JText::_('YENDIF_VIDEO_SHARE_PLUGIN'); ?></th>
          	  		<th><?php echo JText::_('YENDIF_VIDEO_SHARE_GROUP'); ?></th>
        	  		<th></th>
      			</tr>
      			<?php foreach( $status->plugins as $plugin ) : ?>
      				<tr>
        	  			<td><?php echo $plugin['name']; ?></td>
        	  			<td><?php echo ucfirst($plugin['group']); ?></td>
        	  			<td><?php echo $plugin['result'] ? JText::_('YENDIF_VIDEO_SHARE_REMOVED') : JText::_('YENDIF_VIDEO_SHARE_NOT_REMOVED'); ?></td>
      				</tr>
      			<?php endforeach; ?>
      		<?php endif; ?>
    	  </tbody>
  		</table>
	<?php }	
}