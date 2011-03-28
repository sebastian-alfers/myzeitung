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
	 * @param string $model type of model / subfolder in img path
	 * @param array    $htmlAttributes Array of HTML attributes.
	 * @param boolean $return Wheter this method should return a value or output it. This overrides AUTO_OUTPUT.
	 * @return mixed    Either string or echos the value, depends on AUTO_OUTPUT and $return.
	 * @access public
	 */
	function resize($path, $width, $height, $aspect = true, $model = 'all', $htmlAttributes = array(), $return = false) {

		$types = array(1 => "gif", "jpeg", "png", "swf", "psd", "wbmp"); // used to determine image type

		$fullpath = ROOT.DS.APP_DIR.DS.WEBROOT_DIR.DS.$this->themeWeb.IMAGES_URL;
		$cachepath = ROOT.DS.APP_DIR.DS.WEBROOT_DIR.DS.$this->themeWeb.IMAGES_URL.'cache'.DS;

		//new dirs
		$model = strtolower($model);
		$first = strtolower(substr($path,0,1));
		$second = strtolower(substr($path,1,1));
		$customPath = $model.DS.$first.DS.$second;

		$fullpath = $fullpath.$customPath.DS;

		if(!is_dir($fullpath)){
				
			if (!mkdir($fullpath, 0700, true)) {
				die('Erstellung der Verzeichnisse schlug fehl...');
			}
		}


		$url = $fullpath.$path;

		if (!file_exists($url) || !($size = getimagesize($url))){
			if($path == self::DEFAULT_IMG){
				//default img not there;
				return 'default.jpg';
			}
			//img not there, but is in folder-structure -> resize with passed params
			return $this->resize(self::DEFAULT_IMG, $width, $height, $aspect, 'default', $htmlAttributes, $return);	
		}
		

		if ($aspect) { // adjust to aspect.
			if (($size[1]/$height) > ($size[0]/$width))  // $size[0]:width, [1]:height, [2]:type
			$width = ceil(($size[0]/$size[1]) * $height);
			else
			$height = ceil($width / ($size[0]/$size[1]));
		}



		$relfile = 'cache'.DS.$this->cacheDir.$customPath.'/'.$width.'x'.$height.'_'.basename($path); // relative file
		
		$cacheFoler = $cachepath.$customPath.DS;
		$cachefile = $cacheFoler.$width.'x'.$height.'_'.basename($path);  // location on server
		
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
				
			$image = call_user_func('imagecreatefrom'.$types[$size[2]], $url);
			if (function_exists("imagecreatetruecolor") && ($temp = imagecreatetruecolor ($width, $height))) {
				imagecopyresampled ($temp, $image, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
			} else {
				$temp = imagecreate ($width, $height);
				imagecopyresized ($temp, $image, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
			}
			call_user_func("image".$types[$size[2]], $temp, $cachefile);
			imagedestroy ($image);
			imagedestroy ($temp);
		}		
		return $relfile;
		//return $this->output(sprintf($this->Html->tags['image'], $relfile, $htmlAttributes), $return);
	}
}
?>