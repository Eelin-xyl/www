<?php
/**
* @package      EasyDiscuss
* @copyright    Copyright (C) 2010 - 2016 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<div class="row">
	<div class="col-md-6">
		<div class="panel">
			<?php echo $this->html('panel.head', 'COM_EASYDISCUSS_MIGRATORS_KUNENA_DETAILS'); ?>

			<div class="panel-body">
				<div class="o-form-horizontal">
					<?php if ($exists) { ?>
					<p><?php echo JText::_('COM_EASYDISCUSS_MIGRATORS_NOTICE');?></p>
					
					<ul class="t-mt--md t-mb--md">
						<li><?php echo JText::_('COM_EASYDISCUSS_MIGRATORS_NOTICE_BACKUP'); ?></li>
						<li><?php echo JText::_('COM_EASYDISCUSS_MIGRATORS_NOTICE_OFFLINE'); ?></li>
					</ul>

					<?php echo $this->html('forms.toggle', 'migrator_reset_points', 'COM_EASYDISCUSS_MIGRATORS_RESET_HITS', $this->config->get('migrator_reset_points'), array('attributes' => 'data-migrator-kunena-hits')); ?>
					<?php echo $this->html('forms.toggle', 'migrator_user_signature', 'COM_EASYDISCUSS_MIGRATORS_USER_SIGNATURE', $this->config->get('migrator_user_signature'), array('attributes' => 'data-migrator-kunena-signature')); ?>
					<?php echo $this->html('forms.toggle', 'migrator_user_avatar', 'COM_EASYDISCUSS_MIGRATORS_USER_AVATAR', $this->config->get('migrator_user_avatar'), array('attributes' => 'data-migrator-kunena-avatar')); ?>

					<a href="javascript:void(0);" class="btn btn-success" data-ed-migrate>
						<?php echo JText::_('COM_EASYDISCUSS_MIGRATOR_MIGRATE'); ?>
					</a>

					<?php } else { ?>
						<p><?php echo JText::_('COM_EASYDISCUSS_MIGRATORS_KUNENA_NOT_INSTALLED'); ?></p>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="panel">
			<div class="panel-head">
				<b><?php echo JText::_('COM_EASYDISCUSS_PROGRESS');?></b>
				<span data-progress-loading class="eb-loader-o size-sm hide"></span>
			</div>

			<div class="panel-body">
				<div data-progress-empty><?php echo JText::_('COM_EASYDISCUSS_MIGRATOR_NO_PROGRESS_YET'); ?></div>
				<div data-progress-status style="overflow:auto; height:98%;max-height: 300px;"></div>
			</div>
		</div>
	</div>
</div>

