<?php


class LandingController extends AppController {

    var $uses = array();

    public function beforeFilter(){
		parent::beforeFilter();
		//declaration which actions can be accessed without being logged in
		$this->Auth->allow('*');
    }

    function bundestag(){

    }

    function tierUmwelt(){

    }


}


?>