<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<div class="container-nav hidden">
	<a class="nav-sidebar-toggle" data-bp-toggle="collapse" data-target=".app-sidebar-collapse">
		<i class="fa fa-bars"></i>
		<span><?php echo JText::_('COM_EASYBLOG_MOBILE_MENU');?></span>
	</a>
	<a class="nav-subhead-toggle" data-target=".subhead-collapse">
		<i class="fa fa-cog"></i>
		<span><?php echo JText::_('COM_EASYBLOG_MOBILE_OPTIONS');?></span>
	</a>
</div>

<div class="hidden" data-j4-sidebar>
	<li class="item item-level-1">
		<a class="has-arrow" href="javascript:void(0);" aria-expanded="false" data-back-easyblog>
			<span style="padding: .2rem 0;margin: 0 .6rem;">
				<img src="/media/com_easyblog/images/easyblog-48x48.png" style="width: 18px;height: 18px;">
			</span>

			<span class="sidebar-item-title">EasyBlog</span>
		</a>
	</li>
</div>

<div class="app-sidebar app-sidebar-collapse" data-sidebar>
	<ul class="app-sidebar-nav list-unstyled">
		<li class="sidebar-item sidebar-item--joomla-4-btn">
			<a href="javascript:void(0);" data-back-joomla>
				<i class="fa fa-chevron-left"></i> <span class="app-sidebar-item-title"><?php echo JText::_('Back');?></span>
			</a>
		</li>

		<?php foreach ($menus as $menu) { ?>
			<li class="sidebar-item <?php echo isset($menu->childs) && $menu->childs ? 'dropdown' : '';?> <?php echo $menu->view == $view ? 'open active' : '';?>" data-sidebar-item>

				<?php if (isset($menu->childs) && $menu->childs) { ?>
				<a href="javascript:void(0);" class="dropdown-toggle_" data-sidebar-parent>
				<?php } else { ?>
				<a href="<?php echo $menu->link;?>">
				<?php } ?>

					<?php if (isset($menu->icon) && $menu->icon) { ?><i class="fa <?php echo $menu->icon;?>"></i><?php } ?><span class="app-sidebar-item-title"><?php echo JText::_($menu->title);?></span>

					<?php if (isset($menu->counter) && $menu->counter) { ?>
					<span class="badge"><?php echo $menu->counter;?></span>
					<?php } ?>
				</a>

				<?php if (isset($menu->childs) && $menu->childs) { ?>
				<ul class="dropdown-menu" role="menu" data-sidebar-child>
					<?php foreach ($menu->childs as $child) { ?>
					<li class="childItem<?php echo $layout == $child->url->layout ? ' active' : '';?>">
						<a href="<?php echo $child->link;?>">
							<?php if (isset($child->icon) && $child->icon) { ?>
							<i class="fa <?php echo $child->icon;?>"></i>
							<?php } ?>
							<span class="app-sidebar-item-title"><?php echo JText::_($child->title);?></span>

							<?php if (isset($child->counter) && $child->counter) { ?>
							<span class="badge"><?php echo $child->counter;?></span>
							<?php } ?>
						</a>
					</li>
					<?php } ?>
				</ul>
				<?php } ?>
			</li>
		<?php } ?>
	</ul>
</div>
