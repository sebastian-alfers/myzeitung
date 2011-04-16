<?php
class ImageHelper extends Helper {

	var $helpers = array('Html');
	var $themeWeb = '';
	var $cacheDir = ''; // relative to IMAGES_URL path

	const DEFAULT_IMG = 'default.jpg';

	/**
	 * Automatically resizes an image and returns formatted IMG tag
	 *
	 * @param string $path Path to the image file, relative to the webroot/img/ directory.
	 * @param integer $width Image of returned image
	 * @param integer $height Height of returned image
	 * @param boolean $aspect Maintain aspect ratio (default: true)
	 * @param array    $htmlAttributes Array of HTML attributes.
	 * @param boolean $return Wheter this method should return a value or output it. This overrides AUTO_OUTPUT.
	 * @return mixed    Either string or echos the value, depends on AUTO_OUTPUT and $return.
	 * @access public
	 */
	function resize($path, $width, $height, $aspect = true, $htmlAttributes = array(), $return = false) {

		$types = array(1 => "gif", "jpeg", "png", "swf", "psd", "wbmp"); // used to determine image type

		///Applications/MAMP/htdocs/myzeitung/webroot/img/
		$img_path = ROOT.DS.APP_DIR.DS.WEBROOT_DIR.DS.$this->themeWeb.IMAGES_URL;

		///Applications/MAMP/htdocs/myzeitung/webroot/img/post/38/bildschirmfoto_2011-02-17_um_13.06.46.png
		$orig_img_path = $img_path.$path;

		
		///Applications/MAMP/htdocs/myzeitung/webroot/img/cache/
		$cachepath = ROOT.DS.APP_DIR.DS.WEBROOT_DIR.DS.$this->themeWeb.IMAGES_URL.'cache'.DS;

		//new dirs
		//$model = strtolower($model);
		//$first = strtolower(substr($path,0,1));
		//$second = strtolower(substr($path,1,1));
		//$customPath = $model.DS.$first.DS.$second;

		///Applications/MAMP/htdocs/myzeitung/webroot/img//p/o/

		$size = getimagesize($orig_img_path);
		$fullpath = $this->_getCachePathFromImagePath($path, $size, $width, $height, $aspect);

//		if(!is_dir($fullpath['path'])){
//			debug($fullpath['path']);die();
//			if (!mkdir($fullpath['path'], 0700, true)) {
//				die('Erstellung der Verzeichnisse schlug fehl...');
//			}
//		}

		///Applications/MAMP/htdocs/myzeitung/webroot/img/post/38/90/50/bildschirmfoto_2011-02-17_um_13.06.46.png
		$url = $img_path.$fullpath['path'].$fullpath['file'];

		if (!file_exists($orig_img_path) || !($size)){

			//default img not there;
			debug('can not load ' . $orig_img_path);
			return 'default.jpg';
		}


		if ($aspect) { // adjust to aspect.
			if (($size[1]/$height) > ($size[0]/$width))  // $size[0]:width, [1]:height, [2]:type
			$width = ceil(($size[0]/$size[1]) * $height);
			else
			$height = ceil($width / ($size[0]/$size[1]));
		}


		//cache/post/38/90/50/bildschirmfoto_2011-02-17_um_13.06.46.png
		$relfile = 'cache'.DS.$fullpath['path'].$fullpath['file'];

		///Applications/MAMP/htdocs/myzeitung/webroot/img/cache/post/38/90/50/
		$cacheFoler = $cachepath.$fullpath['path'];

		///Applications/MAMP/htdocs/myzeitung/webroot/img/cache/cache/post/38/90/50/bildschirmfoto_2011-02-17_um_13.06.46.png
		$cachefile = $cachepath . $relfile;
		
		
		if(!is_dir($cacheFoler)){
			if (!mkdir($cacheFoler, 0700, true)) {
				die('Erstellung der Verzeichnisse schlug fehl...');
			}
		}

		if (file_exists($cachefile)) {
			$csize = getimagesize($cachefile);
			$cached = ($csize[0] == $width && $csize[1] == $height); // image is cached
			if (@filemtime($cachefile) < @filemtime($url)) // check if up to date
			$cached = false;
		} else {
			$cached = false;
		}

		if (!$cached) {
			$resize = ($size[0] > $width || $size[1] > $height) || ($size[0] < $width || $size[1] < $height);
		} else {
			$resize = false;
		}

		if ($resize) {

			$image = call_user_func('imagecreatefrom'.$types[$size[2]], $orig_img_path);
			if (function_exists("imagecreatetruecolor") && ($temp = imagecreatetruecolor ($width, $height))) {
				imagecopyresampled ($temp, $image, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
			} else {
				$temp = imagecreate ($width, $height);
				imagecopyresized ($temp, $image, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
			}
			
			$cachefile = $cacheFoler.$fullpath['file'];

			call_user_func("image".$types[$size[2]], $temp, $cachefile);
			imagedestroy ($image);
			imagedestroy ($temp);
		}
		return $relfile;
		//return $this->output(sprintf($this->Html->tags['image'], $relfile, $htmlAttributes), $return);
	}

	/**
	 * get the path from image in image folder
	 * and transforms it to a path in cache folder with resize values
	 * Enter description here ...
	 * @param String $path
	 * @param boolean $aspect - keep aspect ratio or not
	 * @param array $size - dimensions of image
	 * @param int $width - width of requested img
	 * @param int $height - width of requested img
	 *
	 * @return array - path, name
	 */
	private function _getCachePathFromImagePath($path, $size, $width, $height, $aspect){

		$ret = array();
		//calculate aspect
		if ($aspect) { // adjust to aspect.
			if (($size[1]/$height) > ($size[0]/$width))  // $size[0]:width, [1]:height, [2]:type
			$width = ceil(($size[0]/$size[1]) * $height);
			else
			$height = ceil($width / ($size[0]/$size[1]));
		}

		$parts = explode('/', $path);

		$file_name = $parts[count($parts)-1];
		$ret['file'] = $file_name;
		$file_name = $width.DS.$height.DS;
		$parts[count($parts)-1] = $file_name;

		$cache_path = implode($parts, '/');

		$ret['path'] = $cache_path;

		return $ret;
	}
}
?>