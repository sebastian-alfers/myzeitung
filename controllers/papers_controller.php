<?php
class PapersController extends AppController {

	var $name = 'Papers';
	var $components = array('Auth', 'Session');
	var $uses = array('Paper', 'Category', 'Route', 'User', 'ContentPaper');

	//for types in DB, IMPORTAT for DB, DO NOT CHANGE!
	const SOURCE_TYPE_USER 		= 0;
	const SOURCE_TYPE_TOPIC		= 1;
	const TARGET_TYPE_PAPER 		= 2;
	const TARGETE_TYPE_CATEGORY 	= 3;

	const PAPER 	= 'paper';
	const CATEGORY 	= 'category';
	const USER 		= 'user';
	const TOPIC 	= 'topic';	

	const CONTENT_DATA = 'content_data';

	//to concatinate for frontend e.g. category_#id (category_44)
	const SEPERATOR = '_';

	public function beforeFilter(){
		parent::beforeFilter();
		//declaration which actions can be accessed without being logged in
		$this->Auth->allow('index');
	}

	function index() {

		// recursive gibt an, bis zu welcher relationsebene daten gezogen werde. bei -1 wird nur das model gezogen. bei 0 wird glaub ich auch eine belongsTo oder hasOne beziehung gezogen
		// bin aber nicht ganz sicher.
		// besser ist das containable behavior. damit kannst du mit $this->Paper->contain() angeben, welche models bei der abfrage mit berŸcksichtigt werden unabhŠngig der relationsebene.
		// http://book.cakephp.org/#!/view/1323/Containable  dort findest du noch mehr mšglichkeiten und auch nen vergleich zu recursive.
		// mit paginate funzt es in der syntax irgendwie anders als bei find() read() und ich finds gerade nicht. daher schreibe ich diesen aufsatz.
		$this->Paper->recursive = 2;
		$this->set('papers', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid paper', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('test', 'asdf');
		$this->set('paper', $this->Paper->read(null, $id));
		//	debug($this->Paper->read(null,$id));

	}

	/**
	 * controller to add content for:
	 * - paper / category /subcategory of category
	 *
	 * Enter description here ...
	 */
	function addcontent(){

		if (!empty($this->data)) {
			//form has been submitted
			
			if(isset($this->data['Paper'][self::CONTENT_DATA]) && !empty($this->data['Paper'][self::CONTENT_DATA])){
				//validate if hidden field is paper or category
				
				//add content for
				$source = $this->data['Paper'][self::CONTENT_DATA];
				//split
				$source = explode(self::SEPERATOR, $source);
				$sourceType = $source[0];
				$sourceId   = $source[1];
				$targetType = $this->data['Paper']['target_type'];
				
				if($this->_isValidTargetType($targetType) &&
				   $this->_isValidSourceType($sourceType) &&
					      isset($this->data['Paper']['target_id'])){
					
					$targetId = $this->data['Paper']['target_id'];
					
					if(count($source) == 2){
						switch ($targetType){
							case self::PAPER:
								//type of content for paper: user
								if($this->newContentPaper(self::SOURCE_TYPE_USER, $sourceId, $targetId)){
									// todo: save data here
									$this->Session->setFlash(__('paper data saved', true));
									$this->redirect(array('action' => 'index'));									
								}
								break;
							case self::CATEGORY:
								//type of content for paper: topic

						}
					}
				}
				else{
					//no valid soure or target type
					
				}


			}
		}
		else{
			//check if paper id is as param
			if(empty($this->params['pass'][1]) || !isset($this->params['pass'][1])){
				//no param for category
				$this->Session->setFlash(__('No param for paper', true));
				$this->redirect(array('action' => 'index'));
			}
			if(!$this->_isValidTargetType($this->params['pass'][0]) || !isset($this->params['pass'][1])){
				$this->Session->setFlash(__('Wrong type do add for', true));
				$this->redirect(array('action' => 'index'));
			}
			//type for content for hidden field
			$this->set('target_type', $this->params['pass'][0]);

			//id for content for hidden field, associated to target_type
			$this->set('target_id', $this->params['pass'][1]);

			//generated user data for select drop down
			$content_data = $this->_generateUserSelectData();
			$this->set(self::CONTENT_DATA, $content_data);
		}

	}

	function add() {
		if (!empty($this->data)) {
			//adding a route

			$this->Paper->create();
			$this->data['Paper']['owner_id'] = $this->Auth->User("id");
			if ($this->Paper->save($this->data)) {
				$routeData = array('Route' => array(
									'source' => $this->data['Paper']['title'],
									'ref_id' => $this->Paper->id,
									'target_controller' => 'papers',
									'target_action' => 'view',
									'target_param' => $this->Paper->id
				));

				$route = $this->Route->save($routeData);
				if(!empty($route)){
					$this->Session->setFlash(__('The paper has been saved', true));
					$this->redirect(array('action' => 'index'));
				}
				else{
					$this->Session->setFlash(__('Paper saved, error wile saving the paper route', true));
				}
			} else {
				$this->Session->setFlash(__('The paper could not be saved. Please, try again.', true));
			}




		}
		//$routes = $this->Paper->Route->find('list');
		//$this->set(compact('routes'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid paper', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Paper->save($this->data)) {
				$this->Session->setFlash(__('The paper has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The paper could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Paper->read(null, $id);
		}
		$routes = $this->Paper->Route->find('list');
		$this->set(compact('routes'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for paper', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Paper->delete($id)) {
			$this->Session->setFlash(__('Paper deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Paper was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}

	/**
	 * build data for dropdown so select user or topy
	 * to as reference for paper_content
	 *
	 * @return array()
	 */
	private function _generateUserSelectData(){
		$content_data = array();
		//build array for user
		$users = $this->User->find('all');
		//debug($users);die();
		$content_data = array('options' => array());
		foreach($users as $user){
			//debug($user);die();
			$content_data['options'][self::USER.self::SEPERATOR.$user['User']['id']] = $user['User']['firstname'].' (all topics)';
			$topics = $user['Topic'];
			if(isset($topics) && count($topics >0)){
				foreach($topics as $topic){
					$content_data['options'][self::TOPIC.self::SEPERATOR.$topic['id']] = '> topic:'.$topic['name'];
				}

			}
			//debug($content_data);die();
		}
		return $content_data;
	}

	/**
	 * get $this->data and checks if is type
	 * paper or category for paper_content
	 *
	 * @return boolean
	 */
	private function _isValidTargetType($targetType){
		
		return (($targetType == self::PAPER) ||
		($targetType == self::CATEGORY));
	}
	
	/**
	 * get $this->data and checks if is type
	 * paper or category for paper_content
	 *
	 * @return boolean
	 */
	private function _isValidSourceType($sourceType){

		return (($sourceType == self::USER) ||
		($sourceType == self::TOPIC));
	}

	/**
	 * 
	 * content for a paper
	 * 
	 * @param string $sourceType
	 * @param int $sourceId
	 * @param int $targetId
	 */
	public function newContentPaper($sourceType, $sourceId, $targetId){
		//this->_saveNewContent(paper, ....)
		$this->data['ContentPaper'] = array();
		$this->data['ContentPaper']['source_type'] = $sourceType;
		$this->data['ContentPaper']['source_id'] = $sourceId;
		$this->data['ContentPaper']['target_id'] = $targetId;
		$this->data['ContentPaper']['target_type'] = self::TARGET_TYPE_PAPER;
		$this->ContentPaper->create();
		
		return $this->ContentPaper->save($this->data);	
	}
	
}
?>