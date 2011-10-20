<?php

class MzslidesController extends AppController {

	var $name = 'Mzslides';

	var $uses = array();

	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('main');
	}


	function main(){
		$this->layout = 'html5-slides';
	}


}
