<?php

/*
 * @version		$Id: default.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<div class="yendif-video-share dashboard">
	<div class="row-fluid">
    	<div class="span8">
        	<div class="cpanel hidden-phone">
            	<div class="icon">
					<a href="index.php?option=com_yendifvideoshare&view=config" title="<?php echo JText::_('YENDIF_VIDEO_SHARE_PLAYER_SETTINGS'); ?>">
						<img src="../media/yendifvideoshare/assets/admin/images/config.png" alt="<?php echo JText::_('YENDIF_VIDEO_SHARE_PLAYER_SETTINGS'); ?>" />
    					<span><?php echo JText::_('YENDIF_VIDEO_SHARE_PLAYER_SETTINGS'); ?></span>
    				</a>
  				</div>
                
  				<div class="icon">
					<a href="index.php?option=com_yendifvideoshare&view=categories&task=add&<?php echo YendifVideoShareUtils::getToken(); ?>=1" title="<?php echo JText::_('YENDIF_VIDEO_SHARE_ADD_CATEGORY'); ?>">
    					<img src="../media/yendifvideoshare/assets/admin/images/add-category.png" alt="<?php echo JText::_('YENDIF_VIDEO_SHARE_ADD_CATEGORY'); ?>" />
        				<span><?php echo JText::_('YENDIF_VIDEO_SHARE_ADD_CATEGORY'); ?></span>
    				</a>
  				</div>
  
  				<div class="icon">
    				<a href="index.php?option=com_yendifvideoshare&view=videos&task=add&<?php echo YendifVideoShareUtils::getToken(); ?>=1" title="<?php echo JText::_('YENDIF_VIDEO_SHARE_ADD_VIDEO'); ?>">
    					<img src="../media/yendifvideoshare/assets/admin/images/add-video.png" alt="<?php echo JText::_('YENDIF_VIDEO_SHARE_ADD_VIDEO'); ?>" />
        				<span><?php echo JText::_('YENDIF_VIDEO_SHARE_ADD_VIDEO'); ?></span>
    				</a>
  				</div>
                
  				<div class="icon">
					<a href="http://yendifplayer.com/forum/joomla-video-share.html" target="_blank" title="<?php echo JText::_('YENDIF_VIDEO_SHARE_GET_SUPPORT'); ?>">
						<img src="../media/yendifvideoshare/assets/admin/images/support.png" alt="<?php echo JText::_('YENDIF_VIDEO_SHARE_GET_SUPPORT'); ?>" />
    					<span><?php echo JText::_('YENDIF_VIDEO_SHARE_GET_SUPPORT'); ?></span>
    				</a>
  				</div>
			</div>
            
            <div class="clearfix"></div>
            
            <div class="yendif-spacer"></div> 
            
            <ul class="nav nav-tabs">
        		<li class="active"><a href="#server_information" data-toggle="tab"><?php echo JText::_('YENDIF_VIDEO_SHARE_SERVER_INFORMATION');?></a></li>
        		<li><a href="#recently_added_videos" data-toggle="tab"><?php echo JText::_('YENDIF_VIDEO_SHARE_RECENTLY_ADDED_VIDEOS');?></a></li>
        		<li><a href="#most_viewed_videos" data-toggle="tab"><?php echo JText::_('YENDIF_VIDEO_SHARE_MOST_VIEWED_VIDEOS');?></a></li>
      		</ul>
      
      		<div class="tab-content">

				<div class="tab-pane active" id="server_information">
					<table class="table table-striped">
  						<thead>
    						<tr>
      							<th class="center"width="8%">#</th>
      							<th><?php echo JText::_('YENDIF_VIDEO_SHARE_CONFIGURATION_OPTION'); ?></th>
      							<th class="center" width="15%"><?php echo JText::_('YENDIF_VIDEO_SHARE_VALUE'); ?></th>
    						</tr>
  						</thead>
  						<?php
							$n = count( $this->server_details );
							foreach( $this->server_details as $key => $item ) {
								$class  = $item['value'] == JText::_('YENDIF_VIDEO_SHARE_NO') ? 'text-error' : 'text-success';
								$status = sprintf( '<p class="%s">%s</p>', $class, $item['value'] );
  							?>
  							<tr>
    							<td class="center"><?php echo $key + 1; ?></td>
    							<td><?php echo $item['name']; ?></td>
    							<td class="center"><?php echo $status; ?></td>
  							</tr>
  						<?php } ?>
					</table>
					<?php if( ! $n ) echo '<p class="text-center muted">' . JText::_('YENDIF_VIDEO_SHARE_ITEM_NOT_FOUND') . '</p>';	?>
            	</div>
                
                <div class="tab-pane" id="recently_added_videos">
					<table class="table table-striped">
  						<thead>
    						<tr>
      							<th class="center" width="8%">#</th>
      							<th><?php echo JText::_('YENDIF_VIDEO_SHARE_VIDEO_TITLE'); ?></th>
      							<th width="20%"><?php echo JText::_('YENDIF_VIDEO_SHARE_CATEGORY'); ?></th>
      							<th width="12%"><?php echo JText::_('YENDIF_VIDEO_SHARE_USER'); ?></th>
      							<th width="12%"><?php echo JText::_('YENDIF_VIDEO_SHARE_PUBLISHED'); ?></th>
    						</tr>
  						</thead>
  						<?php
							$n = count( $this->latest_videos );
							foreach( $this->latest_videos as $key => $item ) {
								$class  = $item->published == 0 ? 'text-error' : 'text-success';
								$text   = $item->published == 0 ? JText::_('YENDIF_VIDEO_SHARE_NO') : JText::_('YENDIF_VIDEO_SHARE_YES');
								$status = sprintf( '<p class="%s">%s</p>', $class, $text );
  							?>
  							<tr>
    							<td class="center"><?php echo $key + 1; ?></td>
    							<td><?php echo $item->title; ?></td>
    							<td><?php echo $item->category; ?></td>
    							<td><?php echo JFactory::getUser( $item->userid )->username; ?></td>
    							<td class="center"><?php echo $status; ?></td>
  							</tr>
 						<?php } ?>
					</table>
					<?php if(! $n ) echo '<p class="text-muted center">' . JText::_('YENDIF_VIDEO_SHARE_ITEM_NOT_FOUND') . '</p>'; ?>
            	</div>
                
            	<div class="tab-pane" id="most_viewed_videos">
					<table class="table table-striped">
 	 					<thead>
    						<tr>
      							<th class="center" width="8%">#</th>
      							<th><?php echo JText::_('YENDIF_VIDEO_SHARE_VIDEO_TITLE'); ?></th>
      							<th width="20%"><?php echo JText::_('YENDIF_VIDEO_SHARE_CATEGORY'); ?></th>
      							<th width="12%"><?php echo JText::_('YENDIF_VIDEO_SHARE_VIEWS'); ?></th>
      							<th width="12%"><?php echo JText::_('YENDIF_VIDEO_SHARE_PUBLISHED'); ?></th>
    						</tr>
  						</thead>
  						<?php
							$n = count( $this->popular_videos );
							foreach( $this->popular_videos as $key => $item ) {
	  							$class  = $item->published == 0 ? 'text-error' : 'text-success';
								$text   = $item->published == 0 ? JText::_('YENDIF_VIDEO_SHARE_NO') : JText::_('YENDIF_VIDEO_SHARE_YES');
								$status = sprintf( '<p class="%s">%s</p>', $class, $text );
  							?>
  							<tr>
    							<td class="center"><?php echo $key + 1; ?></td>
    							<td><?php echo $item->title; ?></td>
    							<td><?php echo $item->category; ?></td>
    							<td><?php echo $item->views; ?></td>
    							<td class="center"><?php echo $status; ?></td>
  							</tr>
  						<?php } ?>
					</table>
					<?php if( ! $n ) echo '<p class="text-muted center">' . JText::_('YENDIF_VIDEO_SHARE_ITEM_NOT_FOUND') . '</p>'; ?>
            	</div>
        	</div>  
		</div>      
        
        <div class="span4 well">
        	<h2 class="center"><?php echo JText::_('YENDIF_VIDEO_SHARE_YOU_HAVE_INSTALLED'); ?></h2>
      		<p class="text-center"><?php echo JText::_('YENDIF_VIDEO_SHARE_VERSION_DATA'); ?></p>
  			<table class="table table-bordered">
    			<tbody>
      				<tr>
      					<td><?php echo JText::_('YENDIF_VIDEO_SHARE_WEBSITE'); ?></td>
      					<td><a href="http://yendifplayer.com" target="_blank">https://yendifplayer.com</a></td>
    				</tr>
      				<tr>
        				<td><?php echo JText::_('YENDIF_VIDEO_SHARE_SUPPORT_MAIL'); ?></td>
        				<td><a href="mailto:admin@yendifplayer.com">admin@yendifplayer.com</a></td>
      				</tr>
      				<tr>
        				<td><?php echo JText::_('YENDIF_VIDEO_SHARE_FORUM_LINK'); ?></td>
        				<td><a href="http://yendifplayer.com/forum/" target="_blank">https://yendifplayer.com/forum/</a></td>
      				</tr>
    			</tbody>
  			</table>
            <p class="text-center muted"><?php echo JText::_('YENDIF_VIDEO_SHARE_CREDITS_DRYICONS'); ?></p>
        </div>
    </div>
    
    <div class="form-actions text-center muted"><?php echo JText::_('YENDIF_VIDEO_SHARE_COPYRIGHTS'); ?></div>
</div>