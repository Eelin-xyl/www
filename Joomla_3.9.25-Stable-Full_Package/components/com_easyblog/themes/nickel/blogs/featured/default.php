<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 - 2019 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<!-- @module: easyblog-before-pagination -->
<?php echo EB::renderModule('easyblog-before-entries');?>

<!-- Post listings begins here -->
<div itemscope itemtype="http://schema.org/Blog" class="eb-posts <?php echo $this->isMobile() ? 'is-mobile' : '';?> <?php echo $posts ? 'eb-masonry' : '';?>" data-blog-posts>
	<?php if ($posts) { ?>
		<?php foreach ($posts as $post) { ?>
			<?php if (!EB::isSiteAdmin() && $this->config->get('main_password_protect') && !empty($post->blogpassword) && !$post->verifyPassword()) { ?>
				<?php echo $this->output('site/blogs/latest/default.protected', array('post' => $post)); ?>
			<?php } else { ?>
				<?php echo $this->output('site/blogs/latest/default.main', array('post' => $post, 'return' => $return)); ?>
			<?php } ?>
		<?php } ?>
	<?php } else { ?>
	<div class="empty">
		<?php echo JText::_('COM_EASYBLOG_NO_FEATURED_POSTS_YET');?>
	</div>
	<?php } ?>
</div>

<!-- @module: easyblog-after-entries -->
<?php echo EB::renderModule('easyblog-after-entries'); ?>

<?php if ($pagination) { ?>
	<!-- @module: easyblog-before-pagination -->
	<?php echo EB::renderModule('easyblog-before-pagination'); ?>

	<!-- Pagination items -->
	<?php echo $pagination->getPagesLinks();?>

	<!-- @module: easyblog-after-pagination -->
	<?php echo EB::renderModule('easyblog-after-pagination'); ?>
<?php } ?>
