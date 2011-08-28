<?php
App::import('Helper', 'Html');


class MzHtmlHelper extends HtmlHelper {

    function css($path){

        //important to cast
        $disable_combine = (boolean)Configure::read('Frontend.disable_combine_css');

        echo parent::css($path, $disable_combine, array('inline' => $disable_combine));

    }

}