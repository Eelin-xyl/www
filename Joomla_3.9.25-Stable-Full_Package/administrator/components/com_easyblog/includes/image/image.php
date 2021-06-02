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

jimport('joomla.filesystem.file');

class EasyBlogImage extends EasyBlog
{
	public static $xssTags = array('abbr','acronym','address','applet','area','audioscope','base','basefont','bdo','bgsound','big','blackface','blink','blockquote','body','bq','br','button','caption','center','cite','code','col','colgroup','comment','custom','dd','del','dfn','dir','div','dl','dt','em','embed','fieldset','fn','font','form','frame','frameset','h1','h2','h3','h4','h5','h6','head','hr','html','iframe','ilayer','img','input','ins','isindex','keygen','kbd','label','layer','legend','li','limittext','link','listing','map','marquee','menu','meta','multicol','nobr','noembed','noframes','noscript','nosmartquotes','object','ol','optgroup','option','param','plaintext','pre','rt','ruby','s','samp','script','select','server','shadow','sidebar','small','spacer','span','strike','strong','style','sub','sup','table','tbody','td','textarea','tfoot','th','thead','title','tr','tt','ul','var','wbr','xml','xmp','!DOCTYPE', '!--');

	/**
	 * Proper check for file contents to ensure user doesn't upload anything funky
	 *
	 * @since	5.1
	 * @access	public
	 */
	public function canUpload($file, &$err)
	{
		$config = EB::config();

		if (empty($file['name'])) {
			$err = 'COM_EASYBLOG_WARNEMPTYFILE';
			return false;
		}

		jimport('joomla.filesystem.file');
		if ($file['name'] !== JFile::makesafe($file['name'])) {
			$err = 'COM_EASYBLOG_WARNFILENAME';
			return false;
		}

		$format = strtolower(JFile::getExt($file['name']));

		if (!$this->isImage($file['name'])) {
			$err = 'COM_EASYBLOG_WARNINVALIDIMG';
			return false;
		}

		$maxWidth	= 160;
		$maxHeight	= 160;

		// maxsize should get from eblog config
		//$maxSize	= 2000000; //2MB
		//$maxSize	= 200000; //200KB

		// 1 megabyte == 1048576 byte
		$byte   		= 1048576;
		$uploadMaxsize  = (float) $config->get('main_upload_image_size', 0 );
		$maxSize 		= $uploadMaxsize * $byte;

		if ($maxSize > 0 && (float) $file['size'] > $maxSize) {
			$err = 'COM_EASYBLOG_WARNFILETOOLARGE';
			return false;
		}

		$user = JFactory::getUser();
		$imginfo = null;

		if(($imginfo = getimagesize($file['tmp_name'])) === FALSE) {
			$err = 'COM_EASYBLOG_WARNINVALIDIMG';
			return false;
		}

		return true;
	}

	/**
	 * Proper check for file contents to ensure user doesn't upload anything funky
	 *
	 * @since	5.1
	 * @access	public
	 */
	public function canUploadFile($file)
	{
		if (!isset($file['name']) || empty($file['name'])) {
			return EB::exception('COM_EASYBLOG_IMAGE_UPLOADER_PLEASE_INPUT_A_FILE_FOR_UPLOAD', EASYBLOG_MSG_ERROR);
		}

		// Get the extension
		$extension = JFile::getExt($file['name']);
		$extension = strtolower($extension);

		// Check for allowed extensions
		if (!$this->isExtensionAllowed($extension)) {
			return EB::exception('COM_EASYBLOG_FILE_NOT_ALLOWED', EASYBLOG_MSG_ERROR);
		}

		// Ensure that the file that is being uploaded isn't too huge
		$fileSize = (int) $file['size'];

		if ($this->isExceededFilesizeLimit($fileSize)) {
			return EB::exception('COM_EASYBLOG_WARNFILETOOLARGE', EASYBLOG_MSG_ERROR);
		}

		// Ensure that the user doesn't do any funky stuff to the image
		if ($this->containsXSS($file['tmp_name'])) {
			return EB::exception('COM_EASYBLOG_FILE_CONTAIN_XSS', EASYBLOG_MSG_ERROR);
		}

		return true;
	}

