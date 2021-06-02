<?php

/*
 * @version		$Id: default.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

$app = JFactory::getApplication();	

$itemId = $app->input->getInt('Itemid')  ? '&Itemid=' . $app->input->getInt('Itemid') : '';
$new_link = JRoute::_( 'index.php?option=com_yendifvideoshare&view=user&task=add&'.YendifVideoShareUtils::getToken().'=1'.$itemId );
?>

<div class="yendif-video-share">
	<div class="page-header">
  		<h1> <?php echo JText::_('YENDIF_VIDEO_SHARE_MY_VIDEOS'); ?> </h1>
    </div>
    
  	<form action="<?php echo JRoute::_('index.php'); ?>" name="yendif_form" id="yendif_form" method="post">
    	<div class="btn-toolbar">
        	<div class="btn-group pull-left">
	    		<input type="text" name="search" id="filter_search" value="<?php echo htmlspecialchars($this->search_key); ?>" title="<?php echo JText::_('YENDIF_VIDEO_SHARE_SEARCH_MY_VIDEOS'); ?>" />
	  		</div>
      		<div class="btn-group pull-left">
	    		<button type="submit" class="btn" title="<?php echo JText::_('YENDIF_VIDEO_SHARE_GO'); ?>"><i class="icon-search"></i></button>
				<button type="button" class="btn" title="<?php echo JText::_('YENDIF_VIDEO_SHARE_RESET'); ?>" onclick="document.getElementById('filter_search').value=''; this.form.submit();"><i class="icon-remove"></i></button>
	  		</div>
            <div class="pull-right">
            	<a class="btn btn-primary" href="<?php echo $new_link; ?>"><?php echo JText::_('YENDIF_VIDEO_SHARE_ADD_NEW_VIDEO'); ?></a>
            </div>
            <div class="clearfix"></div>
        </div>
        
    	<table class="table table-striped">
      		<thead>
        		<tr class="yendifHeaderRow">
          			<th class="center" width="7%">#</th>
          			<th width="33%"><?php echo JText::_('YENDIF_VIDEO_SHARE_VIDEO_TITLE'); ?></th>
          			<th class="center" width="7%"><?php echo JText::_('YENDIF_VIDEO_SHARE_ID'); ?></th>
          			<th width="18%"><?php echo JText::_('YENDIF_VIDEO_SHARE_CATEGORY'); ?></th>          
          			<th class="center" width="15%"><?php echo JText::_('YENDIF_VIDEO_SHARE_PUBLISHED'); ?></th>
          			<th class="center" width="25%"><?php echo JText::_('YENDIF_VIDEO_SHARE_ACTIONS'); ?></th>
        		</tr>
      		</thead>
      		<tbody>
        	<?php
				foreach( $this->items as $key => $item ) {
					$edit_link    = JRoute::_( 'index.php?option=com_yendifvideoshare&view=user&task=edit&'.YendifVideoShareUtils::getToken().'=1&'.'id='.$item->id.$itemId );
					$delete_link  = JRoute::_( 'index.php?option=com_yendifvideoshare&view=user&task=delete&'.YendifVideoShareUtils::getToken().'=1&'.'id='.$item->id.$itemId );
					$preview_link = YendifVideoShareUtils::buildRoute( $item, 'video' );
					$status_img   = $item->published == 0 ? 'publish_x.png' : 'tick.png';
					?>
        			<tr>
          				<td class="center"><?php echo ( $this->limitstart + $key + 1 ); ?> </td>
          				<td><a href="<?php echo $edit_link; ?>"> <?php echo $item->title; ?> </a></td>
          				<td class="center"><?php echo $item->id; ?> </td>
          				<td><?php echo $item->category;?> </td>          
          				<td class="center"><img src="<?php echo JURI::root( true ) . "/media/yendifvideoshare/assets/site/images/".$status_img; ?>" /></td>
          				<td class="center">
                        	<div class="btn-group">
          						<a class="btn btn-small btn-default" href="<?php echo $edit_link; ?>"><?php echo JText::_('YENDIF_VIDEO_SHARE_EDIT'); ?></a>
            					<a class="btn btn-small btn-danger" href="<?php echo $delete_link; ?>"><?php echo JText::_('YENDIF_VIDEO_SHARE_DELETE'); ?></a>
            					<a class="btn btn-small btn-success" href="<?php echo $preview_link; ?>" target="_blank"><?php echo JText::_('YENDIF_VIDEO_SHARE_PREVIEW'); ?></a>
                            </div>
          				</td>
        			</tr>
        			<?php
      			}
			?>
      	</tbody>
	</table>
    <div class="pagination"><?php echo $this->pagination->getPagesLinks(); ?></div>
    <input type="hidden" name="option" value="com_yendifvideoshare" />
    <input type="hidden" name="view" value="user" />
    <input type="hidden" name="Itemid" value="<?php echo $app->input->getInt('Itemid'); ?>" />
    <input type="hidden" name="task" value="" />
    <?php echo JHTML::_( 'form.token' ); ?>
  </form>
</div>