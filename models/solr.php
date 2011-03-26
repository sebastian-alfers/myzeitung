<?php

require_once('libs/Apache/Solr/Service.php');

class Solr extends AppModel {

	const TYPE_USER = 'user';
	const TYPE_POST = 'post';
	const TYPE_PAPER = 'paper';
	const TYPE_CATEGORY = 'category';
	const TYPE_UNKNOWN = 'unknown';

	const HOST = 'localhost';
	const PORT = 8983;
	const PATH = '/solr';

	var $useTable = false;

	CONST DEFAULT_LIMIT = 10;

	private $solr = null;

	function __construct(){
		parent::__construct();

		$this->solr = new Apache_Solr_Service(self::HOST, self::PORT, self::PATH);

	}

	/**
	 * add documents to solr index
	 *
	 * @param array $documents
	 */
	function add($docs = array()){

		try {
			/*
			 $docs = array(
			 'doc_no1' => array(
			 'index_id' => 1,
			 'id' => '332',
			 'user_name' => '332',
			 'topic_name' => '332',
			 'user_id' => '332',
			 'content' => 'Alphabet',
			 'title' => 'Franz tobi jagt im komplett verwahrlosten Taxi quer durch Bayern',

			 ),
			 'doc_no2' => array(
			 'index_id' => 2,
			 'id' => '332',
			 'user_name' => '332',
			 'topic_name' => '332',
			 'user_id' => '332',
			 'content' => 'Buchstaben uuu',
			 'title' => 'jjj Polyfon zwitschernd assen M�xchens V�gel R�ben, Joghurt und Quark.',

			 ),
			 );*/

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
			}
			else{
				debug('<pre>');
				debug(debug_print_backtrace());
				debug('Solr not running!');
				return;
			}

		}
		catch ( Exception $e ) {
			debug('Error while adding documents to index: ' . $e->getMessage());
			debug(debug_backtrace());
			$this->log('Error while adding documents to index: ' . $e->getMessage());
		}

	}

	/**
	 * removes an indexed field by id
	 *
	 * @param string $id
	 */
	function delete($id){
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
	 */
	function query($query, $limit = self::DEFAULT_LIMIT, $grouped = true){
		if(empty($query)) return false;
		$results = array();

		// if magic quotes is enabled then stripslashes will be needed
		if (get_magic_quotes_gpc() == 1) $query = stripslashes($query);

		try
		{
			$grouped = array();
			$response = $this->getSolr()->search($query, 0, $limit);
			if ( $response->getHttpStatus() == 200 ) {
				//debug($response->response->docs);die();
				foreach($response->response->docs as $doc){
					if(isset($doc->type)){
						$grouped[$doc->type][] = $doc;
					}
					else{
						$grouped[self::TYPE_UNKNOWN][] = $doc;
					}
				}

				if(!empty($grouped)){
					$results['results'] = $grouped;
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
		if($this->solr == null){
			$this->solr = new Apache_Solr_Service(self::HOST, self::PORT, self::PATH);
		}
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
		if($this->solr instanceof Apache_Solr_Service && $this->solr != null){
			if (!$this->solr->ping()) return false;
			return true;
		}


		return false;
	}

}
?>