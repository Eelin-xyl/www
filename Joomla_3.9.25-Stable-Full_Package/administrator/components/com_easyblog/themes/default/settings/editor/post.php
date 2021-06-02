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
<div class="row form-horizontal">
	<div class="col-lg-6">
		<div class="panel">
			<?php echo $this->html('panel.heading', 'COM_EASYBLOG_SETTINGS_COMPOSER_POST_OPTIONS', 'COM_EASYBLOG_SETTINGS_COMPOSER_POST_OPTIONS_INFO'); ?>

			<div class="panel-body">
				<?php echo $this->html('settings.toggle', 'layout_composer_frontpage', 'COM_EASYBLOG_SETTINGS_COMPOSER_POST_OPTIONS_FRONTPAGE'); ?>

				<?php echo $this->html('settings.toggle', 'layout_composer_language', 'COM_EASYBLOG_SETTINGS_COMPOSER_POST_OPTIONS_LANGUAGE_SELECTION'); ?>

				<?php echo $this->html('settings.toggle', 'layout_composer_creationdate', 'COM_EASYBLOG_SETTINGS_COMPOSER_POST_OPTIONS_CREATION_DATE'); ?>

				<?php echo $this->html('settings.toggle', 'layout_composer_publishingdate', 'COM_EASYBLOG_SETTINGS_COMPOSER_POST_OPTIONS_PUBLISHING_DATE'); ?>

				<?php echo $this->html('settings.toggle', 'layout_composer_unpublishdate', 'COM_EASYBLOG_SETTINGS_COMPOSER_POST_OPTIONS_UNPUBLISHING_DATE'); ?>

				<?php echo $this->html('settings.toggle', 'layout_composer_autopostdate', 'COM_EB_SETTINGS_COMPOSER_POST_OPTIONS_AUTOPOSTING_DATE'); ?>
				
				<?php echo $this->html('settings.toggle', 'main_sendemailnotifications', 'COM_EB_DEFAULT_NOTIFY_SUBSCRIBERS'); ?>


				<?php echo $this->html('settings.toggle', 'layout_composer_privacy', 'COM_EASYBLOG_SETTINGS_COMPOSER_POST_OPTIONS_PRIVACY_SECTION', ''); ?>

				<div class="form-group">
					<?php echo $this->html('form.label', 'COM_EASYBLOG_SETTINGS_WORKFLOW_DEFAULT_BLOG_PRIVACY', 'main_blogprivacy'); ?>

					<div class="col-md-7">
						<?php
							$nameFormat = EB::privacy()->getOptions();
							$showdet = JHTML::_('select.genericlist', $nameFormat, 'main_blogprivacy', 'class="form-control"', 'value', 'text', $this->config->get('main_blogprivacy'));
							echo $showdet;
						?>
					</div>
				</div>

				<?php echo $this->html('settings.toggle', 'layout_composer_comment', 'COM_EASYBLOG_SETTINGS_COMPOSER_POST_OPTIONS_COMMENT', '', 'data-comment-option'); ?>

				<div class="form-group <?php echo $this->config->get('layout_composer_comment') ? '' : 'hide';?>" data-comment-option-default>
					<?php echo $this->html('form.label', 'COM_EASYBLOG_SETTINGS_WORKFLOW_DEFAULT_ALLOW_COMMENT', 'main_defaultallowcomment'); ?>

					<div class="col-md-7">
						<?php echo $this->html('form.toggler', 'main_defaultallowcomment', $this->config->get('main_defaultallowcomment')); ?>
					</div>
				</div>

				<?php echo $this->html('settings.toggle', 'layout_composer_feature', 'COM_EASYBLOG_SETTINGS_COMPOSER_POST_OPTIONS_FEATURE_SECTION'); ?>

				<?php echo $this->html('settings.toggle', 'layout_composer_category_language', 'COM_EASYBLOG_SETTINGS_COMPOSER_POST_CATEGORY_RESPECT_LANGUAGE'); ?>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
	</div>
</div>
