<?php

/*
 * @version		$Id: default.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');
		
$span   = 'span' . floor(12 / $this->cols);
$column = 0;
?>

<div class="yendif-video-share categories <?php echo $this->escape( $this->params->get('pageclass_sfx') ); ?>">
	<?php if( $this->params->get('show_page_heading', 1) ) : ?>
 		<div class="page-header">
			<h1>
				<?php echo $this->escape( $this->params->get('page_heading', $this->menu_title) ); ?>
                <?php echo $this->rss_feed; ?>
            </h1>
    	</div>
  	<?php endif; ?> 
  
  	<div class="row-fluid">
  		<ul class="thumbnails">
    	<?php 
  	  		foreach( $this->items as $item ) {			
  				if( $column >= $this->cols ) {
					echo '</ul><ul class="thumbnails">';
					$column = 0;
				}
		
				$target_url = YendifVideoShareUtils::buildRoute( $item, 'category' );
				$image = YendifVideoShareUtils::getImage( $item->image, '_thumb', $this->config->default_image );	
		
				$count = '';
				if( $this->show_videos_count ) {
					$count = YendifVideoShareUtils::getMediaCount( $item->id );
					$count = '&nbsp;(' .$count. ')';
				}
    			?>
                <li class="<?php echo $span; ?>">
                	<div class="thumbnail">
    					<a href="<?php echo $target_url; ?>" class="yendif-thumbnail">
                        	<div class="yendif-image" style="background-image: url(<?php echo $image; ?>);">&nbsp;</div>
                        </a>
                        <div class="caption">
        					<h4><a href="<?php echo $target_url; ?>"><?php echo $item->name.$count; ?></a></h4>
                            <?php if( $this->show_excerpt && ! empty( $item->description ) ) : ?>
                            	<p><?php echo YendifVideoShareUtils::Truncate( $item->description, $this->excerpt_length ); ?></p>
                            <?php endif; ?>
                        </div>
  	  				</div> 
    			</li>
    			<?php
					if( $column >= $this->cols ) echo '</ul>';
		  			$column++;
      		}
		?>    
    	</ul>
        
        <div class="pagination pagination-centered"><?php echo $this->pagination->getPagesLinks(); ?></div>
  	</div>
  	
</div>