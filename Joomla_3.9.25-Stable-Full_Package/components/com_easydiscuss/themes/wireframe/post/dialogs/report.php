<?php
/**
* @package      EasyDiscuss
* @copyright    Copyright (C) Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasyDiscuss is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<dialog>
    <width>450</width>
    <height>250</height>
    <selectors type="json">
    {
        "{closeButton}" : "[data-close-button]",
        "{form}": "[data-ed-form-response]",
        "{submitButton}" : "[data-submit-button]"
    }
    </selectors>
    <bindings type="javascript">
    {
        "{closeButton} click": function() {
            this.parent.close();
        },

        "{submitButton} click": function() {
            this.form().submit();
        }
    }
    </bindings>
    <title><?php echo JText::_('COM_EASYDISCUSS_REPORT_ABUSE'); ?></title>
    <content>
        <p class="t-lg-mt--md t-lg-mb--xl">
            <?php echo JText::_('COM_EASYDISCUSS_REPORTING_SUBMIT_REPORT_DESC'); ?>
        </p>

        <form data-ed-form-response method="post" action="<?php echo JRoute::_('index.php');?>">

            <textarea name="reporttext" class="o-form-control" rows="6" style="height:120px;"></textarea>

            <input type="hidden" name="id" value="<?php echo $id;?>" />
            <?php echo $this->html('form.action', 'reports', 'reports', 'save');?> 
        </form>        
    </content>
    <buttons>
        <button data-close-button type="button" class="ed-dialog-footer-content__btn"><?php echo JText::_('COM_EASYDISCUSS_BUTTON_CANCEL'); ?></button>
        <button data-submit-button type="button" class="ed-dialog-footer-content__btn t-text--primary"><?php echo JText::_('COM_EASYDISCUSS_BUTTON_SUBMIT'); ?></button>
    </buttons>
</dialog>
