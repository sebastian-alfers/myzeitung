<?php

//require_once('libs/Apache/Solr/Service.php');
App::import('Lib', 'ApacheSolrService', array('file' => 'Apache/Solr/Service.php'));

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
	const SEARCH_RESULT_SEARCH_FIELD_NGRM = 'search_field_ngrm';
	const SEARCH_RESULT_SEARCH_FIELD_AUTO_SUGGEST = 'search_field_auto_suggest';
	
	const HOST = 'localhost';
	const PORT = 8983; # -alf 8080  -tim 8983
	const PATH = '/solr';


	var $useTable = false;

	//additional fields
	var $fields = array();

	CONST DEFAULT_LIMIT = 10;
	CONST SUGGEST_LIMIT = 6;
	
	private $solr = null;

	function __construct(){
		parent::__construct();

        $port = self::PORT;

        if(defined('SOLR_PORT')){

             $port = SOLR_PORT;
        }

        if(USE_SOLR){
             $this->solr = new Apache_Solr_Service(self::HOST, $port, self::PATH);
            
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
          //      debug($item);
				$part = new Apache_Solr_Document();

				foreach ( $fields as $key => $value ) {
        //            debug($key);
					$part->$key = $value;
				}
				$documents[] = $part;
			}
           // debug($documents);
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
	function delete($records){
        if(!empty($records)){
            if(!is_array($records)){
                $record = $records;
                $records = array();
                $records[] = $record;
            }
        }
		if(!USE_SOLR) return;

        foreach($records as $record){
            $xml = '<delete><id>'. $record .'</id></delete>';
            $this->getSolr()->delete($xml);

        }
   
        $this->getSolr()->commit();
        $this->getSolr()->optimize();

	}



    function refreshPostsIndex(){
        //read all posts with  order "id desc"
        App::import('model','Post');
        $this->Post = new Post();
        $this->Post->contain('User','Route', 'Topic.id', 'Topic.name');

        $posts = $this->Post->find('all',array('order' => array('Post.id desc')));

        $lastFoundId = 0;
        //just in case there are still search entries, with an higher id, than the highest
        //record from database
        if(isset($posts[0]['Post']['id'])){
            $lastFoundId = $posts[0]['Post']['id'] + 25;
        }

        $delete = array();
        $update = array();

        
        foreach($posts as $post){
            //if a post is enabled its gonna be updated in the index
            //if a post is disabled (not enabled) its gonna be deleted from the index

            if($post['Post']['enabled'] == true){
                $this->Post->id = $post['Post']['id'];
                $this->Post->data = $post;
                $update[] = $this->Post->generateSolrData();
            }else{
                $delete[] = self::TYPE_POST.'_'.$post['Post']['id'];
            }
            //start of the magic :-)
            //comparing the id of the LAST and the ACTIVE record.
            //if the result of the substraction (differene )of the LAST-id and the ACTIVE-id is larger than 1
            //we know that there were records in between once. to be on the safe side, we delete all these
            //"ghost-records" from solr, just in case that there might be a solr-record left.
            if(($lastFoundId - $post['Post']['id']) > 1){
                //there had been posts between the LAST and the ACTIVE record
                //-> delete all index_entries that match those post id's in between
                $deleteStartId = $post['Post']['id'] + 1;
                $deleteEndId = $lastFoundId - 1;
                for($i = $deleteStartId; $i <= $deleteEndId; $i++){
                    $delete[] = self::TYPE_POST.'_'.$i;
                }
            }

            //keep the id of the active record
            //this will be the "last record id" for the NEXT record.
            $lastFoundId = $post['Post']['id'];
        }
        //add or delete records
        $this->add($update);
        $this->delete($delete);
    }



    function refreshUsersIndex(){
        //read all users with  order "id desc"
        App::import('model','Users');
        $this->User = new User();
        $this->User->contain();

        $users = $this->User->find('all',array('order' => array('User.id desc')));

        $lastFoundId = 0;
        //just in case there are still search entries, with an higher id, than the highest
        //record from database
        if(isset($users[0]['User']['id'])){
            $lastFoundId = $users[0]['User']['id'] + 25;
        }

        $delete = array();
        $update = array();


        foreach($users as $user){
            $this->User->id = $user['User']['id'];
            //if a user is enabled its gonna be updated in the index
            //if a User is disabled (not enabled) its gonna be deleted from the index
            if($user['User']['enabled'] == true){
                $this->User->id = $user['User']['id'];
                $this->User->data = $user;
                $update[] = $this->User->generateSolrData();
            }else{
                $delete[] = self::TYPE_USER.'_'.$user['User']['id'];
            }
            //start of the magic :-)
            //comparing the id of the LAST and the ACTIVE record.
            //if the result of the substraction (differene )of the LAST-id and the ACTIVE-id is larger than 1
            //we know that there were records in between once. to be on the safe side, we delete all these
            //"ghost-records" from solr, just in case that there might be a solr-record left.
            if(($lastFoundId - $user['User']['id']) > 1){
                //there had been users between the LAST and the ACTIVE record
                //-> delete all index_entries that match those post id's in between
                $deleteStartId = $user['User']['id'] + 1;
                $deleteEndId = $lastFoundId - 1;

                for($i = $deleteStartId; $i <= $deleteEndId; $i++){
                    $delete[] = self::TYPE_USER.'_'.$i;
                }
            }

            //keep the id of the active record
            //this will be the "last record id" for the NEXT record.
            $lastFoundId = $user['User']['id'];
        }
        //add or delete records
        $this->add($update);
        $this->delete($delete);
    }


    
    function refreshPapersIndex(){
        //read all Papers with  order "id desc"
        App::import('model','Papers');
        $this->Paper = new Paper();
        $this->Paper->contain('User', 'Route');
        $papers = $this->Paper->find('all',array('order' => array('Paper.id desc')));

        $lastFoundId = 0;
        //just in case there are still search entries, with an higher id, than the highest
        //record from database
        if(isset($papers[0]['Paper']['id'])){
            $lastFoundId = $papers[0]['Paper']['id'] + 25;

        }

        $delete = array();
        $update = array();

        foreach($papers as $paper){

            //if a paper is enabled its gonna be updated in the index
            //if a paper is disabled (not enabled) its gonna be deleted from the index
            if($paper['Paper']['enabled'] == true){
                $this->Paper->id = $paper['Paper']['id'];
                $this->Paper->data = $paper;
                $update[] = $this->Paper->generateSolrData();
            }else{
                $delete[] = self::TYPE_PAPER.'_'.$paper['Paper']['id'];
            }
            //start of the magic :-)
            //comparing the id of the LAST and the ACTIVE record.
            //if the result of the substraction (differene )of the LAST-id and the ACTIVE-id is larger than 1
            //we know that there were records in between once. to be on the safe side, we delete all these
            //"ghost-records" from solr, just in case that there might be a solr-record left.
            if(($lastFoundId - $paper['Paper']['id']) > 1){
                //there had been papers between the LAST and the ACTIVE record
                //-> delete all index_entries that match those post id's in between
                $deleteStartId = $paper['Paper']['id'] + 1;
                $deleteEndId = $lastFoundId - 1;

                for($i = $deleteStartId; $i <= $deleteEndId; $i++){
                    $delete[] = self::TYPE_PAPER.'_'.$i;
                }
            }

            //keep the id of the active record
            //this will be the "last record id" for the NEXT record.
            $lastFoundId = $paper['Paper']['id'];
        }
        //add or delete records
        $this->add($update);
        $this->delete($delete);
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

	function query($query, $limit = self::DEFAULT_LIMIT, $grouped = true, $start = 0,$params = null){

		
		if(!USE_SOLR) return;
		
		if(empty($query)) return false;
		$results = array();
		$params['sort'] = 'score desc';
		
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
			$response = $this->getSolr()->search($query, $start, $limit, $params);

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
			$this->log('Error while performing query to solr. query: ' . $query);
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
		echo "has been deleted";
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

