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
<div class="row">
	<div class="col-lg-6">
		<div class="panel">
			<?php echo $this->html('panel.heading', 'COM_EASYBLOG_CATEGORIES_EDIT_GENERAL', 'COM_EASYBLOG_CATEGORIES_EDIT_GENERAL_INFO'); ?>
			<div class="panel-body">
				<div class="form-group">
					<label for="page_title" class="col-md-5">
						<?php echo JText::_('COM_EASYBLOG_CATEGORIES_PRIVACY'); ?>

						<i data-html="true" data-placement="top" data-title="<?php echo JText::_('COM_EASYBLOG_CATEGORIES_PRIVACY'); ?>" 
							data-content="<?php echo JText::_('COM_EASYBLOG_CATEGORY_PRIVACY_TIPS');?>" data-eb-provide="popover" class="fa fa-question-circle pull-right"></i>
					</label>

					<div class="col-md-7">
						<?php if ($this->config->get('main_category_privacy')) { ?>
							<?php echo JHTML::_('select.genericlist' , EB::privacy()->getOptions('category') , 'private' , 'class="form-control"' , 'value' , 'text', $category->private);?>
						<?php } else { ?>
							<?php echo JText::_('COM_EB_CATEGORY_PRIVACY_COMPLEX_STRUCTURE_DISABLED'); ?>
							<div>
								<a href="index.php?option=com_easyblog&view=settings" class="btn btn-sm btn-default"><?php echo JText::_('COM_EB_VIEW_SETTINGS');?></a>
							</div>
							<input type="hidden" name="private" value="0" />
						<?php }?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="panel<?php echo $category->private != 2 ? ' hide' : '';?>" data-category-access>
			<?php echo $this->html('panel.heading', 'COM_EASYBLOG_CATEGORIES_ASSIGNED_PERMISSIONS', 'COM_EASYBLOG_CATEGORIES_ASSIGNED_PERMISSIONS_INFO'); ?>

			<div class="panel-body">
				<?php if ($category->default) { ?>
					<div class="eb-alert row-table alert alert-notice">
						<div class="col-cell cell-tight cell-sign">
							<i class="fa fa-times-circle"></i>
						</div>
						<div class="col-cell cell-text"><?php echo JText::_('COM_EB_CATEGORIES_ASSIGNED_PERMISSIONS_DENIED');?></div>
					</div>
				<?php } else { ?>
					<div class="form-group">
						<label for="<?php echo 'category_acl_view';?>" class="col-md-5">
							<?php echo JText::_('COM_EASYBLOG_CATEGORIES_ACL_VIEW_TITLE');?>

							<i data-html="true" data-placement="top" data-title="<?php echo JText::_('COM_EASYBLOG_CATEGORIES_ACL_VIEW_TITLE'); ?>" data-content="<?php echo JText::_('COM_EASYBLOG_CATEGORIES_ACL_VIEW_DESC');?>" data-eb-provide="popover" class="fa fa-question-circle pull-right"></i>
						</label>

						<div class="col-md-7">
							<select multiple="multiple" name="category_acl_view[]" class="form-control" style="height: 150px;">
								<?php foreach ($groups[CATEGORY_ACL_ACTION_VIEW] as $group) { ?>
								<option value="<?php echo $group->groupid; ?>" style="padding:2px;" <?php echo ($group->status) ? 'selected="selected"' : ''; ?> ><?php echo $group->groupname; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label for="category_acl_type" class="col-md-5">
							<?php echo JText::_('COM_EB_CATEGORIES_ASSIGNED_PERMISSIONS_SELECT_CREATION_TYPE');?>
							<i data-html="true" data-placement="top" data-title="<?php echo JText::_('COM_EB_CATEGORIES_ASSIGNED_PERMISSIONS_SELECT_CREATION_TYPE'); ?>" data-content="<?php echo JText::_('COM_EB_CATEGORIES_ASSIGNED_PERMISSIONS_SELECT_CREATION_TYPE_DESC');?>" data-eb-provide="popover" class="fa fa-question-circle pull-right"></i>
						</label>

						<div class="col-md-7">
							<select name="params[category_acl_type]" id="category_acl_type" class="form-control">
								<option <?php echo $params->get('category_acl_type') == CATEGORY_ACL_ACTION_SELECT ? 'selected="selected"' : '';?> value="<?php echo CATEGORY_ACL_ACTION_SELECT;?>">
									<?php echo JText::_('COM_EB_CATEGORIES_ASSIGNED_PERMISSIONS_SELECT_CREATION_TYPE_GROUP');?>
								</option>
								<option <?php echo $params->get('category_acl_type') == CATEGORY_ACL_ACTION_SPECIFIC ? 'selected="selected"' : '';?> value="<?php echo CATEGORY_ACL_ACTION_SPECIFIC;?>">
									<?php echo JText::_('COM_EB_CATEGORIES_ASSIGNED_PERMISSIONS_SELECT_CREATION_TYPE_USERS');?>
								</option>
							</select>


							<div class="mt-10 <?php echo $params->get('category_acl_type') == CATEGORY_ACL_ACTION_SELECT ? '' : 'hide';?>" data-category-acl-select>
								<select multiple="multiple" name="category_acl_select[]" class="form-control" style="height: 150px;">
									<?php foreach ($groups[CATEGORY_ACL_ACTION_SELECT] as $group) { ?>
										<option value="<?php echo $group->groupid; ?>" style="padding:2px;" <?php echo ($group->status) ? 'selected="selected"' : ''; ?> ><?php echo $group->groupname; ?></option>
									<?php } ?>
								</select>
							</div>

							<div class="mt-10 <?php echo $params->get('category_acl_type') == CATEGORY_ACL_ACTION_SPECIFIC ? '' : 'hide';?>" data-category-acl-specific>
								<?php echo $this->html('form.usertags', 'category_acl_specific', $usertags, '', array('category' => $category->id)); ?>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>

	</div>
</div>
