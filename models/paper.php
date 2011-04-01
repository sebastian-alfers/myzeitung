<?php
class Paper extends AppModel {
	var $name = 'Paper';
	var $actsAs = array('Increment'=>array('incrementFieldName'=>'count_subscriptions'));
	var $displayField = 'title';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	private  $_contentReferences = null;


	var $hasOne = array(
		'Route' => array(
			'className' => 'Route',
			'foreignKey' => 'ref_id',//important to have FK
	),
	);


	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'owner_id'
			),
			);

			var $hasAndBelongsToMany = array(
		'Post' => array(
			'className' => 'Post',
			'joinTable' => 'category_paper_posts',
			'foreignKey' => 'paper_id',
			'associationForeignKey' => 'post_id',
			//	'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => 'CategoryPaperPost.created DESC',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
			)
			);



			var $hasMany = array(
		'Category' => array(
			'className' => 'Category',
			'foreignKey' => 'paper_id',
			'conditions' => array('parent_id' => 0),//IMPORTANT! to avoid show sub-category in root
			//	'dependent' => false
			),
		'Subscription' => array(
			'className' => 'Subscription',
			'foreignKey' => 'paper_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
			)
			);

			/**
			 * @author tim
			 * Function for a user to subscribe a paper.
			 * @param int $user_id - user to subscribe a given paper
			 */
			public function subscribe($user_id){
				//check if the user already subscribed the paper -> just one paper/user combination allowed
				$subscriptionData = array(
							'paper_id' => $this->id,
						   	'user_id' => $user_id);
				if(($this->Subscription->find('count',array('conditions' => $subscriptionData))) == 0){

					if($data['Paper']['owner_id'] != $user_id){
						//paper is not from subscribing user
						//creating subscription
						$this->Subscription->create();
						if($this->Subscription->save($subscriptionData)){
							//subscription was saved
							$data['Paper']['count_subscriptions'] += 1;
							$this->save($data['Paper']);
							return true;
						}	else {
							// subscription couldn't be saved
							$this->log('Paper/Subscribe: The paper '.$this->id.' could not be subscribed by user '.$user_id);

						}
					} else {
						//user tried to subscribe his own paper
						$this->log('Paper/Subscribe: User '.$user_id.' tried to subscribe Paper.'.$this->id.' which is his own paper.');

					}

				}else{
					// already subscribed
					$this->log('Paper/Subscribe: User '.$user_id.' tried to subscribe  Paper'.$this->id.' which he had already subscribed.');

					return true;
				}
				return false;
			}



			/**
			 * @author tim
			 * deletes subscription for a paper for a specific user (param)
			 * @param int $user_id
			 */
			public function unsubscribe($user_id){

				if($data['Paper']['owner_id'] != $user_id){

					// just in case there are several subscriptions for the combination post/user - all will be deleted.
					$subscriptions =  $this->Subscription->find('all',array('conditions' => array('Subscription.paper_id' => $this->id, 'Subscription.user_id' => $user_id)));
					$delete_counter = 0;
					foreach($subscriptions as $subscription){
						//deleting the subscriptions from the db
						$this->Subscription->delete($subscription['Subscription']['id']);
						$delete_counter += 1;
					}
					//writing log entry if there were more than one entries for this repost (shouldnt be possible)
					if($delete_counter > 1){
						$this->log('Paper/unsubscribe: User '.$user_id.' had more then 1 subscription entry for Paper '.$this->id.'. (now deleted) This should not be possible.');
					}

					if($delete_counter >= 1){
						//decrementing subscribers counter
						$data['Paper']['count_subscriptions'] -= 1;
						$this->save($data);
					} else {
						$this->log('Paper/unsubscribe: Subscription could not be removed or no subscription found');
					}
					return true;
				} else {
					//$this->Session->setFlash(__('You cannot unsubscribe your own paper. You can delete it.', true));
					$this->log('Paper/unsubscribe: User '.$user_id.' tried to unsubscribe his own Paper '.$this->id.'. This should not be possible.');
				}
				return false;
			}
			/**
			 * @todo alf: das ganze muss ohne "recursive" laufen.... alles bitte mit contain
			 */

			function getContentReferences($recursive = 2){

				if($this->_contentReferences == null){

					App::import('model','ContentPaper');
					$contentPaper = new ContentPaper();



					$conditions = array('conditions' => array(
		'ContentPaper.paper_id' => $this->id));
					$paperReferences = array();
					$contentPaper->recursive = $recursive;// to get user from topic
					$paperReferences = $contentPaper->find('all', $conditions);


					$this->_contentReferences =  $paperReferences;
				}

				return $this->_contentReferences;

			}

			/**
			 * get a list of all topic associations related to this paper
			 */
			function getTopicReferencesToOnlyThisPaper($recursion = 2){
				$allReferences = $this->getContentReferences($recursion);
				$topicReferences = array();
				if(count($allReferences) > 0){
					foreach($allReferences as $reference){

						//only topics that are not associated to a category -> direkt in paper

						if($reference['Topic']['id'] && !$reference['Category']['id']	){
							$topicReferences[] = $reference;
						}
					}
				}
				return $topicReferences;
			}

			/**
			 * get a list of all topic associations related to this paper
			 */
			function getTopicReferencesToOnlyThisCategory($recursion = 2){
				$allReferences = $this->getContentReferences($recursion);
				$categoryReferences = array();
				if(count($allReferences) > 0){
					foreach($allReferences as $reference){
						//only topics that are not associated to a category -> direkt in paper
						if($reference['Topic']['id'] && $reference['Category']['id']){
							$categoryReferences[] = $reference;
						}
					}
				}
				return $categoryReferences;
			}

			/**
			 * 1)
			 * update solr index with saved data
			 */
			function afterSave($created){

				App::import('model','Solr');
				App::import('model','User');
				App::import('model','Subscription');


				if($this->id){
					/**
					 * @todo alf: hier wird glaub ich jedes mal nen satz erzeugt. stattdessen mŸsste ein Satz bei created angelegt werden und bei nicht created ge-updatet, bzw gelšscht und neu angelegt werden
					 */
					//get User information
					$user = new User();

					$userData = $user->read(null, $data['Paper']['owner_id']);
					$data['Paper']['index_id'] = 'paper_'.$this->id;
					$data['Paper']['id'] = $this->id;
					$data['Paper']['type'] = Solr::TYPE_PAPER;

					$data['Paper']['user_id'] = $userData['User']['id'];
					$data['Paper']['user_name'] = $userData['User']['username'];
					$solr = new Solr();
					$solr->add($this->removeFieldsForIndex($data));

					//create subscription for created paper
					if($created){
						$subscriptionData = array('paper_id' => $this->id,
				 							'user_id' => $userData['User']['id'],
											'own_paper' => true,
						);
						$this->Subscription = new Subscription();
						$this->Subscription->create();
						$this->Subscription->save($subscriptionData);
							
					}


				}
				else{
					$this->log('Error while adding paper to solr! No paper id in afterSave()');
				}
			}


			/**
			 * associate content to a paper or category
			 *
			 * @param $data data from controller
			 */
			public function associateContent($data){

				//form has been submitted

				if(isset($data['Paper'][ContentPaper::CONTENT_DATA]) && !empty($data['Paper'][ContentPaper::CONTENT_DATA])){
					//validate if hidden field is paper or category

					//add content for
					$source = $data['Paper'][ContentPaper::CONTENT_DATA];
					//split
					$source = explode(ContentPaper::SEPERATOR, $source);
					$sourceType = $source[0];
					$sourceId   = $source[1];
					$targetType = $data['Paper']['target_type'];

					if($this->isValidTargetType($targetType) &&
					$this->isValidSourceType($sourceType) &&
					isset($data['Paper']['target_id']))
					{
						if(count($source) == 2){

							//prepare variables to indicate whole user or only topic as source
							$user_id = null;
							$topic_id = null;
							if($sourceType == ContentPaper::USER) $user_id = $sourceId;
							if($sourceType == ContentPaper::TOPIC) $topic_id = $sourceId;
								
							switch ($targetType){
								case ContentPaper::PAPER:
									return $this->_associateContentForPaper($data, $user_id, $topic_id);
									break;
								case ContentPaper::CATEGORY:

									$category_id = $data['Paper']['target_id'];
									//get paper for category
									$category = $this->Category->read(null, $category_id);

									if($category['Paper']['id']){
										$paper_id = $category['Paper']['id'];
										if($this->newContentForPaper($paper_id, $category_id, $user_id, $topic_id)){
											// todo: save data here
											return true;
										}
									}
									else{
										//category MUST have a paper
										//not able to read paper for category -> error
										//$this->Session->setFlash(__('error while reading paper for category!', true));
										//$this->redirect(array('action' => 'index'));
										return false;
									}
									break;
							}
						}
					}
					else{
						//no valid source or target type
						return false;

					}


				}
				return false;


			}


			/**
			 *	returns true if is allowed to associate data (user or topic) to a paper (or category)
			 *
			 *
			 * validate by following criteria
			 * - if constelation(paper_id, category_id, user_id, topic_id) isnt already there
			 *
			 * - if the whole user is associated to a paper or category, check if there are already
			 *   other associations to one or more topic of the user IN THIS paper or category
			 *
			 *   @todo ask is want to delete all other refs to the user«s topics and add whole user
			 *
			 * - if a topic is associated to a paper or a category, check if this topic isnt already
			 *   associated in this category
			 *
			 * @param int $categoryId
			 * @param int $userId
			 * @param int $topicId
			 */
			private function _canAssociateDataToPaper($categoryId, $userId, $topicId){

				//check for extatly this constelation
				$conditions = array('conditions' => array(
											'ContentPaper.paper_id' => $this->id,
											'ContentPaper.category_id' => $categoryId,
											'ContentPaper.user_id' => $userId,
											'ContentPaper.topic_id' => $topicId));
				App::import('model','ContentPaper');
				$contentPaper = new ContentPaper();
				$checkDoubleReference = $contentPaper->find('all', $conditions);

				//if we get an result -> not allowed to add this constelation
				if(isset($checkDoubleReference[0]['ContentPaper']['id'])){
					//$this->Session->setFlash(__('this constelations already exists', true));
					//$this->redirect(array('action' => 'index'));
					return false;
				}

				if($userId && $userId != null && $userId >= 0){
					//get user topics
					$user = $this->User->read(null, $userId);
						
					$userTopics = $user['Topic'];

					//if user has no topcis (should not be possible...)
					if(count($userTopics) == 0) return true;

					$paper = $this->read(null, $this->id);

					if($categoryId){
						//whole user to a category
						//check, it this user has not topic in this category
						$recursion = 1;
						$categoryTopics = $this->getTopicReferencesToOnlyThisCategory($recursion);
							
						//if paper has no topics referenced
						if(count($categoryTopics) == 0) return true;

						//check if one of the user topics is in the paper topics
						foreach($userTopics as $userTopic){

							foreach($categoryTopics as $categoryTopic){
								if($userTopic['id'] == $categoryTopic['Topic']['id']){
									//the category has already a topic from the user
									//@todo -> ask user if he wants to delete all topics from user to be able
									//   to associate whole user to paper
									//$this->Session->setFlash(__('Error! there already exist a topic form this user in the category.', true));
									//$this->redirect(array('action' => 'index'));
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
					$paperTopics = $this->getTopicReferencesToOnlyThisPaper($recursion);

					//if paper has no topics referenced
					if(count($paperTopics) == 0) return true;

					//check if one of the user topics is in the paper topics
					foreach($userTopics as $userTopic){

						foreach($paperTopics as $paperTopic){
							if($userTopic['id'] == $paperTopic['Topic']['id']){
								//the paper has already a topic from the user
								//@todo ask user if he wants to delete all topics from user to be able
								//   to associate whole user to paper
								//$this->Session->setFlash(__('Error! there already exist a topic form this user in the paper.', true));
								//$this->redirect(array('action' => 'index'));
								return false;
							}
						}
					}
					return true;


				}
				else if($topicId && $topicId != null){
					//check if this topic isnt already assocaited to this paper itself/ to category itself

					App::import('model','Topic');
					$topic = new Topic();
					$topic->read(null, $topicId);

					if(!$topic->id){
						//$this->Session->setFlash(__('Error! topic could not be loaded', true));
						//$this->redirect(array('action' => 'index'));
						return false;
					}
					else{


						if($topic->data['Topic']['user_id']){
							//check if the posts user is not in this paper
							$userId = $topic->data['Topic']['user_id'];
							$conditions = array('conditions' => array(
											'ContentPaper.paper_id' => $this->id,
											'ContentPaper.user_id' => $userId,
											'ContentPaper.category_id' => null));

							if($categoryId > 0){
								//add category
								$conditions['conditions']['ContentPaper.category_id'] = $categoryId;
							}



							$checkUser = $contentPaper->find('all', $conditions);



							if(isset($checkUser[0]['ContentPaper']['id'])){
								//user is already in paper
								//$this->Session->setFlash(__('The owner of this topic is already in this category ', true));
								//$this->redirect(array('action' => 'index'));
								return false;
							}
							//topics user is not in the paper -> can add topic to paper / category
							return true;


						}
						else{
							//$this->Session->setFlash(__('Error! user for topic could not be loaded', true));
							//$this->redirect(array('action' => 'index'));
							return false;
						}

					}


				}


				return true;

			}


			/**
			 * associate content to paper
			 *
			 * @param int $categoryId
			 * @param int $userId
			 * @param int $topicId
			 *
			 * @return boolean
			 */
			public function newContentForPaper($categoryId, $userId, $topicId){

				if(!$this->_canAssociateDataToPaper($categoryId, $userId, $topicId)){
						
					return false;
				}

				//$this->ContentPaper->find('all', )

				//this->_saveNewContent(paper, ....)
				$data = array();
				$data['ContentPaper'] = array();
				$data['ContentPaper']['paper_id'] 	= $this->id;
				$data['ContentPaper']['category_id'] 	= $categoryId;
				$data['ContentPaper']['user_id'] 		= $userId;
				$data['ContentPaper']['topic_id'] 	= $topicId;

				App::import('model','ContentPaper');
				$contentPaper = new ContentPaper();
				$contentPaper->create();

				return $contentPaper->save($data);
			}

			/**
			 *
			 * associate contenet (user or topic) to a paper
			 *
			 * @param int $user_id
			 * @param int $topic_id
			 */
			private function _associateContentForPaper($data, $user_id, $topic_id){

				$paper_id = $data['Paper']['target_id'];
				if($this->newContentForPaper(null, $user_id, $topic_id)){
						
					//$this->Session->setFlash(__('content was associated to paper', true));
					//$this->redirect(array('action' => 'index'));
					return true;
				}
				return false;
			}



			/**
			 * get $data and checks if is type
			 * paper or category for paper_content
			 *
			 * @return boolean
			 */
			public function isValidTargetType($targetType){
				return (($targetType == ContentPaper::PAPER) ||
				($targetType == ContentPaper::CATEGORY));
			}

			/**
			 * get $data and checks if is type
			 * paper or category for paper_content
			 *
			 * @return boolean
			 */
			public function isValidSourceType($sourceType){

				return (($sourceType == ContentPaper::USER) ||
				($sourceType == ContentPaper::TOPIC));
			}



			/**
			 * @todo move to abstract for all models
			 * Enter description here ...
			 */
			private function removeFieldsForIndex($data){
				unset($data['Paper']['url']);
				unset($data['Paper']['modified']);
				unset($data['Paper']['created']);
				unset($data['Paper']['owner_id']);
				unset($data['Paper']['count_subscriptions']);
				unset($data['Paper']['route_id']);



				return $data;
			}

			function delete($id){
				$this->removeUserFromSolr($id);
				return parent::delete($id);
			}

			/**
			 * remove the user from solr index
			 *
			 * @param string $id
			 */
			function removeUserFromSolr($id){
				App::import('model','Solr');
				$solr = new Solr();
				$solr->delete(Solr::TYPE_PAPER . '_' . $id);
			}
}
?>