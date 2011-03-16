<?php
class PapersController extends AppController {

	var $name = 'Papers';
	var $components = array('Auth', 'Session');
	var $uses = array('Paper', 'Category', 'Route', 'User', 'ContentPaper', 'Topic', 'CategoryPaperPost');



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
		$this->Paper->recursive = 2;//important to load categories
		$this->set('papers', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid paper', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Paper->contain('User.id', 'User.username', 'Category', 'Post');
		$this->set('paper', $this->Paper->read(null, $id));
		
	}

	/**
	 *
	 */
	function references(){
		if(!$this->_validateShowContentData($this->params)){
			$this->Session->setFlash(__('invalid data for content view ', true));
			$this->redirect(array('action' => 'index'));
		}
		else{
			$type = $this->params['pass'][0];
			$id = $this->params['pass'][1];
			switch($type){
				case ContentPaper::PAPER:

					$this->Paper->read(null, $id);
					$paperReferences = $this->Paper->getContentReferences();
					$this->set('paperReferences', $paperReferences);
					break;
				case ContentPaper::CATEGORY:

					break;
				default:
					$this->Session->setFlash(__('invalid data for content view ', true));
					$this->redirect(array('action' => 'index'));
					exit;

			}


		}
	}

	/**
	 * returns true if all data are valid
	 */
	private function _validateShowContentData($data){
		if (empty($data)) return false;

		//only paper or category dadta
		if(!in_array($data['pass'][0], array(ContentPaper::PAPER, ContentPaper::CATEGORY))) return false;

		return true;
	}

