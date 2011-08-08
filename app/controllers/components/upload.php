<?php

/**
 * provides different helper methods for uploadging
 *
 * @author sebastianalfers
 *
 */
class UploadComponent extends Object {

	var $components = array('Session');

	public function transformFileName($file_name){
		if($file_name == ''){
			return '';
		}

		$file_name = preg_replace('/^\W+|\W+$/', '', $file_name); // remove all non-alphanumeric chars at begin & end of string
		$file_name = preg_replace('/\s+/', '_', $file_name); // compress internal whitespace and replace with _
		$file_name = strtolower(preg_replace('/\W-/', '', $file_name)); // remove all non-alphanumeric chars except _ and -
        $pattern = array("','", "';'", "'#'", );
        $file_name = preg_replace($pattern, '', $file_name);


		return $file_name;
	}

	/**
	 * checks, if the hash folder has images
	 *
	 */
	public function hasImagesInHashFolder($hash){

		$webroot = ROOT.DS.APP_DIR.DS.WEBROOT_DIR.DS; //until webroot/
		$path_to_tmp_folder = $webroot.'img'.DS.'tmp'.DS.$hash.DS;

		return is_dir($path_to_tmp_folder);
			
	}

	/**
	 * string for tmp folderc ect. for uploadimg
	 * stuff
	 *
	 */
	public function getHash(){
		return md5(microtime().$this->Session->read('sessID'));
	}

	/**
     * @param  $hash - name of folder in img/tmp
     * @param null $id - to hash the path
     * @param null $timestamp - added to the path
     * @param  $images - string, with comma-seperated links
     * @param  $folder - e.g. post or paper, like namespace
     * @return array|bool
     */
	public function copyImagesFromHash($hash, $id = null, $timestamp = null, $images, $folder){

		$new_images = array();
		
		$webroot = $this->getWebrootUrl();
		$path_to_tmp_folder = $webroot.$this->getPathToTmpHashFolder($hash);

		if(is_dir($path_to_tmp_folder)){

			if($timestamp !== false && $timestamp == null){
				$year = date('Y');
				$month = date('m');
			}
			else{
				$year = date('Y', strtotime($timestamp));
				$month = date('m', strtotime($timestamp));
			}
				
			//for new posts, use current timesempt, for edit posts user the posts creation date for path
			
			//hash the id for path
			$hash_id = md5($id);
			
			$new_rel_path = $year.DS.$month.DS.$hash_id.DS;// starting from webroot/img/* folder
			$post_img_folder = $webroot.'img'.DS.$folder.DS.$new_rel_path;
			//create folder for new post
			if(!is_dir($post_img_folder)){
				if (!mkdir($post_img_folder, 0755, true)) {
					$this->log('can not create directory for post: ' . $post_img_folder);
					return false;
				}
			}

			//found folder

			if ($handle = opendir($path_to_tmp_folder)) {
				
				$images= explode(",", $images);
					
				foreach($images as $file){

					$tmp_path = $path_to_tmp_folder.$file;  //root/path/to/hash/file.jpg
					$new_full_path = $post_img_folder.$file; //root/path/to/new/file.jpg

					if(!is_dir($tmp_path) && file_exists($tmp_path)){
						$size = getimagesize($tmp_path);

                        if(file_exists($new_full_path)){
                            $parts = explode("/", $new_full_path);
                            $count = count($parts);

                            $parts[$count-1] = uniqid().'_' . $parts[$count -1];
                            $new_full_path = '';
                            foreach($parts as $index => $part){
                                $new_full_path.=$part;
                                if($index+1 < $count) $new_full_path.= DS;
                            }
                        }

						if (copy($tmp_path , $new_full_path)) {
							unlink($tmp_path);
							$new_images[] = array('path' => $folder.DS.$new_rel_path.$file, 'file_name' => basename($new_full_path), 'size' => $size);
						}
					}


				}
			}
			else{
				$this->log('can not open directory for copy images: ' . $path_to_tmp_folder);
				return false;
			}

		}
		else{
			$this->log('given path is no directory:' . $path_to_tmp_folder);
			return false;
		}
		return $new_images;

	}

	/**
	 * return path to webroot folder
	 * @return string
	 */
	public function getWebrootUrl(){
		return  ROOT.DS.APP_DIR.DS.WEBROOT_DIR.DS; //until webroot/
	}

	/**
	 * path
	 *
	 */
	public function getPathToTmpHashFolder($hash){

		return 'img'.DS.'tmp'.DS.$hash.DS;

			
	}

	/**
	 * removes the tmp folder
	 */
	public function removeTmpHashFolder($hash){

		$webroot = $this->getWebrootUrl();
		$path_to_tmp_folder = $webroot.$this->getPathToTmpHashFolder($hash);
		//remove files in folder


		if (is_dir($path_to_tmp_folder) && $handle = opendir($path_to_tmp_folder)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					$tmp_path = $path_to_tmp_folder.$file;  //root/path/to/hash/file.jpg
					unlink($tmp_path);
				}
			}
		}
		else{
			$this->log('can not open directory for remove images: ' . $path_to_tmp_folder);
			return false;
		}
			
	}
}
?>