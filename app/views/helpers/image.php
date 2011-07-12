<?php
class ImageHelper extends Helper {

	var $helpers = array('Html', 'Cf');
	var $themeWeb = '';
	var $cacheDir = ''; // relative to IMAGES_URL path

	const DEFAULT_IMG = 'assets/default.jpg';
    const USER = 'user';
    const PAPER = 'paper';

	/**
	 * Automatically resizes an image and returns formatted IMG tag
	 *
	 * @param string $path Path to the image file, relative to the webroot/img/ directory.
	 * @param integer $width Image of returned image
	 * @param integer $height Height of returned image
	 * @param array $orig_size - result of method getimagesize - saved in db
	 * @param array $htmlAttributes Array of HTML attributes.
	 * @param boolean $relativeMove adds inline css position:relative and set top minus half of max height (e.g. for post navigator)
	 *
	 * @return string / array - path within cache folder, if $relativeMove == true -> add additional inline styles
	 */
	function resize($path, $width, $height, $orig_size = null, $relativeMove = false) {


		$planned_height = $height;
		$planned_with = $width;

		$types = array(1 => "gif", "jpeg", "png", "swf", "psd", "wbmp"); // used to determine image type

		///Applications/MAMP/htdocs/myzeitung/webroot/img/
		$img_path = ROOT.DS.APP_DIR.DS.WEBROOT_DIR.DS.$this->themeWeb.IMAGES_URL;

		///Applications/MAMP/htdocs/myzeitung/webroot/img/post/38/bildschirmfoto_2011-02-17_um_13.06.46.png
		$orig_img_path = $img_path.$path;

		if($orig_size == null){
			$orig_size = getimagesize($orig_img_path);
		}

		if($height != null && $width != null){
			$planned_aspect_ratio = $width / $height;

			//both values are set -> check if landscape or portrait
			//if portrait:  calculate new height
			if($this->_isLandscape($orig_size, $planned_aspect_ratio)){
				$width = null;
			}
			else{
				$height = null;

			}
		}
			

		if($width == null){
			//calculate width
			$orig_width = $orig_size[0];
			$orig_height = $orig_size[1];
			$factor = $height / $orig_height;
			$width = ceil($orig_width * $factor);
		}
		if($height == null){
			//calculate height
			$orig_width = $orig_size[0];
			$orig_height = $orig_size[1];
			$factor = $width / $orig_width;
			$height = ceil($orig_height * $factor);
		}


		///Applications/MAMP/htdocs/myzeitung/webroot/img/cache/
		$cachepath = ROOT.DS.APP_DIR.DS.WEBROOT_DIR.DS.$this->themeWeb.IMAGES_URL.'cache'.DS;

		//new dirs
		//$model = strtolower($model);
		//$first = strtolower(substr($path,0,1));
		//$second = strtolower(substr($path,1,1));
		//$customPath = $model.DS.$first.DS.$second;

		///Applications/MAMP/htdocs/myzeitung/webroot/img//p/o/

		$size = getimagesize($orig_img_path);
		$fullpath = $this->_getCachePathFromImagePath($path, $size, $width, $height, true);

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
			return self::DEFAULT_IMG;
		}


		if (true) { // adjust to aspect.
			if (($size[1]/$height) > ($size[0]/$width))  // $size[0]:width, [1]:height, [2]:type
			$width = ceil(($size[0]/$size[1]) * $height);
			else
			$height = ceil($width / ($size[0]/$size[1]));
		}


		//cache/post/38/90/50/bildschirmfoto_2011-02-17_um_13.06.46.png
		$relfile = $fullpath['path'].$fullpath['file'];

		///Applications/MAMP/htdocs/myzeitung/webroot/img/cache/post/38/90/50/
		$cacheFoler = $cachepath.$fullpath['path'];

		///Applications/MAMP/htdocs/myzeitung/webroot/img/cache/cache/post/38/90/50/bildschirmfoto_2011-02-17_um_13.06.46.png
		$cachefile = $cachepath . $relfile;


		if(!is_dir($cacheFoler)){
			if (!mkdir($cacheFoler, 0755, true)) {
				die('Erstellung der Verzeichnisse schlug fehl...');
			}
		}
		//debug ('jo cache ' . $cachefile);
		//debug ('jo cache ' . $cachepath.$fullpath['path'].$fullpath['file']);die();

		if (file_exists($cachefile)) {


			$csize = getimagesize($cachefile);
			$cached = ($csize[0] == $width && $csize[1] == $height); // image is cached
			if (@filemtime($cachefile) < @filemtime($url)) // check if up to date
			$cached = false;
		} else {
			$cached = false;
		}

