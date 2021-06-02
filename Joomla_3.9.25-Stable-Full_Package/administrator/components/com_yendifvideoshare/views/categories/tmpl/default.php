<?php

/*
 * @version		$Id: default.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access'); ?>

<div class="yendif-video-share categories">
	<form action="index.php" method="post" name="adminForm" id="adminForm">
  		<div id="filter-bar" class="btn-toolbar">
      		<div class="filter-search btn-group pull-left">
	    		<input type="text" name="search" id="search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->lists['search'] ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('YENDIF_VIDEO_SHARE_FILTER_BY_NAME'); ?>" />
	  		</div>
      		<div class="btn-group pull-left">
	    		<button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button type="button" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.getElementById('search').value=''; document.getElementById('filter_category').value=-1; document.getElementById('filter_state').value=-1; this.form.submit();"><i class="icon-remove"></i></button>
	  		</div>
      		<div class="btn-group pull-right hidden-phone">
				<?php echo $this->pagination->getLimitBox(); ?>
		  	</div>
            <div class="btn-group pull-right hidden-phone">
				<?php echo $this->lists['categories']; ?>
	  		</div> 
      		<div class="btn-group pull-right hidden-phone">
				<?php echo $this->lists['state']; ?>
	  		</div>
            <div class="clearfix"></div>     
    	</div>
        
    	<table class="table table-striped">
      		<thead>
        		<tr>
        			<th class="center hidden-phone" width="3%">#</th>
          			<th class="center hidden-phone" width="3%"><input type="checkbox" name="toggle" value="" onClick="Joomla.checkAll(this);" /></th>
          			<th><?php echo JText::_('YENDIF_VIDEO_SHARE_CATEGORY_NAME'); ?></th>          
          			<th class="order center hidden-tablet hidden-phone" width="14%">
		  				<?php echo JText::_('YENDIF_VIDEO_SHARE_POSITION'); ?>&nbsp;&nbsp;<?php echo JHTML::_('grid.order',  $this->items ); ?>
          			</th>
          			<th class="center" width="10%"><?php echo JText::_('YENDIF_VIDEO_SHARE_PUBLISHED'); ?></th>
                    <th class="center hidden-phone" width="8%"><?php echo JText::_('YENDIF_VIDEO_SHARE_ID'); ?></th>
        		</tr>
      		</thead>
      		<tbody>
        	<?php
				$n = count( $this->items );
				foreach( $this->items as $key => $item ) {
					$link = JRoute::_( 'index.php?option=com_yendifvideoshare&view=categories&task=edit&'. YendifVideoShareUtils::getToken() .'=1&'.'cid[]='. $item->id );
					$checked = JHTML::_('grid.id', $key, $item->id );
					$published = JHTML::_('grid.published', $item, $key );
					?>
        			<tr>
          				<td class="center hidden-phone"><?php echo ($this->limitstart + $key + 1); ?> </td>
          				<td class="center hidden-phone"><?php echo $checked; ?> </td>
          				<td><?php echo $item->spcr; ?><a href="<?php echo $link; ?>"> <?php echo $item->name;?> </a></td>         
          				<td class="order hidden-tablet hidden-phone">
            				<input type="text" name="order[]" class="input-mini center pull-left" value="<?php echo $item->ordering; ?>" />
                        	 <div class="btn-group pull-right">
                         		<?php echo $this->pagination->orderUpIcon( $key, $item->up, 'orderup', 'YENDIF_VIDEO_SHARE_MOVE_UP' ); ?>
                            	<?php echo $this->pagination->orderDownIcon( $key, $n, $item->down, 'orderdown', 'YENDIF_VIDEO_SHARE_MOVE_DOWN' ); ?>
                         	</div>
          				</td>
          				<td class="center"><?php echo $published; ?> </td>
                        <td class="center hidden-phone"><?php echo $item->id;?> </td>
        			</tr>
        			<?php
            	}
			?>
      		</tbody>
      		<tfoot>
        		<tr>
          			<td colspan="6"><?php echo $this->pagination->getListFooter(); ?></td>
        		</tr>
      		</tfoot>
    	</table>
    	<input type="hidden" name="boxchecked" value="0">
    	<input type="hidden" name="option" value="com_yendifvideoshare" />
    	<input type="hidden" name="view" value="categories" />
    	<input type="hidden" name="task" value="" />
    	<?php echo JHTML::_( 'form.token' ); ?>
  	</form>
    
    <div class="form-actions text-center muted"><?php echo JText::_('YENDIF_VIDEO_SHARE_COPYRIGHTS'); ?></div>
</div>