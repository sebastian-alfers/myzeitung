<?php
App::import('Helper', 'Javascript');


class MzJavascriptHelper extends JavascriptHelper {

	var $helpers = array('Cf');

    function link($url, $combine = true){



        $disable_combine = (boolean)Configure::read('Frontend.disable_combine_js');
        $use_cdn = (boolean)Configure::read('Cdn.enable');
        
		if($disable_combine && $use_cdn){
			echo $this->Cf->script('/js/'.$url);

		}
		else{
            if(!$combine || $disable_combine){
                echo parent::link($url, true);
            }
            else{
                parent::link($url, $disable_combine);
            }
        }

        return;
    }


}