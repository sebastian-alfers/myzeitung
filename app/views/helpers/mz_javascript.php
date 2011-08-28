<?php
App::import('Helper', 'Javascript');


class MzJavascriptHelper extends JavascriptHelper {

    function link($url, $combine = true){

        $disable_combine = (boolean)Configure::read('Frontend.disable_combine_js');

        if(!$combine || $disable_combine){
            echo parent::link($url, true);
        }
        else{
            parent::link($url, $disable_combine);
        }

        return;
    }


}