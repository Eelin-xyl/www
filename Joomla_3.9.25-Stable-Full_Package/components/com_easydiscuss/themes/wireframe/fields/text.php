<?php
/**
* @package		EasyDiscuss
* @copyright	Copyright (C) Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyDiscuss is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<input type="text" 
       name="fields[<?php echo $field->id;?>]" 
       class="o-form-control" 
       id="field-<?php echo $field->id;?>" 
       value="<?php echo $this->html('string.escape', $value);?>" 
	   <?php echo $field->required ? 'data-ed-custom-fields-required data-ed-textbox-fields' : '' ?>
    />