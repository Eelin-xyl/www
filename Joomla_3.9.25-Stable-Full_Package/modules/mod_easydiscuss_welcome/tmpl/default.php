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
<div id="ed" class="ed-mod ed-mod--welcome <?php echo $lib->getModuleWrapperClass();?>">
	<div class="ed-mod-card">
		<div class="ed-mod-card__body">
			<?php if ($isLoggedIn) { ?>
				<div class="o-card t-bg--100">
					<div class="o-card__body l-stack">
						<div class="o-media o-media--top">
							<?php if ($params->get('showavatar')) {?>
								<div class="o-media__image">
									<?php echo ED::themes()->html('user.avatar', $my, array('rank' => true, 'status' => true, 'size' => 'md')); ?>
								</div>
							<?php } ?>
							<div class="o-media__body">
								<?php echo ED::themes()->html('user.username', $my); ?>
								<?php if ($params->get('showranks')) { ?>
								<div class="t-font-size--01">( <?php echo $ranking; ?> )</div>
								<?php } ?>
							</div>
						</div>
					</div>
					<?php if ($params->get('showbadges')) { ?>
					<hr>
					<div class="o-card__body">
						<div class="l-stack">
							<div class="o-title-01"><?php echo JText::_('MOD_EASYDISCUSS_WELCOME_YOUR_BADGES'); ?></div>

							<?php if ($badges) { ?>
								<div class="l-cluster l-spaces--xs">
									<div class="">
										<?php foreach ($badges as $badge) { ?>
										<div>
											<a href="<?php echo EDR::_('index.php?option=com_easydiscuss&view=badges&layout=listings&id=' . $badge->id);?>">
											<img src="<?php echo $badge->getAvatar();?>" width="22" title="<?php ED::string()->escape($badge->title);?>" />
											</a>
										</div>
										<?php }?>
									</div>
								</div>
								
							<?php } else { ?>
							<div class="t-font-size--01">
								<?php echo JText::_('MOD_EASYDISCUSS_WELCOME_NO_BADGES_YET'); ?>
							</div>
							<?php } ?>
						</div>
					</div>
					<?php } ?>
					
					<hr>
					<div class="o-card__body">
						<div class="">
							<div class="ed-welcome-nav-item">
								<a class="edit-profile" href="<?php echo ED::getEditProfileLink();?>"> <span><?php echo JText::_('MOD_EASYDISCUSS_WELCOME_EDIT_PROFILE');?></span></a>
							</div>
							
							<?php if ($params->get('showfavourites')) { ?>
								<div class="ed-welcome-nav-item">
									<a class="my-favourites" href="<?php echo EDR::_('index.php?option=com_easydiscuss&view=favourites'); ?>"> <span><?php echo JText::_('MOD_EASYDISCUSS_WELCOME_VIEW_FAVOURITE');?></span></a>
								</div>
							<?php } ?>

							<?php if ($params->get('showsubscriptions')) { ?>
								<div class="ed-welcome-nav-item">
									<a class="my-subscriptions" href="<?php echo EDR::_('index.php?option=com_easydiscuss&view=subscription'); ?>"><span><?php echo JText::_('MOD_EASYDISCUSS_WELCOME_VIEW_SUBSCRIPTIONS');?></span></a>
								</div>
							<?php } ?>

							<?php if (ED::isSiteAdmin($my->id) && $params->get('show_assignedposts')) { ?>
								<div class="ed-welcome-nav-item">
									<a class="my-assigned" href="<?php echo EDR::_('index.php?option=com_easydiscuss&view=assigned'); ?>"><span><?php echo JText::_('MOD_EASYDISCUSS_WELCOME_VIEW_ASSIGNED_POST');?></span></a>
								</div>
							<?php } ?>

							<?php if ($params->get('show_mydiscussions')) { ?>
								<div class="ed-welcome-nav-item">
									<a class="user-discussions" href="<?php echo $my->getLink(); ?>"><span><?php echo JText::_('MOD_EASYDISCUSS_WELCOME_MY_DISCUSSIONS');?></span></a>
								</div>
							<?php } ?>

							<?php if ($params->get('show_browsediscussions')) { ?>
								<div class="ed-welcome-nav-item">
									<a class="all-discussions" href="<?php echo EDR::_('index.php?option=com_easydiscuss');?>"><span><?php echo JText::_('MOD_EASYDISCUSS_WELCOME_BROWSE_DISCUSSIONS');?></span></a>
								</div>
							<?php } ?>

							<?php if ($params->get('show_browsecategories')) { ?>
								<div class="ed-welcome-nav-item">
									<a class="discuss-categories" href="<?php echo EDR::_('index.php?option=com_easydiscuss&view=categories');?>"><span><?php echo JText::_('MOD_EASYDISCUSS_WELCOME_BROWSE_CATEGORIES');?></span></a>
								</div>
							<?php } ?>

							<?php if ($params->get('show_browsetags')) { ?>
								<div class="ed-welcome-nav-item">
									<a class="discuss-tags" href="<?php echo EDR::_('index.php?option=com_easydiscuss&view=tags');?>"><span><?php echo JText::_('MOD_EASYDISCUSS_WELCOME_BROWSE_TAGS');?></span></a>
								</div>
							<?php } ?>

							<?php if ($params->get('show_browsebadges')) { ?>
								<div class="ed-welcome-nav-item">
									<a class="discuss-badges" href="<?php echo EDR::_('index.php?option=com_easydiscuss&view=badges');?>"><span><?php echo JText::_('MOD_EASYDISCUSS_WELCOME_BROWSE_BADGES');?></span></a>
								</div>
							<?php } ?>
						</div>
					</div>
					<div class="o-card__footer">
						<div class="ed-welcome-nav-item">
							<a class="ed-welcome-logout" href="<?php echo JRoute::_('index.php?option=com_users&task=user.logout&' . ED::getToken() . '=1&return='.$return);?>"><span><?php echo JText::_('MOD_EASYDISCUSS_WELCOME_SIGN_OUT');?></span>
								<i class="fas fa-sign-out-alt t-ml--auto"></i>
							</a>
						</div>
						
					</div>
				</div>
				
			<?php } else if ($params->get('enablelogin')) { ?>
				<div class="o-card t-bg--100">
					<div class="o-card__body l-stack">
						<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" name="login" id="form-login" >
							<?php echo $params->get('pretext'); ?>
							<div class="l-stack">
								<div>
									<label for="discuss-welcome-username" class="o-form-label"><?php echo JText::_('MOD_EASYDISCUSS_WELCOME_USERNAME'); ?></label>
									<input type="text" id="discuss-welcome-username" name="username" class="o-form-control" size="18">
								</div>
								<div>
									<label for="discuss-welcome-password" class="o-form-label"><?php echo JText::_('MOD_EASYDISCUSS_WELCOME_PASSWORD'); ?></label>
									<input type="password" id="discuss-welcome-password" name="password" class="o-form-control" size="18" >
								</div>

								<?php if (ED::isTwoFactorEnabled()) { ?>
									<div>
										<label for="discuss-welcome-secretkey" class="o-form-label"><?php echo JText::_('MOD_EASYDISCUSS_WELCOME_SECRETKEY'); ?></label>
										<input type="secretkey" id="discuss-welcome-secretkey" name="secretkey" class="o-form-control" size="18" >
									</div>
								<?php } ?>
								<?php if (JPluginHelper::isEnabled('system', 'remember')) { ?>
									<div class="o-form-check">
										<input type="checkbox" class="o-form-check-input" id="modlgn_remember" name="remember" value="yes" title="<?php echo JText::_('MOD_EASYDISCUSS_WELCOME_REMEMBER_ME');?>" alt="<?php echo JText::_('MOD_EASYDISCUSS_WELCOME_REMEMBER_ME');?>">
										<label for="modlgn_remember" class="o-form-check-label"><?php echo JText::_('MOD_EASYDISCUSS_WELCOME_REMEMBER_ME');?></label>
									</div>
								<?php } ?>
								<div>
									<input type="submit" value="<?php echo JText::_('MOD_EASYDISCUSS_WELCOME_SIGN_IN');?>" name="Submit" class="o-btn o-btn--primary">
								</div>

								<div class="account-register t-font-size--01">
									<?php echo JText::sprintf('MOD_EASYDISCUSS_WELCOME_FORGOT_PASSOWRD_OR_USERNAME', JRoute::_('index.php?option=com_users&view=reset'), JRoute::_('index.php?option=com_users&view=remind')) ?>
									<br>
									<?php if ($allowRegister) { ?>
										<a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>"><?php echo JText::_('MOD_EASYDISCUSS_WELCOME_CREATE_ACCOUNT');?></a>
									<?php } ?>
								</div>
							</div>
							
							<?php echo $params->get('posttext'); ?>
							<input type="hidden" name="option" value="com_users" />
							<input type="hidden" name="task" value="user.login" />
							<input type="hidden" name="return" value="<?php echo $return; ?>" />
							<?php echo JHTML::_('form.token'); ?>
						</form>
					</div>
				</div>
			<?php } ?>

		</div>
	</div>


</div>
