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

class DiscussVideoMetaCafe
{
	private function getCode( $url )
	{
		preg_match( '/\/watch\/(.*)(?=(\/).)/i' , $url , $matches );

		if( !empty( $matches ) )
		{
			return $matches[1];
		}

		return false;
	}

	public function getEmbedHTML($url, $isAmp = false)
	{
		$code	= $this->getCode( $url );

		$config	= ED::config();
		$width	= $config->get( 'bbcode_video_width' );
		$height	= $config->get( 'bbcode_video_height' );

		if ($code) {
			$src = str_ireplace('/watch/', '/embed/', $url);
			// return '<embed flashVars="playerVars=showStats=yes|autoPlay=no" src="http://www.metacafe.com/fplayer/' . $code . '/easydiscuss.swf" width="' . $width . '" height="' . $height . '" wmode="transparent" allowFullScreen="true" allowScriptAccess="always" name="Metacafe_' . $code . '" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed>';

			if ($isAmp) {
				return '<amp-iframe  src="' . $src . '" width="' . $width . '" height="' . $height . '" frameborder="0" layout="responsive" sandbox="allow-scripts allow-same-origin"></amp-iframe>';
			}

			return '<div class="ed-video ed-video--16by9"><iframe title="" width="' . $width . '" height="' . $height . '" src="' . $src . '" frameborder="0" allowfullscreen></iframe></div>';
		}
		return false;
	}
}
