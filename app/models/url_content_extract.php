<?php

class UrlContentExtract extends AppModel {

    var $_htmlData = '';

	var $useTable = false;

	var $url = '';

    /**
     * get images as a previe of a url for a video (e.g. youtube, vimeo ect.)
     *
     * @return array
     */
    function getVideoPreview($url){

        $html = new DOMDocument();
        $html->loadHtmlFile( 'http://www.youtube.com/watch?v=X5halJlIRQ0&feature=feedrec' );

		$meta_regex = '/<meta[^>]*'.'/Ui';
		preg_match_all($meta_regex, $this->_htmlData, $meta, PREG_PATTERN_ORDER);
		print_r($meta);


    }
	
	/**
	 * gets a valid url and returns content
	 *
	 * @param unknown_type $url


    function getContent($url){
		$this->url = $this->_checkValues($url);

		$html_site = $this->_fetchRecord($url);


		//$title = $this->_fetchTitle($html_site);

		$images = array();
		$images = $this->_getImages($html_site);

		$images_content = $this->_loadImages($images);
		
		$this->log('**********');
		$this->log($images_content);

		return $images_content;
	}
     * */

	/**
	 * gets an array with relativ image urls
	 *
	 * @param array $images
	 *
	 * @return String
	 *
	 */
	private function _loadImages($images){

		$str = '';
		
		$k=1;
		for ($i=0;$i<=sizeof($images);$i++)
		{
			if(@$images[$i])
			{
				@$images[$i] = $this->url.DS.@$images[$i];
				//if(@getimagesize(@$images[$i]))
				//{
					//list($width, $height, $type, $attr) = getimagesize(@$images[$i]);
					//if($width >= 50 && $height >= 50 ){

						if($k >= 5) return $str;
						
						$str .= "<img src='".@$images[$i]."' width='100' id='".$k."' >";

						$k++;

					//}
				//}
			}
		}
		
		return $str;
	}

	/**
	 * returns all images
	 *
	 * @param String $html_site
	 *
	 * @return array
	 */
	private function _getImages($html_site){
		$image_regex = '/<img[^>]*'.'src=[\"|\'](.*)[\"|\']/Ui';
		preg_match_all($image_regex, $html_site, $img, PREG_PATTERN_ORDER);
		return $img[1];
	}

	/**
	 * get <title /> tag from source
	 *
	 * @param String $html_site
	 */
	private function _fetchTitle($html_site){
		$title_regex = "/<title>(.+)<\/title>/i";
		preg_match_all($title_regex, $html_site, $title, PREG_PATTERN_ORDER);
		//return $title[1][0];
	}

	private function _checkValues($value)
	{
		$value = trim($value);
		if (get_magic_quotes_gpc())
		{
			$value = stripslashes($value);
		}
		$value = strtr($value, array_flip(get_html_translation_table(HTML_ENTITIES)));
		$value = strip_tags($value);
		$value = htmlspecialchars($value);
		return $value;
	}

	private function _fetchRecord($path)
	{
		$file = fopen($path, "r");
		if (!$file)
		{
			exit("Problem occured");
		}
		$data = '';
		while (!feof($file))
		{
			$data .= fgets($file, 1024);
		}
		$this->_htmlData = $data;
	}

}
?>