<?php
/**
* @package      EasyBlog
* @copyright    Copyright (C) 2010 - 2017 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

$styles = array(
	array(
		'label' => JText::_('COM_EASYBLOG_COMPOSER_BLOCKS_BUTTON_COLOR_DEFAULT_TYPE'),
		'classname' => 'btn-default'
	),
	array(
		'label' => JText::_('COM_EASYBLOG_COMPOSER_BLOCKS_BUTTON_COLOR_PRIMARY_TYPE'),
		'classname' => 'btn-primary'
	),
	array(
		'label' => JText::_('COM_EASYBLOG_COMPOSER_BLOCKS_BUTTON_COLOR_SUCCESS_TYPE'),
		'classname' => 'btn-success'
	),
	array(
		'label' => JText::_('COM_EASYBLOG_COMPOSER_BLOCKS_BUTTON_COLOR_INFO_TYPE'),
		'classname' => 'btn-info'
	),
	array(
		'label' => JText::_('COM_EASYBLOG_COMPOSER_BLOCKS_BUTTON_COLOR_WARNING_TYPE'),
		'classname' => 'btn-warning'
	),
	array(
		'label' => JText::_('COM_EASYBLOG_COMPOSER_BLOCKS_BUTTON_COLOR_DANGER_TYPE'),
		'classname' => 'btn-danger'
	)
);
?>

<div class="eb-composer-fieldset">
	<div class="eb-composer-fieldset-header">
		<strong><?php echo JText::_('COM_EASYBLOG_BLOCKS_GENERAL_ATTRIBUTES'); ?></strong>
	</div>
	<div class="eb-composer-fieldset-content o-form-horizontal">
		<?php echo $this->html('composer.field', 'composer.textbox', 'link', 'COM_EASYBLOG_COMPOSER_BLOCKS_BUTTON_LINK', $data->link, 'data-button-link'); ?>

		<div class="o-form-group">
			<label class="o-control-label eb-composer-field-label">
				<?php echo JText::_('COM_EASYBLOG_COMPOSER_BLOCKS_BUTTON_OPEN_TARGET'); ?>
			</label>
			<div class="o-control-input">
				<select class="o-form-control" name="target" data-button-target>
					<option value=""><?php echo JText::_('COM_EASYBLOG_COMPOSER_BLOCKS_BUTTON_ATTRIBUTE_TARGET_NONE'); ?></option>
					<option value="_blank">_blank</option>
					<option value="_self">_self</option>
					<option value="_parent">_parent</option>
					<option value="_top">_top</option>
				</select>
			</div>
		</div>

		<?php echo $this->html('composer.field', 'form.toggler', 'nofollow', 'COM_EASYBLOG_COMPOSER_BLOCKS_BUTTON_ATTRIBUTE_NOFOLLOW', $data->nofollow, 'data-button-nofollow'); ?>
	</div>
</div>

<div class="eb-composer-fieldset">
	<div class="eb-composer-fieldset-header">
		<strong><?php echo JText::_('COM_EASYBLOG_COMPOSER_BLOCKS_BUTTON_STYLE'); ?></strong>
	</div>
	<div class="eb-composer-fieldset-content">
		<div class="o-form-group">
			<div class="eb-swatch swatch-grid">
				<div class="row">
					<?php foreach($styles as $style) { ?>
					<div class="col-sm-4">
						<div class="eb-swatch-item eb-composer-button-preview" data-style="<?php echo $style['classname']; ?>" data-eb-composer-button-swatch-item>
							<div class="eb-swatch-preview">
								<span class="btn <?php echo $style['classname']; ?>"><?php echo JText::_('COM_EASYBLOG_COMPOSER_BLOCKS_COLOR_BUTTON'); ?></span>
							</div>
							<div class="eb-swatch-label">
								<span><?php echo $style['label']; ?></span>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="eb-composer-fieldset">
	<div class="eb-composer-fieldset-header">
		<strong><?php echo JText::_('COM_EASYBLOG_COMPOSER_BLOCKS_BUTTON_SIZE'); ?></strong>
	</div>
	<div class="eb-composer-fieldset-content">
		<div class="o-form-group">
			<div class="eb-list" data-eb-composer-block-button-size>
				<div class="eb-list-item-group">
					<div class="eb-list-item" data-size="btn-xs"><?php echo JText::_('COM_EASYBLOG_COMPOSER_BLOCKS_BUTTON_SIZE_XSMALL');?></div>
					<div class="eb-list-item" data-size="btn-sm"><?php echo JText::_('COM_EASYBLOG_COMPOSER_BLOCKS_BUTTON_SIZE_SMALL');?></div>
					<div class="eb-list-item active" data-size=""><?php echo JText::_('COM_EASYBLOG_COMPOSER_BLOCKS_BUTTON_SIZE_STANDARD');?></div>
					<div class="eb-list-item" data-size="btn-lg"><?php echo JText::_('COM_EASYBLOG_COMPOSER_BLOCKS_BUTTON_SIZE_LARGE');?></div>
				</div>
			</div>
		</div>
	</div>
</div>
