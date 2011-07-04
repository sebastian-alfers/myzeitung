<?php

require_once('libs/Apache/Solr/Service.php');

class Solr extends AppModel {

	const TYPE_USER = 'user';
	const TYPE_POST = 'post';
	const TYPE_PAPER = 'paper';
	const TYPE_CATEGORY = 'category';
	const TYPE_UNKNOWN = 'unknown';

	
	const QUERY_TYPE_AUTO_SUGGEST = 'auto';
	const QUERY_TYPE_SEARCH_RESULTS = 'search';
	
	const SEARCH_RESULT_SEARCH_FIELD = 'search_field';
	const SEARCH_RESULT_SEARCH_FIELD_PHONETIC = 'search_field_phonetic';
	const SEARCH_RESULT_SEARCH_FIELD_AUTO_SUGGEST = 'search_field_auto_suggest';
	
	const HOST = 'localhost';
	const PORT = 8080;
	const PATH = '/solr';


	var $useTable = false;

	//additional fields
	var $fields = array();

	CONST DEFAULT_LIMIT = 2;
	CONST SUGGEST_LIMIT = 6;
	
	private $solr = null;

	function __construct(){
		parent::__construct();

		if(USE_SOLR){
			$this->solr = new Apache_Solr_Service(self::HOST, self::PORT, self::PATH);
		}

	}

	/**
	 * add documents to solr index
	 *
	 * @param array $documents
	 */
	function add($docs = array()){
		if(!USE_SOLR) return;

		try {

			$documents = array();

			foreach ( $docs as $item => $fields ) {

				$part = new Apache_Solr_Document();

				foreach ( $fields as $key => $value ) {
					$part->$key = $value;
				}
				$documents[] = $part;
			}

			if($this->getSolr()){
				$this->getSolr()->addDocuments( $documents );
				$this->getSolr()->commit();
				$this->getSolr()->optimize();
				return true;
			}
			else{
				//debug('<pre>');
				//debug(debug_print_backtrace());
				//debug('solr not running');
				//return false;
				return true;

			}

		}
		catch ( Exception $e ) {
			debug('Error while adding documents to index: ' . $e->getMessage());
			$back_trace = debug_backtrace();
			$msg = $back_trace[0]['file'] . '<br />';
			$msg .= 'function: ' . $back_trace[0]['function'] . '<br />';
			$msg .= 'line: ' . $back_trace[0]['line'] . '<br />';
			debug($msg);
			// @todo thorw exception
			$this->log('Error while adding documents to index: ' . $e->getMessage());
			//debug('solr not running');
			//return false;
			return true;
		}
		debug('solr not running');
		return false;

	}

	/**
	 * removes an indexed field by id
	 *
	 * @param string $id
	 */
	function delete($id){
		if(!USE_SOLR) return;

		if(!empty($id)){
			$xml = '<delete><id>'. $id .'</id></delete>';
			$this->getSolr()->delete($xml);
			$this->getSolr()->commit();
			$this->getSolr()->optimize();
		}

	}

	/**
	 *
	 * performs a query to solr and returns result
	 *
	 * @param string $query
	 * @param int $limit
	 * @param boolean $grouped
	 * @param int - pagination, start from document $start
	 */
	function query($query, $limit = self::DEFAULT_LIMIT, $grouped = true, $start = 0){
		
		if(!USE_SOLR) return;
		
		if(empty($query)) return false;
		$results = array();

		// if magic quotes is enabled then stripslashes will be needed
		if (get_magic_quotes_gpc() == 1) $query = stripslashes($query);

		try
		{				
			$data = array();
				
			if(!empty($this->fields)){
				foreach($this->fields as $field_name => $filter_value){
					$query.= ' AND ' . $field_name.':"'.$filter_value.'"';
				}
			}

			$response = $this->getSolr()->search($query, $start, $limit, array('sort' => 'score desc'));
			if ( $response->getHttpStatus() == 200 ) {
				//debug($response->response->docs);die();
				foreach($response->response->docs as $doc){
					if(isset($doc->type) && !$grouped === false){
						$data[$doc->type][] = $doc;
					}
					else{
						$data[] = $doc;
					}
				}

				if(!empty($data)){
					$results['results'] = $data;
				}
				else{
					$results['results'] = $response;
				}
				$results['rows'] = (int) $response->response->numFound;
				$results['start'] = min(1, $results['rows']);
				$results['end'] = min($limit, $results['rows']);
			}
			else{
				$this->log('Wrong solr status! ' . $response->getHttpStatusMessage());
			}
		}
		catch (Exception $e)
		{
			$this->log('Erro while performing query to solr. query: ' . $query);
		}

		return $results;
	}

	/**
	 * create net instance of Apache_Solr_Service
	 * checks if possibel to ping
	 */
	function getSolr(){

		if(!USE_SOLR) return;

		if(!$this->canPing()){
			$this->solr = null;
		}

		return $this->solr;
	}

	/**
	 * try a ping if solr instance is there
	 *
	 */
	function canPing(){
		if(!USE_SOLR) return;

		if($this->solr instanceof Apache_Solr_Service && $this->solr != null){
			if (!$this->solr->ping()) return false;
			return true;
		}


		return false;
	}

	function deleteIndex(){
		if(!USE_SOLR) return;

		var_dump($this->getSolr()->deleteByQuery('*:*'));
		$this->getSolr()->commit();
		echo "has been delteted";
		return true;
	}

	/**
	 * add one or more fields to be added in addition to default serach field "search_field"
	 * @param array of type [field_name=>filter_value]
	 */
	function setSearchFields($fields){
		if(!empty($fields)){
			foreach($fields as $field_name => $filter_value){
				$this->fields[$field_name] = $filter_value;
			}
		}
	}
}
