<?php
class SearchController extends AppController {


	var $uses = array ('Solr', 'Subscription', 'User', 'Post');
	
	var $helpers = array('MzText', 'MzTime', 'Image', 'Reposter');

	public function beforeFilter(){
		parent::beforeFilter();
		//declaration which actions can be accessed without being logged in
		$this->Auth->allow('index','users', 'papers','posts', 'ajxSearch', 'delete');//!!!!!!!!!!!!!!!!!!!!!!!!!REMOVE delte""""""""!!!!!!!!!!!!!!!!!!!!!

	}


	function index() {
		
		if(!isset($this->params['form']['start'])){
			$start = 0 ;
		}
		else{
			$start = $this->params['form']['start'];
		}
		
		$this->getResults(null, solr::QUERY_TYPE_SEARCH_RESULTS, $start);

		$this->set('start', $start);
		$this->set('per_page', solr::DEFAULT_LIMIT);		
		
		if($this->params['isAjax']){	
			$this->render('more_results', 'ajax');
		}
				
	}
	
	function users() {
		$this->getResults(solr::TYPE_USER , solr::QUERY_TYPE_SEARCH_RESULTS);		
		$this->render('index');
	}
	
	function papers() {
		$this->getResults(solr::TYPE_PAPER, solr::QUERY_TYPE_SEARCH_RESULTS);
		$this->render('index');		
	}
	
	function posts() {
		$this->getResults(solr::TYPE_POST, solr::QUERY_TYPE_SEARCH_RESULTS);	
		$this->render('index');	
	}
	
	
	function ajxSearch(){
		$this->getResults(null, solr::QUERY_TYPE_AUTO_SUGGEST);
		$this->render('autoComplete', 'ajax');//custom ctp, ajax for blank layout
	}
	
	
	/**
	 * getting and setting the results. calling sub functions in-between.
	 * @param unknown_type $type
	 * @param unknown_type $queryType
	 */
	
    
    function admin_index(){

    }
    function admin_refreshPostsIndex(){

        $this->Solr->refreshPostsIndex();
        $this->Session->setFlash(__('Posts search index has been refreshed', true));
        $this->redirect($this->referer());

    }
    function admin_refreshUsersIndex(){
        $this->Solr->refreshUsersIndex();
        $this->Session->setFlash(__('Users search index has been refreshed', true));
        $this->redirect($this->referer());

    }
    function admin_refreshPapersIndex(){
        $this->Solr->refreshPapersIndex();
        $this->Session->setFlash(__('Papers search index has been refreshed', true));
        $this->redirect($this->referer());

    }

	private function getResults($type = null, $queryType = solr::QUERY_TYPE_SEARCH_RESULTS, $start = 0){
		$params = array();
		if($queryType == solr::QUERY_TYPE_SEARCH_RESULTS){
			//search results
			
			$query = isset($this->params['url']['q']) ? $this->params['url']['q'] : false;
			//			$filter_query = isset($_REQUEST['fq']) ? $_REQUEST['fq'] : false;
			
			if(!$query)//for "more results"
				$query = isset($this->params['form']['q']) ? $this->params['form']['q'] : false;
			
			//debug($query);die();
				

			$grouped = false;
			$limit = solr::DEFAULT_LIMIT;
			
			//$params[''];
		} else {
			//auto suggest
			$query = $_POST['query'];
			$grouped = true;
			$limit = solr::SUGGEST_LIMIT;
		}
		if(isset($filter_query) && !empty($filter_query)){
			$params['fq'] = $filter_query;
		}
		//debug($filter_query);
		$results = false;
		if ($query)
		{
			$this->set('query', $query);
			//********************************************
			if(isset($type) and in_array($type, array(solr::TYPE_PAPER, solr::TYPE_POST, solr::TYPE_USER))){
				$this->Solr->setSearchFields(array('type' => $type));
			}
			//********************************************	
			$search_string = $this->enhanceQuery($query, $queryType);		


			$results = $this->Solr->query($search_string, $limit, $grouped, $start ,$params);

			if($results and $queryType == solr::QUERY_TYPE_SEARCH_RESULTS){
				$results = $this->addExtraInformation($results);
			}
			
			$this->set('rows', $results['rows']);
			$this->set('results', $results);
		}
		else{
			$this->set('query', '');
		}
	}

	
	
	
	
