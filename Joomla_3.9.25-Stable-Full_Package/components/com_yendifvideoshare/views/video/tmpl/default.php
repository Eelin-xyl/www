<?php

/*
 * @version		$Id: default.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies PVT Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.formvalidation');
JHtml::_('jquery.framework');

$app = JFactory::getApplication();
$document = JFactory::getDocument();

$document->addScriptDeclaration("
	if( typeof( yendif ) === 'undefined' ) {
    	var yendif = {};
	};

	yendif.base = '".JURI::root()."';
	yendif.userid = ".JFactory::getUser()->get('id').";
	yendif.allow_guest_like = ".$this->config->allow_guest_like.";
	yendif.allow_guest_rating = ".$this->config->allow_guest_rating.";
	yendif.alert_message = '".JText::_('YENDIF_VIDEO_SHARE_PLEASE_LOGIN')."';
");

$itemId = $app->input->getInt('Itemid') ? '&Itemid=' . $app->input->getInt('Itemid') : '';
?>

<div class="yendif-video-share video <?php echo $this->escape( $this->params->get('pageclass_sfx') ); ?>">
	<?php if( $this->config->show_title ) : ?>
		<div class="page-header">
        	<?php
				$title = $this->escape( $this->item->title );
			 ?>
			<h1> <?php echo str_replace( '&amp;', '&', $title ); ?> </h1>
    	</div>
    <?php endif; ?>
    
    <div class="row-fluid">        
		<?php
            $meta = array();
            
            if( $this->config->show_category ) {
                $meta[] = sprintf( '<i class="icon-folder"></i>&nbsp;<a href="%s">%s</a>', YendifVideoShareUtils::buildRoute( $this->category, 'category' ), $this->category->name );
            }
                                
            if( $this->config->show_user ) {
                $meta[] = '<span><i class="icon-user"></i>&nbsp;'.JFactory::getUser( $this->item->userid )->username.'</span>';
            }
            
            if( $this->config->show_date ) {
                $meta[] = '<span><i class="icon-calendar"></i>&nbsp;'.JHtml::_( 'date', $this->item->created_date, JText::_('DATE_FORMAT_LC3') );
            }
            
            if( $this->show_views ) {
                $meta[] = '<span><i class="icon-eye"></i>&nbsp;'.$this->item->views.' '.JText::_('YENDIF_VIDEO_SHARE_VIEWS').'</span>';
            }
            
            if( count( $meta ) ) echo '<div class="pull-left muted">'.implode( ' / ',  $meta ).'</div>';
        ?>
        
        <?php if( $this->config->show_search ) : ?>
        	<div class="pull-right">
            	<form action="<?php echo JRoute::_( 'index.php?option=com_yendifvideoshare&view=search'.$itemId ); ?>" class="form-validate" method="post">
                    <input type="hidden" name="option" value="com_yendifvideoshare" />
                    <input type="hidden" name="view" value="search" />
                    <input type="hidden" name="Itemid" value="<?php echo $app->input->getInt('Itemid'); ?>" />
                    <div class="input-append">
                    	<input type="text" name="search" class="required" />
                    	<button type="submit" class="btn btn-default"><?php echo JText::_('YENDIF_VIDEO_SHARE_GO'); ?></button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
        
        <div class="clearfix"></div>
    </div>
    
    <?php echo $this->player; ?>
    
    <?php if( $this->access && ( $this->show_rating || $this->show_likes_dislikes ) ) : ?>
    	<ul class="breadcrumb">
    		<?php if( $this->show_rating ) : ?>
   				<li id="yendif-ratings-widget">
                	<?php echo $this->rating_widget; ?> 
                </li>
        	<?php endif; ?>
            
            <?php if( $this->show_likes_dislikes ) : ?>
        		<li id="yendif-likes-dislikes-widget" class="pull-right">
                	<?php echo YendifVideoShareUtils::VotingWidget( $this->item->id, $this->config->allow_guest_like ); ?>
                </li>
        	<?php endif; ?>
    	</ul>
    <?php endif; ?>
    
    <?php
    	if( $this->config->show_description && ! empty( $this->item->description ) ) {
			echo JHTML::_( 'content.prepare', $this->item->description );
		}		
		
		if( $this->config->share_script ) {
    		echo $this->config->share_script;
    	}
		
    	if( $this->access && $this->config->comments != 'none' ) {
			echo $this->loadTemplate('comments');
		}
		
  		if( count( $this->videos ) && $this->config->show_related ) {
			echo $this->loadTemplate('related');
		}
  	?>    
</div>