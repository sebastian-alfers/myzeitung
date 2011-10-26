<?php
App::import('Helper', 'Html');


class MzHtmlHelper extends HtmlHelper {

	var $helpers = array('Cf');

    function css($path){

        //important to cast
        $disable_combine = (boolean)Configure::read('Frontend.disable_combine_css');
        $use_cdn = (boolean)Configure::read('Cdn.enable');

		if($disable_combine && $use_cdn){
			echo $this->Cf->css('/css/'.$path);		
		}
		else{
	        echo parent::css($path, $disable_combine, array('inline' => $disable_combine));
		}
	

    }


}