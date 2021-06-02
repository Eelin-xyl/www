<?php

/*
 * @version		$Id: default.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access'); ?>

<div class="yendif-video-share ratings">
	<form action="index.php" method="post" name="adminForm" id="adminForm">
  		<div id="filter-bar" class="btn-toolbar">
    		<div class="filter-search btn-group pull-left">
	    		<input type="text" name="search" id="search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->lists['search'] ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('YENDIF_VIDEO_SHARE_FILTER_BY_TITLE'); ?>" />
	  		</div>
			<div class="btn-group pull-left">
	    		<button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button type="button" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.getElementById('search').value=''; document.getElementById('filter_user').value=-1; document.getElementById('filter_rating').value=-1; this.form.submit();"><i class="icon-remove"></i></button>
	  		</div>
        	<div class="btn-group pull-right hidden-phone">
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
        	<div class="btn-group pull-right hidden-phone">
				<?php echo $this->lists['ratings']; ?>
	  		</div> 
        	<div class="btn-group pull-right hidden-phone">
				<?php echo $this->lists['users']; ?>
	  		</div> 
        	<div class="clearfix"></div>  
    	</div>

    	<table class="table table-striped">
      		<thead>
        		<tr>
          			<th class="center hidden-phone" width="3%">#</th>
          			<th class="center hidden-phone" width="3%"><input type="checkbox" name="toggle" value="" onClick="Joomla.checkAll(this);" /></th>               
          			<th class="hidden-phone"><?php echo JText::_('YENDIF_VIDEO_SHARE_VIDEO_TITLE'); ?></th>
          			<th width="15%"><?php echo JText::_('YENDIF_VIDEO_SHARE_USER'); ?></th>
          			<th class="center" width="8%"><?php echo JText::_('YENDIF_VIDEO_SHARE_RATINGS'); ?></th>
          			<th class="center hidden-phone" width="15%"><?php echo JText::_('YENDIF_VIDEO_SHARE_DATE_ADDED'); ?></th>
        		</tr>
      		</thead>
      		<tbody>
        	<?php
				foreach( $this->items as $key => $item ) {
					$link = JRoute::_( 'index.php?option=com_yendifvideoshare&view=ratings&task=edit&'. YendifVideoShareUtils::getToken() .'=1&'.'cid[]='. $item->id );
					$checked = JHTML::_('grid.id', $key, $item->id );
					$user = ! empty( $item->userid ) ? JFactory::getUser( $item->userid )->username : 'Guest';
					?>
        			<tr>
          				<td class="center hidden-phone"><?php echo ($this->limitstart + $key + 1); ?> </td>
          				<td class="center hidden-phone"><?php echo $checked; ?> </td>
          				<td><?php echo $item->video; ?></td>         
          				<td class="hidden-phone"><?php echo $user ?></td>
          				<td class="center"><?php echo $item->rating; ?> </td>
          				<td class="center hidden-phone"><?php echo $item->created_date; ?> </td>
        			</tr>
        			<?php
         		}
			?>
      		</tbody>
      		<tfoot>
        		<tr>
          			<td colspan="7"><?php echo $this->pagination->getListFooter(); ?></td>
        		</tr>
      		</tfoot>
    	</table>
    	<input type="hidden" name="boxchecked" value="0">
    	<input type="hidden" name="option" value="com_yendifvideoshare" />
    	<input type="hidden" name="view" value="ratings" />
    	<input type="hidden" name="task" value="" />
    	<?php echo JHTML::_( 'form.token' ); ?>
  	</form>
  	
    <div class="form-actions text-center muted"><?php echo JText::_('YENDIF_VIDEO_SHARE_COPYRIGHTS'); ?></div>
</div>