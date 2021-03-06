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

class EasyBlogBlockHandlerHeading extends EasyBlogBlockHandlerAbstract
{
	public $icon = 'fa fa-header';
	public $element = 'heading';

	public function data()
	{
		$params = $this->table->getParams();
		$default = strtoupper($params->get('default', 'h2'));

		$data = (object) array(
			'level' => $default,
			'default' => JText::_('COM_EASYBLOG_COMPOSER_BLOCKS_ENTER_HEADING')
		);

		return $data;
	}

	/**
	 * Retrieve AMP html
	 *
	 * @since   5.1
	 * @access  public
	 */
	public function getAMPHtml($block)
	{
		return $block->html;
	}
}
