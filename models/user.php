<?php
class User extends AppModel {
	var $name = 'User';
	var $displayField = 'name';

	var $ContentPaper = null;


	var $uses = array('Route', 'Cachekey');

	var $validate = array(
		'firstname' => array(
			'maxlength' => array(
				'rule' => array('maxlength', 10),
	//'message' => 'Your custom message here',
	//'allowEmpty' => false,
	//'required' => false,
	//'last' => false, // Stop validation after this rule
	//'on' => 'create', // Limit validation to 'create' or 'update' operations
	),
	),
		'name' => array(
			'maxlength' => array(
				'rule' => array('maxlength', 20),
	//'message' => 'Your custom message here',
	//'allowEmpty' => false,
	//'required' => false,
	//'last' => false, // Stop validation after this rule
	//'on' => 'create', // Limit validation to 'create' or 'update' operations
	),
	),
		'email' => array(
			'email' => array(
				'rule' => array('email'),
	//'message' => 'Your custom message here',
				'allowEmpty' => false,
				'required' => false,
	//'last' => false, // Stop validation after this rule
	//'on' => 'create', // Limit validation to 'create' or 'update' operations
	),
	),
		'username' => array(
			'notempty' => array(
				'rule' => array('notempty'),
	//'message' => 'Your custom message here',
	//'allowEmpty' => false,
	//'required' => false,
	//'last' => false, // Stop validation after this rule
	//'on' => 'create', // Limit validation to 'create' or 'update' operations
	),
	),
		'password' => array(
			'maxlength' => array(
				'rule' => array('maxlength', 999),
	//'message' => 'Your custom message here',
	//'allowEmpty' => false,
	//'required' => false,
	//'last' => false, // Stop validation after this rule
	//'on' => 'create', // Limit validation to 'create' or 'update' operations
	),
			'minlength' => array(
				'rule' => array('minlength', 4),
	//'message' => 'Your custom message here',
	//'allowEmpty' => false,
	//'required' => false,
	//'last' => false, // Stop validation after this rule
	//'on' => 'create', // Limit validation to 'create' or 'update' operations
	),
	),
	);


	var $hasMany = array(
	 'Post' => array(
	 	'className' => 'Post',
	 	'foreignKey' => 'user_id',
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
	 'PostUser' => array(
	 	'className' => 'PostUser',
	 	'foreignKey' => 'user_id',
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
	'Topic' => array(
		'className' => 'Topic',
		'foreignKey' => 'user_id',
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
		'foreignKey' => 'user_id',
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
	'Paper' => array(
		'className' => 'Paper',
		'foreignKey' => 'owner_id',
		'dependent' => true,
		'fields' => '',
		'order' => 'subscription_count DESC',
		'limit' => 3,
		'offset' => '',
		'exclusive' => '',
		'finderQuery' => '',
		'counterQuery' => ''
		),
	'Subscription' => array(
		'className' => 'Subscription',
		'foreignKey' => 'user_id',
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


	/*		var $hasAndBelongsToMany = array(
		'Post' => array(
			'className' => 'Post',
			'joinTable' => 'posts_users',
			'foreignKey' => 'user_id',
			'associationForeignKey' => 'post_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => 'PostsUser.created DESC',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
			),
		'Paper' => array(
			'className' => 'Paper',
			'joinTable' => 'subscriptions',
			'foreignKey' => 'user_id',
			'associationForeignKey' => 'paper_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
			),	
			);*/

			var $belongsTo = array(
		'Group'
		);

		var $hasOne = array(
			'Route' => array(
				'className' => 'Route',
				'foreignKey' => 'ref_id',//important to have FK
		),
		);


		function __construct(){
			parent::__construct();
		}
		
		function afterFind($results){
			
			//adding default user image to users without an image
			 foreach($results as $key => $val) {
			 	if(isset($val['User'])){
					if(isset($val['User']['image'])){		
						if(empty($val['User']['image'])){
								$results[$key]['User']['image'] = 'default-user-image.jpg';
						}
					}

			 	}
		 		if(isset($val['image'])){	
					if(empty($val['image'])){
							$results[$key]['image'] = 'default-user-image.jpg';
					}
				} 
			 }
			 if(isset($results['User'])){
			 	if(empty($results['image'])){
			 		$results['image'] = 'default-user-image.jpg';
			 	}
			 }
			return $results;
		}

		function beforeDelete(){
			App::import('model','Comment');
			$this->Comment = new Comment();
			
			// reading all comments of the deleted user and reseting the user_id to null
			// "comment from -deleted user-"
			$this->Comment->contain();
			$comments = $this->Comment->findAllByUser_id($this->id);
			foreach($comments as $comment){
			 	$comment['Comment']['user_id']= null;
			    debug($comment);
				$this->Comment->save($comment);
			}
			
		return true;
			
		}
		/**
		 * update index
		 */
		function afterSave(){

			App::import('model','Solr');

			$this->data['User']['index_id'] = Solr::TYPE_USER.'_'.$this->id;
			$this->data['User']['type'] = Solr::TYPE_USER;
				
			if(!isset($this->data['User']['id'])){
				if($this->id){
					$this->data['User']['id'] = $this->id;	
				}
					
			}
			
			$this->data['User']['user_name'] = $this->data['User']['name'];
			$this->data['User']['lastname'] = $this->data['User']['name'];
			$this->data['User']['user_id'] = $this->data['User']['id'];
			
			$solr = new Solr();
			$solr->add($this->removeFieldsForIndex($this->data));

			/*
			 App::import('model','Cachekey');
			 $cachekey = new Cachekey();
			 $cachekey->create();
			 if ($cachekey->save(array('old_key' => 123, 'new_key' => 1234))) {}
			 //App::import('model','Route');
			 //$this->save(array('route_id', $route->id));
			 */



		}

		function getWholeUserReferences($user_id){
			App::import('model','ContentPaper');
			App::import('model','Topic');
			$wholeUserReferences = array();
			$conditions = array('conditions' => array('ContentPaper.user_id' => $user_id));
			//$this->ContentPaper->recursive = 0;

			$this->ContentPaper = new ContentPaper();
			$wholeUserReferences = $this->ContentPaper->find('all', $conditions);
			return $wholeUserReferences;
		}

		function getUserTopicReferences($user_id){
			$topicReferences = array();

			//get all users topics
			$this->Topic = new Topic();
			$topics = $this->Topic->find('list', array('conditions' => array('Topic.user_id' => $user_id)));
			foreach($topics as $topid_id => $topic_name){
				$conditions = array('conditions' => array('ContentPaper.topic_id' => $topid_id));
				//$this->ContentPaper->recursive = 0;
				$topicRef = $this->ContentPaper->find('all', $conditions);
				if(isset($topicRef[0]['ContentPaper']['id']) && !empty($topicRef[0]['ContentPaper']['id'])){
					$topicReferences[] = $topicRef[0];
				}

			}
			return $topicReferences;
		}

		function removeFieldsForIndex($data){		
			unset($data['User']['email']);
			unset($data['User']['password']);
			unset($data['User']['created']);
			unset($data['User']['modified']);
			unset($data['User']['lastlogin']);
			unset($data['User']['enabled']);
			unset($data['User']['group_id']);
			unset($data['User']['route_id']);
			unset($data['User']['post_count']);
			unset($data['User']['posts_user_count']);
			unset($data['User']['comment_count']);


			


			
			
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
			$solr->delete(Solr::TYPE_USER . '_' . $id);
		}

}
?>