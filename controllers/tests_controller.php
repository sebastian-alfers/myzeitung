<?php
class TestsController extends AppController {


	var $name = 'Tests';
	
	var $uses = array();

	function index() {
		echo "test";
        die();
	}

}
?>