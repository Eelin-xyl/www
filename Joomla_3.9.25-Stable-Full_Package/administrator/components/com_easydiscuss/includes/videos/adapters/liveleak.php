<?php
/**
 * @package		EasyDiscuss
 * @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 *
 * EasyDiscuss is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die('Restricted access');

class DiscussVideoLiveLeak
{
	private function getCode($url)
	{
		preg_match('/view\?i=(.*)/i', $url, $matches);

		if (!empty($matches)) {
			return $matches[1];
		}
		
		return false;
	}
	
	public function getEmbedHTML($url, $isAmp = false)
	{
		$code = $this->getCode($url);

		$config	= ED::config();
		$width	= $config->get('bbcode_video_width');
		$height	= $config->get('bbcode_video_height');

		if ($code) {
			if ($isAmp) {
				return '<amp-iframe  src="https://www.liveleak.com/e/' . $code . '" width="' . $width . '" height="' . $height . '" frameborder="0" layout="responsive" sandbox="allow-scripts allow-same-origin"></amp-iframe>';
			}

			return '<iframe width="' . $width . '" height="' . $height . '" src="https://www.liveleak.com/e/' . $code . '" frameborder="0" allowfullscreen></iframe>';
		}

		return false;
	}
}