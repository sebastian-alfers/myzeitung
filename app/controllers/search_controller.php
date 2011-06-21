<?php
class SearchController extends AppController {


	var $uses = array ('Solr');
	
	var $helpers = array('Time', 'Image');

	public function beforeFilter(){
		parent::beforeFilter();
		//declaration which actions can be accessed without being logged in
		$this->Auth->allow('index', 'ajxSearch', 'delete');//!!!!!!!!!!!!!!!!!!!!!!!!!REMOVE delte""""""""!!!!!!!!!!!!!!!!!!!!!

	}


	function index() {
		$limit = 20;
		$query = isset($_REQUEST['q']) ? $_REQUEST['q'] : false;
		$results = false;

		if ($query)
		{
			$this->set('query', $query);
			//********************************************
			$this->Solr->setSearchFields(array('type' => 'user'));
			//********************************************			
			$results = $this->Solr->query($query, Solr::DEFAULT_LIMIT, false);
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
					$split = array();//seperate results by type
						
					if($results && !empty($results)){
							
						foreach ($results['results'] as $type => $docs){
							//$split[$type][] = 
						}
					}
						
					$this->set('results', $results);
				}
				else{

				}
			}


			//(title:Pirates AND title:of AND (title:th* OR title:th)) OR title:"pirates of th"

		}

		$this->render('autoComplete', 'ajax');//custom ctp, ajax for blank layout
	}

	function delete(){

		$this->Solr->deleteIndex();

	}

}
?>