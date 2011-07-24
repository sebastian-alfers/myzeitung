<?php
class AdminController extends AppController {

    var $name = 'Admin';
    var $uses = array();

	public function beforeFilter(){
		parent::beforeFilter();
	}

    function admin_index(){
        $this->log(phpversion());
    }
}

?>