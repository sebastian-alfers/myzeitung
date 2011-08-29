<?php

class AwsController extends AppController {

	var $name = 'Aws';

    var $components = array('Aws');

    var $uses = array();

	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('admin_describeImages');

	}

	function admin_describeImages() {

        $instances = $this->Aws->describeInstances();

        $this->set('instances', $instances);

        //$instances = $response->body->instancesSet;



	}


}