	/**
	 * Downloads an external image given a url
	 *
	 * @since	5.4.0
	 * @access	public
	 */
	public function download($url)
	{
		$connector = EB::connector();
		$connector->addUrl($url);
		$connector->execute();

		$contents = $connector->getResult($url);

		$containsXss = $this->contentsContainXss($contents);

		if ($containsXss) {
			throw new Exception('Content of url contains known xss codes');
		}

		// getimagesizefromstring is only available from php 5.4 onwards :(
		// so we need to store the strings as a file first.
		$tmpName = md5($url);

		$jConfig = EB::jConfig();
		$tmpPath = $jConfig->get('tmp_path') . '/' . $tmpName;

		JFile::write($tmpPath, $contents);

		$extension = $this->getExtension($tmpPath);

		// Check for allowed extensions
		if (!$this->isExtensionAllowed($extension)) {

			JFile::delete($tmpPath);

			return EB::exception('COM_EASYBLOG_FILE_NOT_ALLOWED', EASYBLOG_MSG_ERROR);
		}

		// Ensure that the file that is being uploaded isn't too huge
		$fileSize = (int) filesize($tmpPath);
		$exceededFilesize = $this->isExceededFilesizeLimit($fileSize);

		if ($exceededFilesize) {
			JFile::delete($tmpPath);
			return EB::exception('COM_EASYBLOG_WARNFILETOOLARGE', EASYBLOG_MSG_ERROR);
		}


		// Prepare the image data
		$file = getimagesize($tmpPath);
		$file['name'] = basename($url);
		$file['tmp_name'] = $tmpPath;
		$file['type'] = $file['mime'];

		$media = EB::mediamanager();
		$uri = 'user:' . $this->my->id;

		$adapter = $media->getAdapter($uri);
		$result = $adapter->upload($file);

		return $result;
	}

	/**
	 * Given a url for the file, get the name
	 *
	 * @since	5.4.0
	 * @access	public
	 */
	public function getFileName($imageUrl)
	{
		if (!$imageUrl) {
			return '';
		}

		$fileName = basename($imageUrl);
		return $fileName;
	}

	/**
	 * Retrieves a file extension given the name
	 *
	 * @since	5.4.0
	 * @access	public
	 */
	public function getExtension($path)
	{
		$info = getimagesize($path);

		switch ($info['mime']) {
			case 'image/jpeg':
				$extension  = 'jpg';
			break;

			case 'image/png':
			case 'image/x-png':
			default:
				$extension  = 'png';
			break;
		}

		return $extension;
	}

	/**
	 * Checks if the file is an image
	 * @param string The filename
	 * @return file type
	 */
	public static function getTypeIcon( $fileName )
	{
		// Get file extension
		return strtolower(substr($fileName, strrpos($fileName, '.') + 1));
	}

	/**
	 * Checks if an image file name is an image type
	 *
	 * @since	5.1
	 * @access	public
	 */
	public function isImage($fileName)
	{
		static $imageTypes = 'gif|jpg|jpeg|png|jfif|webp';

		return preg_match("/$imageTypes/i",$fileName);
	}

	/**
	 * Determines if the image extension is allowed
	 *
	 * @since	5.4.0
	 * @access	public
	 */
	public function isExtensionAllowed($extension)
	{
		require_once(JPATH_ADMINISTRATOR . '/components/com_easyblog/includes/mediamanager/mediamanager.php');

		// Check for allowed extensions
		$allowed = EBMM::getAllowedExtensions();

		if (!in_array($extension, $allowed)) {
			return false;
		}

		return true;
	}

	/**
	 * Determines if the image size exceeded limit
	 *
	 * @since	5.4.0
	 * @access	public
	 */
	public function isExceededFilesizeLimit($size)
	{
		require_once(JPATH_ADMINISTRATOR . '/components/com_easyblog/includes/mediamanager/mediamanager.php');

		$maximumAllowed = EBMM::getAllowedFilesize();

		if ($maximumAllowed !== false && $size > $maximumAllowed) {
			return true;
		}

		return false;
	}

