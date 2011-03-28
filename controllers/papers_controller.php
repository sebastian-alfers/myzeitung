<?php
class PapersController extends AppController {

	var $name = 'Papers';
	var $components = array('Auth', 'Session');
	var $uses = array('Paper', 'Subscription', 'Category', 'Route', 'User', 'ContentPaper', 'Topic', 'CategoryPaperPost');
	var $helpers = array('Time', 'Image');

	public function beforeFilter(){
		parent::beforeFilter();
		//declaration which actions can be accessed without being logged in
		$this->Auth->allow('index');
	}

	function index() {
		$this->paginate = array(
		 	 'Paper' => array(
						  //fields
	          			  'fields' => array('id','owner_id','title','description','created','count_subscriptions'),
				         //limit of records per page
			            'limit' => 10,	        
		 	            //order
	     		        'order' => 'Paper.title ASC',
						//contain array: limit the (related) data and models being loaded per paper
			             'contain' => array(),	
			         )
			 	);	
		$papers = 	$this->paginate($this->Paper);
		//add temp variable to papers array: subscribed = true, if user is logged in and has already subscribed the paper
		// @todo !! REDUNDANT users_subscriptions and papers index -> build a component or something like that for this 
		if(is_array($papers)){
			for($i = 0; $i < count($papers); $i++){
				$papers[$i]['Paper']['subscribed'] = false;				
				if($this->Auth->user('id')){
				//check for subscriptions - if yes -> subscribed = true
					if(($this->Subscription->find('count', array('conditions' => array('Subscription.user_id' => $this->Auth->user('id'),'Subscription.paper_id' => $papers[$i]['Paper']['id'])))) > 0){
						$papers[$i]['Paper']['subscribed'] = true;
					} 
				}			
			}	
		}
		$this->set('papers', $papers);
	}

	
	/**
	 * @author Tim
	 * Action for viewing and browse a papers content.
	 * @param $paper_id 
	 * @param $category_id
	 */
	function view($paper_id = null, $category_id = null) {
		if (!$paper_id) {
			$this->Session->setFlash(__('Invalid paper', true));
			$this->redirect(array('action' => 'index'));
		}
		
		/*writing all settings for the paginate function. 
		  important here is, that only the paper's posts are subject for pagination.*/
		    $this->paginate = array(
			        'Post' => array(
				    //setting up the join. this conditions describe which posts are gonna be shown
			            'joins' => array(
			                array(
			                    'table' => 'category_paper_posts',
			                    'alias' => 'CategoryPaperPost',
			                    'type' => 'INNER',
			                    'conditions' => array(
			                        'CategoryPaperPost.post_id = Post.id',
			                		'CategoryPaperPost.paper_id' => $paper_id
			                    ),
			                   
			                ),
			                
			            ),
			            //limit of records per page
			            'limit' => 10,
			            //order
			            'order' => 'CategoryPaperPost.created DESC',
			        	//contain array: limit the (related) data and models being loaded per post
			            'contain' => array('User.id','User.username'),
			         )
			    );
	    if($category_id != null){
	    	//adding the topic to the conditions array for the pagination - join
	    	$this->paginate['Post']['joins'][0]['conditions']['CategoryPaperPost.category_id'] = $category_id;
	    }		
		
		$this->Paper->contain('User.id', 'User.username', 'Category.name', 'Category.id');
		$this->set('paper', $this->Paper->read(null, $paper_id));
		$this->set('posts', $this->paginate($this->Paper->Post));
		
	}

