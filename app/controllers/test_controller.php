<?php
class TestController extends AppController {

	var $uses = array();
	
	var $helpers = array('Javascript');
	
	public function beforeFilter(){
		parent::beforeFilter();
		//declaration which actions can be accessed without being logged in
		$this->Auth->allow('index', 'invite', 'ajax');

	}


	function index() {
		
		$this->set('test', array('test me' => 'jojo'));
		
		
		
		
	}

    function invite(){

        if(!empty($this->data)){
            debug($this->data);
        }

    }

    function ajax(){

    }

}
?>