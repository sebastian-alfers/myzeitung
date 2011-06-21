<?php
class SearchController extends AppController {


	var $uses = array ('Solr', 'Subscription', 'User', 'Post');
	
	var $helpers = array('Time', 'Image');

	public function beforeFilter(){
		parent::beforeFilter();
		//declaration which actions can be accessed without being logged in
		$this->Auth->allow('index','users', 'papers','posts', 'ajxSearch', 'delete');//!!!!!!!!!!!!!!!!!!!!!!!!!REMOVE delte""""""""!!!!!!!!!!!!!!!!!!!!!

	}


	function index() {
		$this->getResults();		
	}
	
	function users() {
		$this->getResults(solr::TYPE_USER);		
		$this->render('index');
	}
	
	function papers() {
		$this->getResults(solr::TYPE_PAPER);
		$this->render('index');		
	}
	
	function posts() {
		$this->getResults(solr::TYPE_POST);	
		$this->render('index');	
	}
	
	private function getResults($type = null){
		$query = isset($_REQUEST['q']) ? $_REQUEST['q'] : false;
		$results = false;
		if ($query)
		{
			$this->set('query', $query);
			//********************************************
			if(isset($type) and in_array($type, array(solr::TYPE_PAPER, solr::TYPE_POST, solr::TYPE_USER))){
				$this->Solr->setSearchFields(array('type' => $type));
			}
			//********************************************			
			$results = $this->Solr->query($query, Solr::DEFAULT_LIMIT, false);
			
			//feeding results with addtional info (as you can see below)																				
			foreach($results['results'] as &$result){
				
				if($result instanceof Apache_Solr_Document){
					
					if($result->type == 'paper'){
					//if a result is a paper, and the user DOES NOT OWN it, there are two options: user has subscribed the paper (unsubscribe button) 
					//																				user has not subscribed the paper (subscribe button) 
					$result->paper_subscribed = false;
					//check for subscriptions - if yes -> subscribed = true
						if($this->Auth->user('id')){
							$this->Subscription->contain();
							if(($this->Subscription->find('count', array('conditions' => array('Subscription.user_id' => $this->Auth->user('id'),'Subscription.paper_id' => $result->id)))) > 0){
								$result->paper_subscribed = true;
							}
						} 						
					}
					//reading if the user allows messages
					if($result->type == 'user'){
						$result->user_allow_messages = false;
						$this->User->contain();
						$this->data = $this->User->read('allow_messages', $result->id);
						$result->user_allow_messages = $this->data['User']['allow_messages'];
					}
					//reading post counters
					if($result->type == 'post'){
						debug('post');
						$result->post_comment_count = 0;
						$result->post_posts_user_count = 0;
						$result->post_view_count = 0;
						$result->post_reposters = array();
						$this->Post->contain();
						debug($result);
						$this->data = $this->Post->read(array('reposters', 'comment_count', 'posts_user_count', 'view_count'), $result->id);
						debug($this->data);
						$result->post_comment_count = $this->data['Post']['comment_count'];
						$result->post_posts_user_count = $this->data['Post']['posts_user_count'];
						$result->post_view_count = $this->data['Post']['view_count'];
						$result->post_reposters = $this->data['Post']['reposters'];
					}
				}
			}
		
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