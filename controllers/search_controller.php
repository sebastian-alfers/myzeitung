<?php
class SearchController extends AppController {


	var $uses = array ('Solr');

	public function beforeFilter(){
		parent::beforeFilter();
		//declaration which actions can be accessed without being logged in
		$this->Auth->allow('index');
	}


	function index() {
		$limit = 10;
		$query = isset($_REQUEST['q']) ? $_REQUEST['q'] : false;
		$results = false;

		if ($query)
		{
			$this->set('query', $query);
			$results = $this->Solr->query($query);
			$this->set('results', $results);
		}
		else{
			$this->set('query', '');
		}
		
	}

}
?>