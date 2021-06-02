<?php

/*
 * @version		$Id: default.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

$popup_class_suffix = $this->enable_popup ? ' yendif-popup-gallery' : '';

$span   = 'span' . floor(12 / $this->cols);
$column = 0;

?>

<div class="yendif-video-share category <?php echo $this->escape( $this->params->get('pageclass_sfx') ); ?>"> 
	<div class="page-header">
		<h1>
			<?php echo $this->item->name; ?>
            
            <?php if( $this->show_videos_count ) : ?>
            	( <?php echo YendifVideoShareUtils::getSubCategoryMediaCount( $this->item->id, $this->config->schedule_video_publishing ); ?> )
            <?php endif; ?>
            
            <?php echo $this->rss_feed; ?>
        </h1>        
        <?php if( ! empty( $this->item->description ) ) : ?>
        	<p><?php echo $this->item->description; ?></p>
        <?php endif; ?>
    </div>
         
  	<div class="row-fluid<?php echo $popup_class_suffix; ?>" data-ratio="<?php echo $this->ratio; ?>">
    	<ul class="thumbnails">
    	<?php 
  	  		foreach( $this->videos as $item ) {
    			if( $column >= $this->cols ) {
					echo '</ul><ul class="thumbnails">';
					$column = 0;
				}
		
				$image = YendifVideoShareUtils::getImage( $item->image, '_thumb', $this->config->default_image );	
		
				if( $this->enable_popup ) {
					$iframe_src = JURI::root().'index.php?option=com_yendifvideoshare&view=video&id='.$item->id."&tmpl=component";
					$target_url = 'javascript:void(0)';
				} else {
					$iframe_src = '';
					$target_url = YendifVideoShareUtils::buildRoute( $item, 'video' );
				}		
    			?>    
                <li class="<?php echo $span; ?>" data-mfp-src="<?php echo $iframe_src; ?>" data-title="<?php echo $item->title ?>">
                	<div class="thumbnail">
      					<a href="<?php echo $target_url; ?>" class="yendif-thumbnail">
                        	<div class="yendif-image" style="background-image: url(<?php echo $image; ?>);">&nbsp;</div>
                            <img class="yendif-play-icon" src="<?php echo JURI::root( true ); ?>/media/yendifvideoshare/assets/site/images/play.png" alt="<?php echo $item->title; ?>" />
                            <?php if( !empty( $item->duration ) ) : ?>
          						<span class="yendif-duration"><?php echo $item->duration; ?></span>
          					<?php endif; ?> 
                        </a>
                        <div class="caption">
                        	<h4><a href="<?php echo $target_url; ?>"><?php echo $item->title; ?></a></h4>
                            
                            <?php if( $this->show_excerpt && ! empty( $item->description ) ) : ?>
                            	<p><?php echo YendifVideoShareUtils::Truncate( $item->description, $this->excerpt_length ); ?></p>
                            <?php endif; ?>
                            
         					<?php if( $this->show_rating ) : ?>
                            	<p><?php echo YendifVideoShareUtils::showRating( $item->rating ); ?></p>
                            <?php endif; ?> 
                            
        					<?php if( $this->show_views ) : ?>
    	  						<p class="muted"><?php echo $item->views . ' ' . JText::_('YENDIF_VIDEO_SHARE_VIEWS'); ?></p>
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
    </div>
    
    <div class="pagination pagination-centered"><?php echo $this->pagination->getPagesLinks(); ?></div>
  	
  	<?php if ( ! empty( $this->categories ) ) : ?>
  		<h3><?php echo JText::_('YENDIF_VIDEO_SHARE_SUB_CATEGORIES'); ?></h3>  
           
  		<div class="row-fluid">
        	<ul class="thumbnails">
    		<?php 
	  			$column = 0;
  	  			foreach( $this->categories as $item ) {
    				if( $column >= $this->cols ) {
						echo '</ul><ul class="thumbnails">';
						$column = 0;
					}
		
					$image = YendifVideoShareUtils::getImage( $item->image, '_thumb', $this->config->default_image );	
					$target_url = YendifVideoShareUtils::buildRoute( $item, 'category' );	

					$count = 0;
					if( $this->show_videos_count ) {     			  				
  						$count = YendifVideoShareUtils::getMediaCount( $item->id );				
						$count = '&nbsp;('.$count.')';    
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
         </div>
  	<?php endif; ?>
</div>