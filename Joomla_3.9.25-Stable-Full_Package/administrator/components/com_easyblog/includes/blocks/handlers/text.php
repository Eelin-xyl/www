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

require_once(__DIR__ . '/abstract.php');

class EasyBlogBlockHandlerText extends EasyBlogBlockHandlerAbstract
{
	public $icon = 'fa fa-reorder';
	public $nestable = true;
	public $element = 'article';

	public function meta()
	{
		static $meta;

		if (isset($meta)) {
			return $meta;
		}

		$meta = parent::meta();

		$template = EB::template();
		$meta->blockWrapper = $template->output('site/composer/blocks/handlers/text/block_wrapper');
		$meta->contentWrapper = $template->output('site/composer/blocks/handlers/text/content_wrapper');

		return $meta;
	}

	public function data()
	{
		$theme = EB::template();
		$content = $theme->output('site/composer/blocks/handlers/text/html');

		$data = (object) array(
			'content' => $content,
			'default' => JText::_('COM_EASYBLOG_BLOCKS_TEXT_PLACEHOLDER')
		);

		return $data;
	}

	/**
	 * Allows caller to update block
	 *
	 * @since   5.0
	 * @access  public
	 */
	public function updateBlock($block, $data)
	{
		// Normalize data
		$defaultData = $this->data();
		$data = (object) array_merge((array) $defaultData, (array) $data);

		// Set block properties
		$block->html = $data->content;
		$block->editableHtml = $data->content;
		$block->text = strip_tags($data->content);
		$block->data = $data;

		return $block;
	}

	/**
	 * Retrieve AMP html
	 *
	 * @since   5.1
	 * @access  public
	 */
	public function getAMPHtml($block)
	{
		// style attribute is not allowed in AMP
		$output = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $block->html);

		return $output;
	}
}
