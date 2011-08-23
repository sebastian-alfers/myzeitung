<?php
App::import('Helper', 'Html');


class MzHtmlHelper extends HtmlHelper {

    function css($path){

        $combine = true;

        echo parent::css($path, $combine, array('inline' => $combine));

    }

}