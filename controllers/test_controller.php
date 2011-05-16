<?php
class TestController extends AppController {

	var $uses = array ('Solr');

	var $components = array('Acl');

	public function beforeFilter(){
		parent::beforeFilter();
		//declaration which actions can be accessed without being logged in
		$this->Auth->allow('index');

	}


	function index() {

		$aro =& $this->Acl->Aro;

		//Here's all of our group info in an array we can iterate through
		$groups = array(
		0 => array(
			'alias' => 'superadmin'
			),
			1 => array(
			'alias' => 'admin'
			),
			2 => array(
			'alias' => 'user'
			),
			3 => array(
			'alias' => 'guest'
			),
		);

		//Iterate and create ARO groups
		foreach($groups as $data)
		{
			//Remember to call create() when saving in loops...
			$aro->create();

			//Save data
			$aro->save($data);
		}

		//Other action logic goes here...

	}

}
?>