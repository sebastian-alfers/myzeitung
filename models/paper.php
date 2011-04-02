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
			'dependent' => true,
			'conditions' => array('parent_id' => 0),//IMPORTANT! to avoid show sub-category in root
			//	'dependent' => false
			),
		'Subscription' => array(
			'className' => 'Subscription',
			'foreignKey' => 'paper_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
			),
		'ContentPaper' => array(
			'className' => 'ContentPaper',
			'foreignKey' => 'paper_id',
			'dependent' => true,
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

			if($this->data['Paper']['owner_id'] != $user_id){
				//paper is not from subscribing user
				//creating subscription
				$this->Subscription->create();
				if($this->Subscription->save($subscriptionData)){
					//subscription was saved
					$this->data['Paper']['count_subscriptions'] += 1;
					$this->save($this->data['Paper']);
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

		if($this->data['Paper']['owner_id'] != $user_id){
				
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
				$this->data['Paper']['count_subscriptions'] -= 1;
				$this->save($this->data);
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
			 * @todo alf: hier wird glaub ich jedes mal nen satz erzeugt. stattdessen m�sste ein Satz bei created angelegt werden und bei nicht created ge-updatet, bzw gel�scht und neu angelegt werden
			 */
			//get User information
			$user = new User();
				
			$userData = $user->read(null, $this->data['Paper']['owner_id']);
			$this->data['Paper']['index_id'] = 'paper_'.$this->id;
			$this->data['Paper']['id'] = $this->id;
			$this->data['Paper']['type'] = Solr::TYPE_PAPER;

			$this->data['Paper']['user_id'] = $userData['User']['id'];
			$this->data['Paper']['user_name'] = $userData['User']['username'];
			$solr = new Solr();
			$solr->add($this->removeFieldsForIndex($this->data));
			
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