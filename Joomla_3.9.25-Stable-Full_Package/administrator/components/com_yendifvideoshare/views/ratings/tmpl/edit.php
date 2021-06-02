<?php

/*
 * @version		$Id: add.php 1.2.8 07-03-2019 $
 * @package		Yendif Video Share
 * @copyright   Copyright (C) 2014-2019 Yendif Technologies (P) Ltd
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.formvalidation');
JHtml::_('jquery.framework');

$document = JFactory::getDocument();
$document->addScript( YendifVideoShareUtils::prepareURL('media/yendifvideoshare/assets/admin/js/yendifvideoshare.js') );
$document->addScriptDeclaration("
	Joomla.submitbutton = function( pressbutton ) {
    	if( pressbutton == 'cancel' ) {
        	submitform( pressbutton );
    	} else {
        	var f = document.adminForm;
			
			document.formvalidator.setHandler('list', function (value) {
        		return (value != -1);
			});

        	if( document.formvalidator.isValid(f) ) {
            	submitform( pressbutton );    
        	} else {
            	alert('" . JText::_("YENDIF_VIDEO_SHARE_ERROR_INVALID_INPUT") . "');
        	}    
    	}    
	}
");

?>

<div class="yendif-video-share ratings add">
	<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-horizontal form-validate">
  		<div class="control-group">
            <label class="control-label"><?php echo JText::_('YENDIF_VIDEO_SHARE_USER_RATING'); ?></label>
            <div class="controls">
              	<?php echo $this->ratings; ?>
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label"><?php echo JText::_('YENDIF_VIDEO_SHARE_SELECT_USER'); ?></label>
            <div class="controls">
              	<?php echo $this->userids; ?>
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label"><?php echo JText::_('YENDIF_VIDEO_SHARE_SELECT_VIDEO'); ?></label>
            <div class="controls">
              	<?php echo $this->videoids; ?>
            </div>
        </div>
    <input type="hidden" name="boxchecked" value="1">
    <input type="hidden" name="option" value="com_yendifvideoshare" />
    <input type="hidden" name="view" value="ratings" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="id" value="<?php echo $this->item->id; ?>">
    <?php echo JHTML::_( 'form.token' ); ?>
  </form>
  
  <div class="form-actions text-center muted"><?php echo JText::_('YENDIF_VIDEO_SHARE_COPYRIGHTS'); ?></div>
</div>