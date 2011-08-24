<?php
App::import('Helper', 'Html');


class MzHtmlHelper extends HtmlHelper {

    function css($path){

        $disable_combine = true;

        echo parent::css($path, $disable_combine, array('inline' => $disable_combine));

    }

}