<?php
App::import('Helper', 'Html');


class MzHtmlHelper extends HtmlHelper {

    function css($path){

        $combine = false;

        echo parent::css($path, $combine, array('inline' => $combine));

    }

}