	/**
	 * @author tim
	 * Function for a user to subscribe a paper.
	 * @param int $paper_id - paper to subscribe
	 */
	function subscribe($paper_id){
		if(isset($paper_id)){

			//reading paper
			$this->Paper->contain();
			$this->data = $this->Paper->read(null, $paper_id);

			//valid paper was found
			if(isset($this->data['Paper']['id'])){
				if($this->data['Paper']['owner_id'] != $this->Auth->user('id')){
					//post is not from subscribing user		
	
					//check if the user already subscribed the paper -> just one paper/user combination allowed 
					$subscriptionData = array(
											'paper_id' => $paper_id,
										   	'user_id' => $this->Auth->user('id'));
					$subscriptions = $this->Subscription->find('all',array('conditions' => $subscriptionData));
					// if there are no subscriptions for this paper/user combination yet
					
					if(!isset($subscriptions[0])){
		
	
						//creating subscription
						$this->Subscription->create();
						if($this->Subscription->save($subscriptionData)){
								//subscription was saved
								$this->Session->setFlash(__('You have subscribed the paper successfully.', true));
					
								$this->Paper->count_subscriptions += 1;
								$this->Paper->save($this->data['Paper']);							
						}	else {
							// subscription couldn't be saved
							$this->Session->setFlash(__('The paper could not be subscribed.', true));
						}
					 

					}else{
						// already subscribed
						$this->Session->setFlash(__('Paper has already been subscribed.', true));
						$this->log('Paper/Subscribe: User '.$this->Auth->user('id').' tried to subscribe  Paper'.$paper_id.' which he had already subscribed.');
					}
				} else {
							//user tried to subscribe his own paper
							$this->Session->setFlash(__('You cannot subscribe your own paper. It is subscribed automatically.', true));
							$this->log('Paper/Subscribe: User '.$this->Auth->user('id').' tried to subscribe Paper.'.$paper_id.' which is his own paper.');
				}
			} else {
				// paper was not found
				$this->Session->setFlash(__('Paper could not be found.', true));

			}
		}else {
			if(!isset($paper_id)){
				// no paper id
				$this->Session->setFlash(__('Invalid paper id.', true));
			} 
		}
		$this->redirect($this->referer());
	}
	
	/**
	 * @autohr Tim
	 * Function for a user to unsubscribe a paper.
	 * @param int $paper_id
	 */
	function unsubscribe($paper_id){
		if(isset($paper_id)){

			$this->Paper->contain();	
			$this->data = $this->Paper->read(null, $paper_id);
			if(isset($this->data['Paper']['id'])){
				if($this->data['Paper']['owner_id'] != $this->Auth->user('id')){
					
					// just in case there are several subscriptions for the combination post/user - all will be deleted.
					$subscriptions =  $this->Subscription->find('all',array('conditions' => array('Subscription.paper_id' => $paper_id, 'Subscription.user_id' => $this->Auth->user('id'))));
					$delete_counter = 0;
					foreach($subscriptions as $subscription){
						//deleting the subscriptions from the db
						$this->Subscription->delete($subscription['Subscription']['id']);
						$delete_counter += 1;
		
					}
					//writing log entry if there were more than one entries for this repost (shouldnt be possible)
					if($delete_counter > 1){
						$this->log('Paper/unsubscribe: User '.$this->Auth->user('id').' had more then 1 subscription entry for Paper '.$paper_id.'. (now deleted) This should not be possible.');
					}
		
					if($delete_counter >= 1){
						$this->Session->setFlash(__('Unsubscribed successfully.', true));
		
						//decrementing subscribers counter	
		
						$this->data['Paper']['count_subscriptions'] -= 1;
						$this->Paper->save($this->data['Paper']);
					} else {
						$this->Session->setFlash(__('Subscription could not be removed or no subscription found', true));
					}
				} else {
						$this->Session->setFlash(__('You cannot unsubscribe your own paper. You can delete it.', true));
						$this->log('Paper/unsubscribe: User '.$this->Auth->user('id').' tried to unsubscribe his own Paper '.$paper_id.'. This should not be possible.');
				}
			} else {
				$this->Session->setFlash(__('Invalid Paper id. Paper could not be found. ', true));
				$this->log('Paper/unsubscribe: User '.$this->Auth->user('id').' tried to unsubscribe Paper '.$paper_id.', which could not be found.');

			}
		}
		else {
			// no paper $id
			$this->Session->setFlash(__('No paper id', true));
		}

		$this->redirect($this->referer());
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
					//no valid source or target type

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
			$this->set('owner_id', $this->data['Paper']['owner_id']);
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
	 * associate content to paper
	 * 
	 * @param int $paperId
	 * @param int $categoryId
	 * @param int $userId
	 * @param int $topicId
	 * 
	 * @return boolean
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