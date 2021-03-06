<?php
/**
* @package      EasyBlog
* @copyright    Copyright (C) 2010 - 2019 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<div id="eb" class="eb-mod mod_toolbar<?php echo $modules->getWrapperClass();?>">
	<?php echo EB::toolbar()->html(false, $modToolbar); ?>
</div>

<?php if (!$ebPageExist || !$config->get('layout_toolbar')) { ?>
	<div><?php echo EB::scripts()->getScripts(); ?></div>
<?php } ?>