	/**
	 * modify the query sent to solr depending on which type of query is needed :   search suggest or the default search results.
	 * @param unknown_type $query - typed query
	 * @param unknown_type $queryType 
	 */
	private function enhanceQuery($query = null, $queryType = solr::QUERY_TYPE_SEARCH_RESULTS){
		$search_string = '';
		if($query != null) {
			$query = explode(" ", $query);
			$length = count($query);
			
			//enhance query string for search results  / example:
			// original query = "Donald Duck"
			// modified =  "(search_field:Donald OR search_field_phonetic:Donald OR search_field_ngrm:Donald) AND (search_field:Duck OR search_field_phonetic:Duck OR search_field_ngrm:Duck)"
			if($queryType == solr::QUERY_TYPE_SEARCH_RESULTS){
				if($length == 0){
					$this->set('query', '');
				}
				else{
					$search_string = '';
					for($i = 0; $i < $length; $i++){
						$search_string .= "(".solr::SEARCH_RESULT_SEARCH_FIELD.":".$query[0]." OR ".solr::SEARCH_RESULT_SEARCH_FIELD_PHONETIC.":".$query[0]." OR ".solr::SEARCH_RESULT_SEARCH_FIELD_NGRM.":".$query[0].")";
						if($i < $length-1){
							$search_string .= " AND ";
						}
					}
				}
				
	
			//enhance query string for auto suggest / example
			// original query = "Donald Duck"  (two or more words)
			// modified= "((search_field_auto_suggest:Donald OR search_field_phonetic:Donald OR search_field_ngrm:Donal) 
			//				AND (search_field_auto_suggest:Duck* OR (search_field_auto_suggest:Duck OR search_field_phonetic:Duck  OR search_field_ngrm:Duck)))
			//				 OR (search_field_auto_suggest:"Donald Duck" OR search_field_phonetic:"Donald Duck"  OR search_field_ngrm:"Donald Duck")"
			// original query = "Donald"  (one word)
			// modified= (search_field_auto_suggest:Donald* OR (search_field_auto_suggest:Donald OR search_field_phonetic:Donald  OR search_field_ngrm:Donald))
			} elseif($queryType == solr::QUERY_TYPE_AUTO_SUGGEST){
				if($length == 0){
					$this->set('query', '');
				}
				else if($length == 1){
					$search_string = "(".solr::SEARCH_RESULT_SEARCH_FIELD_AUTO_SUGGEST.":".$query[0]."* OR (".solr::SEARCH_RESULT_SEARCH_FIELD_AUTO_SUGGEST.":".$query[0]." OR ".solr::SEARCH_RESULT_SEARCH_FIELD_PHONETIC.":".$query[0]." OR ".solr::SEARCH_RESULT_SEARCH_FIELD_NGRM.":".$query[0]."))";
				}
				else{
					$search_string = '(';
					for($i = 0; $i < $length-1; $i++){
	
						$search_string .= "(".solr::SEARCH_RESULT_SEARCH_FIELD_AUTO_SUGGEST.":".$query[$i]." OR ".solr::SEARCH_RESULT_SEARCH_FIELD_PHONETIC.":".$query[$i]." OR ".solr::SEARCH_RESULT_SEARCH_FIELD_NGRM.":".$query[$i].")" . " AND ";
					}
						$search_string .= "(".solr::SEARCH_RESULT_SEARCH_FIELD_AUTO_SUGGEST.":".$query[$length-1]."* OR (".solr::SEARCH_RESULT_SEARCH_FIELD_AUTO_SUGGEST.":".$query[$length-1]." OR ".solr::SEARCH_RESULT_SEARCH_FIELD_PHONETIC.":".$query[$length-1]." OR ".solr::SEARCH_RESULT_SEARCH_FIELD_NGRM.":".$query[$length-1]."))) OR (".solr::SEARCH_RESULT_SEARCH_FIELD_AUTO_SUGGEST.":\'".$_POST['query']."\' OR ".solr::SEARCH_RESULT_SEARCH_FIELD_PHONETIC.":\'".$_POST['query']."\' OR ".solr::SEARCH_RESULT_SEARCH_FIELD_NGRM.":\'".$_POST['query']."\')";
				}
            }
		}
		return $search_string;
	}
	
	
	/**
	 * Adding some Information to the results that is not indexed in solr.
	 * @param unknown_type $results
	 */
	private function addExtraInformation($results = null){
		if($results){
				//feeding results with addtional info (as you can see below)																				
				foreach($results['results'] as &$result){
					
					if($result instanceof Apache_Solr_Document){
						
						if($result->type == 'paper'){

                            //if a result is a paper, and the user DOES NOT OWN this paper, there are two options: user has subscribed the paper (unsubscribe button)
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
						//reading post counters and reposters array
						if($result->type == 'post'){
							$result->post_comment_count = 0;
							$result->post_repost_count = 0;
							$result->post_view_count = 0;
							$result->post_reposters = array();
							$this->Post->contain();
							$this->data = $this->Post->read(array('reposters', 'comment_count', 'repost_count', 'view_count'), $result->id);
                            $result->post_comment_count = $this->data['Post']['comment_count'];
							$result->post_repost_count = $this->data['Post']['repost_count'];
							$result->post_view_count = $this->data['Post']['view_count'];
							$result->post_reposters = $this->data['Post']['reposters'];
						}
					}
				}
			}
		return $results;
	}


	function delete(){

		$this->Solr->deleteIndex();

	}

}





?>
