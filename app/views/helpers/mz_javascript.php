<?php
App::import('Helper', 'Javascript');


class MzJavascriptHelper extends JavascriptHelper {

    function link($url){

        $disable_combine = true;

        if($disable_combine){
            echo parent::link($url, $disable_combine);
        }
        else{
            parent::link($url, $disable_combine);
        }

        return;
    }


}