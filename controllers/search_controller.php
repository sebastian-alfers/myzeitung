<?php
class SearchController extends AppController {


	var $uses = array ('Solr');

	public function beforeFilter(){
		parent::beforeFilter();
		//declaration which actions can be accessed without being logged in
		$this->Auth->allow('index', 'ajxSearch');

	}


	function index() {
		$limit = 10;
		$query = isset($_REQUEST['q']) ? $_REQUEST['q'] : false;
		$results = false;

		if ($query)
		{
			$this->set('query', $query);
			$results = $this->Solr->query("(so* OR so)");
			$this->set('results', $results);
		}
		else{
			$this->set('query', '');
		}

	}

	function ajxSearch(){

		$query = $_POST['query'];
		$results = array();
		$search_string = '';
		if(!empty($query)){

			$query = explode(" ", $query);

			$length = count($query);

			if($length == 0){
				$this->set('query', '');
			}
			else if($length == 1){
				$search_string = "(".$query[0]."* OR ".$query[0].")";
				
			}
			else{
				$search_string = '(';
				for($i = 0; $i < $length-1; $i++){

					$search_string .= $query[$i] . " AND ";
				}
				$search_string .= "(".$query[$length-1]."* OR ".$query[$length-1].")) or \"".$_POST['query']."\"";
				
			}

			if($search_string != ''){

				if ($search_string)
				{
					$this->set('query', $search_string);
					$results = $this->Solr->query($search_string);
					$this->set('results', $results);
				}
				else{

				}
			}


			//(title:Pirates AND title:of AND (title:th* OR title:th)) OR title:"pirates of th"

		}

		$this->render('autoComplete', 'ajax');//custom ctp, ajax for blank layout
	}

}
?>