		if (!$cached) {
			$resize = ($size[0] >= $width || $size[1] > $height) || ($size[0] <= $width || $size[1] < $height);

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

		$relfile = 'cache'.DS.$relfile;

		if(!$relativeMove) return $relfile;

		//add additional infos for inline css -> post navigator
		$return = array();

		$return['path'] = $relfile;
		$return['height'] = $height;
		$return['width'] = $width;

		$inline = '';
		if($planned_height != null){
			if($height > $planned_height){
				$inline	.= 'margin-top:-'.(($height-$planned_height)/2).'px;';
			}
			else{
				$inline	.= 'margin-top:'.(($planned_height-$height)/2).'px;';
			}
		}

		if($planned_with != null){
			if($width > $planned_with){
				$inline	.= 'margin-left:-'.(($width-$planned_with)/2).'px;';
			}
			else{
				$inline	.= 'margin-left:'.(($planned_with-$width)/2).'px;';
			}
		}


		$return['inline'] = $inline;

		return $return;
		//return $this->output(sprintf($this->Html->tags['image'], $relfile, $htmlAttributes), $return);
	}

	/**
	 * get the path from image in image folder
	 * and transforms it to a path in cache folder with resize values
	 * Enter description here ...
	 * @param String $path

	 * @param array $size - dimensions of image
	 * @param int $width - width of requested img
	 * @param int $height - width of requested img
	 *
	 * @return array - path, name
	 */
	private function _getCachePathFromImagePath($path, $size, $width, $height){

		$ret = array();
		//calculate aspect
		if (true) { // adjust to aspect.
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

	/**
	 * the preferred dimensions are ($width x $height) e.g. 300x150
	 * 1) calculate preferred aspect ratio
	 *
	 * @param unknown_type $orig_size
	 * @param unknown_type $height
	 * @param unknown_type $width
	 */
	private function _isLandscape($orig_size, $planned_aspect_ratio){
		$img_width = $orig_size[0];
		$img_height = $orig_size[1];
		$imageAspectRatio =  $img_width/$img_height;
		return $imageAspectRatio > $planned_aspect_ratio;
	}


	/**
	 * gets the value of a image from db
	 *
	 */
	function getImgPath($img, $model){

		if($img == ''){
            if($model == self::USER)
			    return User::DEFAULT_USER_IMAGE;

            if($model == self::PAPER)
                return Paper::DEFAULT_PAPER_IMAGE;
		}

		if(!is_array($img)){
            $data = unserialize($img);
        }
        else{
            $data[] = $img;
        }

		if(is_array($data[0])) return $data[0];
        if(is_array($data)) return $data;
		return User::DEFAULT_USER_IMAGE;
	}

	/**
	 *
	 * Enter description here ...
	 * @param array $data - data of e.g. user - path/file_name/size
	 * @param int $width
	 * @param int $height
	 * @param array $img_extra - additional image data like alt-tag for image
	 * @param array $container_data - array widh: -> key for controller data; -> key for additional data
	 */
	public function render($data, $width, $height, $img_extra = array(), $container_data = null, $model = self::USER){
            
		$img_data = $this->getImgPath($data['image'], $model);


		if(is_array($img_data)){

			//debug($img_data);die();
			//found img in db
            if(!isset($img_data['path'])){
                $img_data = $img_data[0];
            }
			$info = $this->resize($img_data['path'], $width, $height, $img_data['size'], true);

			if(is_array($img_extra) && !isset($img_extra['style'])){
				$img_extra['style'] = $info['inline'];
			}
			$img = $this->Cf->image($info['path'], $img_extra);

			//echo $this->Html->link($img, , array('class' => "user-image", 'escape' => false, 'style' => 'overflow:hidden;height:'.$height.'px;width:'.$width.'px;'));
		}
		else{
			$path = $this->resize($img_data, $width, $height, null, false);
			$img = $this->Html->image($path, $img_extra);
		}

		if($container_data != null &&
		is_array($container_data) &&
		isset($container_data['url'])){
				
			//also make link out of it
			$additional_img_data = array();
			if(isset($container_data['additional'])){
				$additional_img_data = $container_data['additional'];
			}
			if(!isset($additional_img_data['style'])){
				$additional_img_data['style'] = 'display:block;overflow:hidden;height:'.$height.'px;width:'.$width.'px;';
			}
			if(!isset($additional_img_data['escape'])){
				$additional_img_data['escape'] = false;
			}
				
			return $this->Html->link($img, $container_data['url'], $additional_img_data);
		}
        elseif(isset($container_data['tag'])){
			//also make link out of it
			$additional_img_data = array();
			if(isset($container_data['additional'])){
				$additional_img_data = $container_data['additional'];
			}
			if(!isset($additional_img_data['style'])){
				$additional_img_data['style'] = 'overflow:hidden;height:'.$height.'px;width:'.$width.'px;';
			}
            if(isset($additional_img_data['float'])){
                $additional_img_data['style'] .= 'float:'.$additional_img_data['float'];
            }
            if(isset($additional_img_data['margin-right'])){
                $additional_img_data['style'] .= ';margin-right:'.$additional_img_data['margin-right'];
            }
			if(!isset($additional_img_data['escape'])){
				$additional_img_data['escape'] = false;
			}

            //sourround img with container
            $t = $container_data['tag'];
            return '<'.$t.' style="'.$additional_img_data['style'].'">'.$img.'</'.$t.'>';
        }
		else{
			return $img;
		}

	}

}
?>