	/**
	 * Determines if the contents contains any of the known possible xss tags
	 *
	 * @since	5.4.0
	 * @access	public
	 */
	private function contentsContainXss($contents)
	{
		foreach (self::$xssTags as $tag) {
			// If this tag is matched anywhere in the contents, we can safely assume that this file is dangerous
			if (stristr($contents, '<' . $tag . ' ') || stristr($contents, '<' . $tag . '>') || stristr($contents, '<?php') || stristr($contents, '?\>')) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Checks if the file contains any funky html tags
	 *
	 * @since	5.1
	 * @access	public
	 */
	public function containsXSS($path)
	{
		// Sanitize the content of the files
		$contents = file_get_contents($path, false, null, 0, 256);

		// If we can't read the file, just skip this altogether
		if (!$contents) {
			return false;
		}

		return $this->contentsContainXss($contents);
	}

	public function parseSize($size)
	{
		if ($size < 1024) {
			return $size . ' bytes';
		}
		else
		{
			if ($size >= 1024 && $size < 1024 * 1024) {
				return sprintf('%01.2f', $size / 1024.0) . ' Kb';
			} else {
				return sprintf('%01.2f', $size / (1024.0 * 1024)) . ' Mb';
			}
		}
	}

	public static function imageResize($width, $height, $target)
	{
		//takes the larger size of the width and height and applies the
		//formula accordingly...this is so this script will work
		//dynamically with any size image
		if ($width > $height) {
			$percentage = ($target / $width);
		} else {
			$percentage = ($target / $height);
		}

		//gets the new value and applies the percentage, then rounds the value
		$width = round($width * $percentage);
		$height = round($height * $percentage);

		return array($width, $height);
	}

	public static function countFiles( $dir )
	{
		$total_file = 0;
		$total_dir = 0;

		if (is_dir($dir)) {
			$d = dir($dir);

			while (false !== ($entry = $d->read())) {
				if (substr($entry, 0, 1) != '.' && is_file($dir . DIRECTORY_SEPARATOR . $entry) && strpos($entry, '.html') === false && strpos($entry, '.php') === false) {
					$total_file++;
				}
				if (substr($entry, 0, 1) != '.' && is_dir($dir . DIRECTORY_SEPARATOR . $entry)) {
					$total_dir++;
				}
			}

			$d->close();
		}

		return array ( $total_file, $total_dir );
	}

	public static function getAvatarDimension($avatar)
	{
		$config			= EB::config();

		//resize the avatar image
		$avatar	= JPath::clean( JPATH_ROOT . DIRECTORY_SEPARATOR . $avatar );
		$info	= @getimagesize($avatar);
		if(! $info === false)
		{
			$thumb	= EasyImageHelper::imageResize($info[0], $info[1], 60);
		}
		else
		{
			$thumb  = array( EBLOG_AVATAR_THUMB_WIDTH, EBLOG_AVATAR_THUMB_HEIGHT);
		}

		return $thumb;
	}

	/**
	 * Retrieves the relative path to the respective avatar storage
	 *
	 * @since	4.0
	 * @access	public
	 */
	public static function getAvatarRelativePath($type = 'profile')
	{
		$config = EB::config();

		// Default path
		$path = '';

		if ($type == 'category') {
			$path = $config->get('main_categoryavatarpath');
		} else if($type == 'team') {
			$path = $config->get('main_teamavatarpath');
		} else {
			$path = $config->get('main_avatarpath');
		}

		// Ensure that there are no trailing slashes
		$path = rtrim($path, '/');

		return $path;
	}


	public static function rel2abs($rel, $base)
	{
		return EB::string()->rel2abs( $rel, $base );
	}

	/**
	 * Generates a standard response
	 *
	 * @since	5.4.0
	 * @access	public
	 */
	private function getMessageObj($code = '', $message = '', $item = false)
	{
		$obj = new stdClass();
		$obj->code = $code;
		$obj->message = $message;

		if ($item) {
			$obj->item = $item;
		}

		return $obj;
	}

	public function upload( $folder , $filename , $file , $baseUri , $storagePath , $subfolder = '' )
	{
		$config	= EB::config();
		$user   = JFactory::getUser();

		if (isset($file['name']))
		{
			if($config->get('main_resize_original_image'))
			{
				$maxWidth	= $config->get( 'main_original_image_width' );
				$maxHeight	= $config->get( 'main_original_image_height' );

				$image	= EB::simpleimage();
				$image->load($file['tmp_name']);
				$image->resizeWithin( $maxWidth , $maxHeight );

				$uploadStatus = $image->save( $storagePath , $image->image_type , $config->get( 'main_original_image_quality' ) );
			}
			else
			{
				$uploadStatus = JFile::upload($file['tmp_name'], $storagePath);
			}

			// @task: thumbnail's file name
			$storagePathThumb	= JPath::clean( $folder . DIRECTORY_SEPARATOR . EBLOG_MEDIA_THUMBNAIL_PREFIX . $filename );

			// Generate a thumbnail for each uploaded images
			$image 	= EB::simpleimage();
			$image->load($storagePath);

			$image->resizeWithin( $config->get( 'main_image_thumbnail_width' ) , $config->get( 'main_image_thumbnail_height' ) );
			$image->save( $storagePathThumb , $image->image_type , $config->get( 'main_image_thumbnail_quality' ) );

			if (!$uploadStatus) {
				return $this->getMessageObj(EBLOG_MEDIA_PERMISSION_ERROR, JText::_('COM_EASYBLOG_IMAGE_MANAGER_UPLOAD_ERROR'));
			} else {

				// file uploaded. Now we test if the index.html was there or not.
				// if not, copy from easyblog root into this folder
				if (!JFile::exists($folder . '/index.html')) {
					$targetFile = JPATH_ROOT . '/components/com_easyblog/index.html';
					$destFile = $folder . '/index.html';

					if (JFile::exists($targetFile)) {
						JFile::copy($targetFile, $destFile);
					}
				}

				return $this->getMessageObj(EBLOG_MEDIA_UPLOAD_SUCCESS, JText::_('COM_EASYBLOG_IMAGE_MANAGER_UPLOAD_SUCCESS'), EasyBlogImageDataHelper::getObject( $folder , $filename , $baseUri , $subfolder ));
			}
		} else {
			return $this->getMessageObj(EBLOG_MEDIA_TRANSPORT_ERROR, JText::_('COM_EASYBLOG_MEDIA_MANAGER_NO_UPLOAD_FILE'));
		}

		return $response;
	}

	/**
	 * Process image to be used in AMP Article
	 *
	 * @since	5.1
	 * @access	public
	 */
	public function processAMP($content, $userId = '')
	{
		$pattern = '#<img[^>]*>#i';
		preg_match_all($pattern, $content, $matches);

		if (!$matches) {
			return array();
		}

		foreach ($matches[0] as $image) {

			preg_match('/src=["\']([^"\']+)["\']/', $image, $src);

			$url = $src[1];

			if (stristr($url, 'https:') === false && stristr($url, 'http:') === false) {

				if (stristr($url, '//') === false) {

					$url = rtrim(JURI::root(), '/') . '/' . ltrim($url);
				} else {
					$uri = JURI::getInstance();

					$scheme = $uri->toString(array('scheme'));

					$scheme = str_replace('://', ':', $scheme);

					$url = $scheme . $url;
				}
			}

			// we need to supress the warning here in case allow_url_fopen disabled on the site. #865
			$imageData = @getimagesize($url);

			// If height is missing, we try to get the original image size config
			if (!$imageData || empty($imageData[1])) {

				$config = EB::config();
				$maxWidth	= $config->get( 'main_image_thumbnail_width' );
				$maxHeight	= $config->get( 'main_image_thumbnail_height' );

				if ($maxWidth) {
					$imageData = array($maxWidth . 'px', $maxHeight . 'px');
				} else {
					// If dimension still missing, we skip this image
					$content = str_ireplace($image, '', $content);
					continue;
				}
			}

			$coverInfo = 'width="' . $imageData[0] . '" height="' . $imageData[1] . '"';

			$ampImage = '<amp-img src="' . $url . '" ' . $coverInfo .  ' layout="responsive" ></amp-img>';

			ob_start();
			echo '<!-- START -->';
			echo $ampImage;
			echo '<!-- END -->';
			$output = ob_get_contents();
			ob_end_clean();

			//For legacy gallery, it always be wrap in <p>. We need to take it out.
			$output = str_replace('<!-- START -->', '<p>', $output);
			$output = str_replace('<!-- END -->', '<p>', $output);

			$content = str_ireplace($image, $output, $content);
		}

		return $content;
	}

	/**
	 * Process image to be used in Instant Articles
	 *
	 * @since	5.1
	 * @access	public
	 */
	public function processInstantImages($content)
	{
		$pattern = '/<img[^>]*>/';

		preg_match_all($pattern, $content, $matches);

		if (!$matches) {
			return array();
		}

		foreach ($matches[0] as $image) {

			preg_match('/src="([^"]+)"/', $image, $src);

			$url = $src[1];

			if (stristr($url, 'https:') === false && stristr($url, 'http:') === false) {

				if (stristr($url, '//') === false) {

					$url = rtrim(JURI::root(), '/') . '/' . ltrim($url);
				} else {
					$uri = JURI::getInstance();

					$scheme = $uri->toString(array('scheme'));

					$scheme = str_replace('://', ':', $scheme);

					$url = $scheme . $url;
				}
			}

			$imageData = getimagesize($url);

			// If height is missing, we try to get the original image size config
			if (!$imageData || empty($imageData[1])) {

				$maxWidth = $this->config->get('main_image_thumbnail_width');
				$maxHeight = $this->config->get('main_image_thumbnail_height');

				if ($maxWidth) {
					$imageData = array($maxWidth . 'px', $maxHeight . 'px');
				} else {
					// If dimension still missing, we skip this image
					$content = str_ireplace($image, '', $content);
					continue;
				}
			}

			$coverInfo = 'width="' . $imageData[0] . '" height="' . $imageData[1] . '"';

			$figure = '<figure><img src="' . $url . '" ' . $coverInfo .  '/></figure>';

			ob_start();
			echo '<!-- START -->';
			echo $figure;
			echo '<!-- END -->';
			$output = ob_get_contents();
			ob_end_clean();

			//For legacy gallery, it always be wrap in <p>. We need to take it out.
			$output = str_replace('<!-- START -->', '</p>', $output);
			$output = str_replace('<!-- END -->', '<p>', $output);

			$content = str_ireplace($image, $output, $content);
		}

		return $content;
	}
}
