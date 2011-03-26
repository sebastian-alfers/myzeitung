<?php
class Paper extends AppModel {
	var $name = 'Paper';
	var $actsAs = array('Containable','Increment'=>array('incrementFieldName'=>'count_subscriptions'));
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
				return $data;
			}

}
?>