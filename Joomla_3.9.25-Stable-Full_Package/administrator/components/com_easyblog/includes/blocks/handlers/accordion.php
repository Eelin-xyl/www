<?php
/**
* @package      EasyBlog
* @copyright    Copyright (C) 2010 - 2020 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

require_once(dirname(__FILE__) . '/abstract.php');

class EasyBlogBlockHandlerAccordion extends EasyBlogBlockHandlerAbstract
{
	public $icon = 'fa fa-angle-double-down';
	public $nestable = true;
	public $element = 'none';

	public function meta()
	{
		static $meta;

		if (isset($meta)) {
			return $meta;
		}

		$meta = parent::meta();

		return $meta;
	}

	public function data()
	{
		$data = (object) array(
			'id' => '',
			'tabs' => array(
				(object) array(
					'default' => 1,
					'content' => JText::_('COM_EB_BLOCK_ACCORDION_DEFAULT_TITLE')
				)
			)
		);

		return $data;
	}

	/**
	 * Validates if the block contains any contents
	 *
	 * @since   5.0
	 * @access  public
	 * @param   string
	 * @return
	 */
	public function validate($block)
	{

		$blockLib = EB::blocks();
		$content = $blockLib->renderViewableBlock($block, true);

		// convert html entities back to it string. e.g. &nbsp; back to empty space
		$content = html_entity_decode($content);

		// strip html tags to precise length count.
		$content = strip_tags($content);

		// remove any blank space.
		$content = trim($content);

		// get content length
		$contentLength = EBString::strlen($content);
		if ($contentLength > 0) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Displays the html output for a accordion block
	 *
	 * @since   5.4.0
	 * @access  public
	 */
	public function getHtml($block, $textOnly = false, $useRelative = false)
	{

		if ($textOnly) {
			return;
		}

		$block->html = EBString::str_ireplace('(&quot;', "('", $block->html);
		$block->html = EBString::str_ireplace('&quot;)', "')", $block->html);

		$options = (array) $block->data;

		$template = EB::template();
		$template->set('block', $block);
		$contents = $template->output('site/blogs/blocks/accordion');

		return $contents;
	}
}
