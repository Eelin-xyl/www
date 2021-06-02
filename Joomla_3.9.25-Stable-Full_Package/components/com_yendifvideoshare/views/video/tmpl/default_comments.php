<?php

/*
 * @version		$Id: default_comments.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies PVT Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

$jomments = JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_jcomments'.DIRECTORY_SEPARATOR.'jcomments.php';    
$komento  = JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_komento'.DIRECTORY_SEPARATOR.'bootstrap.php'; 

if( $this->config->comments == 'facebook' ) { ?>

  	<h2><?php echo JText::_('YENDIF_VIDEO_SHARE_ADD_YOUR_COMMENTS'); ?></h2>
    <div id="fb-root"></div>
  	<div class="fb-comments" data-href="<?php echo JURI::root().'index.php?option=com_yendifvideoshare&view=video&id='.$this->item->id; ?>" data-num-posts="<?php echo $this->config->fb_post_count; ?>" data-width="100%" data-colorscheme="<?php echo $this->config->fb_color_scheme; ?>"></div>
    
<?php } else if( $this->config->comments == 'jcomments' && file_exists( $jomments ) && JComponentHelper::getComponent('com_jcomments', true)->enabled ) {	

	require_once( $jomments );		
    echo JComments::showComments( $this->item->id, 'com_yendifvideoshare', $this->item->title );
	
} else if( $this->config->comments == 'komento' && file_exists( $komento ) && JComponentHelper::getComponent('com_komento', true)->enabled ) {	

	$item = new stdClass;
	$item->id = $this->item->id;
	$item->catid = $this->item->catid;
	$item->text = $this->item->description;
	$item->introtext = $this->item->description;
		
	require_once( $komento );
	echo Komento::commentify( 'com_yendifvideoshare', $item, array() );
	
}