	/**
	 * controller to add content subscription for:
	 * - paper / category /subcategory of category
	 *
	 * Enter description here ...
	 */
	function addcontent(){

		if (!empty($this->data)) {
			//form has been submitted

			if(isset($this->data['Paper'][ContentPaper::CONTENT_DATA]) && !empty($this->data['Paper'][ContentPaper::CONTENT_DATA])){
				//validate if hidden field is paper or category

				//add content for
				$source = $this->data['Paper'][ContentPaper::CONTENT_DATA];
				//split
				$source = explode(ContentPaper::SEPERATOR, $source);
				$sourceType = $source[0];
				$sourceId   = $source[1];
				$targetType = $this->data['Paper']['target_type'];

				if($this->_isValidTargetType($targetType) &&
				$this->_isValidSourceType($sourceType) &&
				isset($this->data['Paper']['target_id']))
				{
					if(count($source) == 2){

						//prepare variables to indicate whole user or only topic as source
						$user_id = null;
						$topic_id = null;
						if($sourceType == ContentPaper::USER) $user_id = $sourceId;
						if($sourceType == ContentPaper::TOPIC) $topic_id = $sourceId;

						switch ($targetType){
							case ContentPaper::PAPER:
								$this->_associateContentForPaper($user_id, $topic_id);
								break;
							case ContentPaper::CATEGORY:

								$category_id = $this->data['Paper']['target_id'];
								//get paper for category
								$category = $this->Category->read(null, $category_id);

								if($category['Paper']['id']){
									$paper_id = $category['Paper']['id'];
									if($this->newContentForPaper($paper_id, $category_id, $user_id, $topic_id)){
										// todo: save data here
										$this->Session->setFlash(__('paper data saved', true));
										$this->redirect(array('action' => 'index'));
									}
								}
								else{
									//category MUST have a paper
									//not able to read paper for category -> error
									$this->Session->setFlash(__('error while reading paper for category!', true));
									$this->redirect(array('action' => 'index'));
								}
								break;
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
			$this->set(ContentPaper::CONTENT_DATA, $content_data);
		}
	}


	/**
	 *
	 * associate contenet (user or topic) to a paper
	 *
	 * @param int $user_id
	 * @param int $topic_id
	 */
	private function _associateContentForPaper($user_id, $topic_id){
		$paper_id = $this->data['Paper']['target_id'];
		if($this->newContentForPaper($paper_id, null, $user_id, $topic_id)){
			$this->Session->setFlash(__('content was associated to paper', true));
			$this->redirect(array('action' => 'index'));
		}
		else{
			$this->Session->setFlash(__('error wile saving data for paper', true));
			$this->redirect(array('action' => 'index'));
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
			$content_data['options'][ContentPaper::USER.ContentPaper::SEPERATOR.$user['User']['id']] = $user['User']['username'].' (all topics)';
			$topics = $user['Topic'];
			if(isset($topics) && count($topics >0)){
				foreach($topics as $topic){
					$content_data['options'][ContentPaper::TOPIC.ContentPaper::SEPERATOR.$topic['id']] = '> topic:'.$topic['name'];
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

		return (($targetType == ContentPaper::PAPER) ||
		($targetType == ContentPaper::CATEGORY));
	}

	/**
	 * get $this->data and checks if is type
	 * paper or category for paper_content
	 *
	 * @return boolean
	 */
	private function _isValidSourceType($sourceType){

		return (($sourceType == ContentPaper::USER) ||
		($sourceType == ContentPaper::TOPIC));
	}

	/**
	 *
	 * content for a paper
	 *
	 * @param string $sourceType
	 * @param int $sourceId
	 * @param int $targetId
	 */
	public function newContentForPaper($paperId, $categoryId, $userId, $topicId){

		if(!$this->_canAssociateDataToPaper($paperId, $categoryId, $userId, $topicId)){
			return false;
		}

		//$this->ContentPaper->find('all', )

		//this->_saveNewContent(paper, ....)
		$this->data['ContentPaper'] = array();
		$this->data['ContentPaper']['paper_id'] 	= $paperId;
		$this->data['ContentPaper']['category_id'] 	= $categoryId;
		$this->data['ContentPaper']['user_id'] 		= $userId;
		$this->data['ContentPaper']['topic_id'] 	= $topicId;

		$this->ContentPaper->create();

		return $this->ContentPaper->save($this->data);
	}

	/**
	 *	returns true if is allowed to associate data (user or topic) to a paper (or category)
	 *
	 *
	 * validate by following criteria
	 * - if constelation(paper_id, category_id, user_id, topic_id) isnt already there
	 *
	 * - if the whole user is associated to a paper or a category, check if there are already
	 *   other associations to one or more topic of the user IN THIS category
	 *   @todo if so, give msg to user and say it is not possible to because of other refs (show references)
	 *   @todo ask is want to delete all other refs to the user«s topics and add whole user
	 *
	 * - if a topic is associated to a paper or a category, check if this topic isnt already
	 *   associated in this category
	 *
	 * @param int $paperId
	 * @param int $categoryId
	 * @param int $userId
	 * @param int $topicId
	 */
	private function _canAssociateDataToPaper($paperId, $categoryId, $userId, $topicId){

		//check for extatly this constelation
		$conditions = array('conditions' => array(
											'ContentPaper.paper_id' => $paperId,
											'ContentPaper.category_id' => $categoryId,
											'ContentPaper.user_id' => $userId,
											'ContentPaper.topic_id' => $topicId));

		$checkDoubleReference = $this->ContentPaper->find('all', $conditions);

		//if we get an result -> not allowed to add this constelation
		if(isset($checkDoubleReference[0]['ContentPaper']['id'])){
			$this->Session->setFlash(__('this constelations already exists', true));
			$this->redirect(array('action' => 'index'));
		}

		if($userId && $userId != null && $userId >= 0){
			//get user topics
			$user = $this->User->read(null, $userId);
			$userTopics = $user['Topic'];


			//if user has no topcis (should not be possible...)
			if(count($userTopics) == 0) return true;

			$paper = $this->Paper->read(null, $paperId);

			if($categoryId){
				//whole user to a category
				//check, it this user has not topic in this category
				$recursion = 1;
				$categoryTopics = $this->Paper->getTopicReferencesToOnlyThisCategory($recursion);
					
				//if paper has no topics referenced
				if(count($categoryTopics) == 0) return true;

				//check if one of the user topics is in the paper topics
				foreach($userTopics as $userTopic){

					foreach($categoryTopics as $categoryTopic){
						if($userTopic['id'] == $categoryTopic['Topic']['id']){
							//the category has already a topic from the user
							//@todo -> ask user if he wants to delete all topics from user to be able
							//   to associate whole user to paper
							$this->Session->setFlash(__('Error! there already exist a topic form this user in the category.', true));
							$this->redirect(array('action' => 'index'));
							return false;
						}
					}
				}
				return true;


			}

			//whole user to paper
			//get all user topics associated to that paper
			//check if alreay one of the users topics is associated to this paper itself or one of its categorie


			//$this->_hasPaperTopicsFromUser($paper['Paper']['id'], $user['User']['id']);
			$recursion = 1;
			$paperTopics = $this->Paper->getTopicReferencesToOnlyThisPaper($recursion);

			//if paper has no topics referenced
			if(count($paperTopics) == 0) return true;

			//check if one of the user topics is in the paper topics
			foreach($userTopics as $userTopic){

				foreach($paperTopics as $paperTopic){
					if($userTopic['id'] == $paperTopic['Topic']['id']){
						//the paper has already a topic from the user
						//@todo ask user if he wants to delete all topics from user to be able
						//   to associate whole user to paper
						$this->Session->setFlash(__('Error! there already exist a topic form this user in the paper.', true));
						$this->redirect(array('action' => 'index'));
						return false;
					}
				}
			}
			return true;


		}
		else if($topicId && $topicId != null){
			//check if this topic isnt already assocaited to this paper itself/ to category itself

			$this->Topic->read(null, $topicId);

			if(!$this->Topic->id){
				$this->Session->setFlash(__('Error! topic could not be loaded', true));
				$this->redirect(array('action' => 'index'));
			}
			else{


				if($this->Topic->data['Topic']['user_id']){
					//check if the posts user is not in this paper
					$userId = $this->Topic->data['Topic']['user_id'];
					$conditions = array('conditions' => array(
											'ContentPaper.paper_id' => $paperId,
											'ContentPaper.user_id' => $userId,
											'ContentPaper.category_id' => null));

					if($categoryId > 0){
						//add category
						$conditions['conditions']['ContentPaper.category_id'] = $categoryId;							
					}
					
					

					$checkUser = $this->ContentPaper->find('all', $conditions);
					
					
					
					if(isset($checkUser[0]['ContentPaper']['id'])){
						//user is already in paper
						$this->Session->setFlash(__('The owner of this topic is already in this category ', true));
						$this->redirect(array('action' => 'index'));
						return false;
					}
					//topics user is not in the paper -> can add topic to paper / category
					return true;


				}
				else{
					$this->Session->setFlash(__('Error! user for topic could not be loaded', true));
					$this->redirect(array('action' => 'index'));
				}

			}


		}


		return true;

	}


	/**
	 *
	 * content for a paper
	 *
	 * @param string $sourceType
	 * @param int $sourceId
	 * @param int $targetId
	 */
	public function newContentForCategory($sourceType, $sourceId, $targetId){
		//this->_saveNewContent(paper, ....)
		$this->data['ContentPaper'] = array();
		$this->data['ContentPaper']['source_type'] = $sourceType;
		$this->data['ContentPaper']['source_id'] = $sourceId;
		$this->data['ContentPaper']['target_id'] = $targetId;
		$this->data['ContentPaper']['target_type'] = ContentPaper::TARGET_TYPE_CATEGORY;

		$this->ContentPaper->create();

		return $this->ContentPaper->save($this->data);
	}




	private function _getSourceTypeId($sourceType){
		if($sourceType == ContentPaper::USER) return ContentPaper::SOURCE_TYPE_USER;
		if($sourceType == ContentPaper::TOPIC) return ContentPaper::SOURCE_TYPE_TOPIC;

		// @todo error log
	}

}
?>