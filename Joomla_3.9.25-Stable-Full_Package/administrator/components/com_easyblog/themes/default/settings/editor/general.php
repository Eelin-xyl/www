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
<div class="row form-horizontal">
	<div class="col-lg-6">
		<div class="panel">
			<?php echo $this->html('panel.heading', 'COM_EASYBLOG_SETTINGS_COMPOSER', 'COM_EASYBLOG_SETTINGS_COMPOSER_INFO'); ?>

			<div class="panel-body">

				<div class="form-group" data-composer-editors>
					<?php echo $this->html('form.label', 'COM_EASYBLOG_SETTINGS_LAYOUT_SELECT_DEFAULT_EDITOR', 'layout_editor'); ?>

					<div class="col-md-7">
						<?php echo $this->html('form.editors', 'layout_editor', $this->config->get('layout_editor'), true); ?>
						<div class="small mt-10">
							<?php echo JText::_('COM_EASYBLOG_SETTINGS_LAYOUT_SELECT_DEFAULT_EDITOR_NOTE');?>
						</div>
					</div>
				</div>

				<?php echo $this->html('settings.toggle', 'layout_composer_multiple_categories', 'COM_EASYBLOG_SETTINGS_LAYOUT_COMPOSER_ALLOW_MULTIPLE_CATEGORIES'); ?>

				<?php echo $this->html('settings.toggle', 'layout_composer_permalink', 'COM_EASYBLOG_SETTINGS_LAYOUT_COMPOSER_ALLOW_EDITING_PERMALINK'); ?>

				<?php echo $this->html('settings.toggle', 'composer_templates', 'COM_EASYBLOG_SETTINGS_COMPOSER_ENABLE_TEMPLATES'); ?>

				<?php echo $this->html('settings.toggle', 'layout_composer_author_alias', 'COM_EASYBLOG_SETTINGS_LAYOUT_COMPOSER_ENABLE_AUTHOR_ALIAS'); ?>

				<?php echo $this->html('settings.toggle', 'main_password_protect', 'COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_PASSWORD_PROTECTION'); ?>

				<?php echo $this->html('settings.toggle', 'main_composer_exit_alert', 'COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_ALERT_DURING_EXIT'); ?>

				<?php echo $this->html('settings.toggle', 'publish_post_confirmation', 'COM_EB_SETTINGS_COMPOSER_PUBLISH_POST_CONFIRMATION'); ?>

				<?php echo $this->html('settings.toggle', 'layout_composer_customnotifications', 'COM_EB_SETTINGS_COMPOSER_ALLOW_CUSTOM_NOTIFICATIONS'); ?>

				<?php echo $this->html('settings.toggle', 'composer_shortcuts', 'COM_EB_SETTINGS_COMPOSER_ALLOW_SHORTCUTS'); ?>
			</div>
		</div>

		<div class="panel">
			<?php echo $this->html('panel.heading', 'Panels', 'Configure panels in the composer'); ?>

			<div class="panel-body">
				<?php echo $this->html('settings.toggle', 'main_copyrights', 'COM_EASYBLOG_SETTINGS_WORKFLOW_ENABLE_COPYRIGHTS'); ?>

				<?php echo $this->html('settings.toggle', 'layout_composer_fields', 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_FIELDS'); ?>

				<?php echo $this->html('settings.toggle', 'layout_dashboardseo', 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_SEO'); ?>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="panel <?php echo $this->config->get('layout_editor') == 'composer' ? '' : 'hide';?>" data-panel-composer>
			<?php echo $this->html('panel.heading', 'Composer Blocks', 'Configure behavior of built-in composer blocks'); ?>

			<div class="panel-body">
				<?php echo $this->html('settings.toggle', 'layout_composer_customid', 'COM_EB_COMPOSER_CUSTOM_ID'); ?>

				<?php echo $this->html('settings.toggle', 'layout_composer_customcss', 'COM_EASYBLOG_SETTINGS_COMPOSER_CUSTOM_CSS'); ?>

				<?php echo $this->html('settings.toggle', 'layout_composer_font', 'COM_EB_SETTINGS_COMPOSER_FONT_STYLE'); ?>
			</div>
		</div>

		<div class="panel">
			<?php echo $this->html('panel.heading', 'Tags', 'Configure tags behavior in the editor'); ?>

			<div class="panel-body">
				<?php echo $this->html('settings.toggle', 'layout_composer_tags', 'COM_EASYBLOG_SETTINGS_LAYOUT_COMPOSER_ENABLE_TAGS'); ?>

				<div class="form-group">
					<?php echo $this->html('form.label', 'COM_EASYBLOG_LAYOUT_DASHBOARD_MAX_TAGS_ALLOWED', 'max_tags_allowed'); ?>

					<div class="col-md-7">
						<div class="form-inline">
							<div class="form-group">
								<div class="input-group">
									<input type="text" name="max_tags_allowed" id="max_tags_allowed" class="form-control text-center" value="<?php echo $this->config->get('max_tags_allowed', '' );?>" />
									<span class="input-group-addon"><?php echo JText::_('COM_EASYBLOG_TAGS');?></span>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="form-group">
					<?php echo $this->html('form.label', 'COM_EASYBLOG_SETTINGS_COMPOER_MAX_TAGS_SUGGESTION', 'composer_max_tag_suggest'); ?>

					<div class="col-md-7">
						<div class="form-inline">
							<div class="form-group">
								<div class="input-group">
									<input type="text" name="composer_max_tag_suggest" id="composer_max_tag_suggest" class="form-control text-center" value="<?php echo $this->config->get('composer_max_tag_suggest', '' );?>" />
									<span class="input-group-addon"><?php echo JText::_('COM_EASYBLOG_TAGS');?></span>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>

		<div class="panel">
			<?php echo $this->html('panel.heading', 'Revisions', 'Configure behavior of post revisions'); ?>

			<div class="panel-body">
				<?php echo $this->html('settings.toggle', 'layout_composer_history', 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_ENABLE_REVISIONS'); ?>

				<?php $hiddenClass = $this->config->get('layout_composer_history') ? '' : 'hidden'; ?>
				<div class="form-group <?php echo $hiddenClass; ?>" data-revision-limit>
					<?php echo $this->html('form.label', 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_REVISIONS_LIMIT', 'layout_composer_history_limit'); ?>

					<div class="col-md-7">
						<?php echo $this->html('form.toggler', 'layout_composer_history_limit', $this->config->get('layout_composer_history_limit'), 'layout_composer_history_limit');?>
					</div>
				</div>

				<div class="form-group <?php echo $hiddenClass; ?>" data-revision-limit-max>
					<?php echo $this->html('form.label', 'COM_EASYBLOG_SETTINGS_LAYOUT_DASHBOARD_REVISIONS_MAX', 'layout_composer_history_limit_max'); ?>

					<div class="col-md-7">
						<div class="form-inline">
							<div class="form-group">
								<div class="input-group">
									<input type="text" name="layout_composer_history_limit_max" id="layout_composer_history_limit_max" class="form-control text-center" value="<?php echo $this->config->get('layout_composer_history_limit_max', '5' );?>" />
									<span class="input-group-addon"><?php echo JText::_('COM_EASYBLOG_REVISIONS');?></span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
