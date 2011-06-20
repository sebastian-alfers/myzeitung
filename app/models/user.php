<?php
class User extends AppModel {

	//field-validation in constructor -> otherwise it's not possible to use "__('translate this', true)" in error messages.

	var $name = 'User';
	var $displayField = 'name';

	var $ContentPaper = null;

	var $updateSolr = false;
	var $uses = array('Route', 'Cachekey');

	const DEFAULT_USER_IMAGE 	= 'assets/default-user-image.jpg';


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
			
		$this->validate = array(

			'name' => array(
				'maxlength' => array(
					'rule'			=> array('maxlength', 30),
					'message'		=> __('Names can only be 30 characters long.', true),
					'last' 			=> true,
				),
			),
			'description' => array(
				'maxlength' => array(
					'rule'			=> array('maxlength', 200),
					'message'		=> __('Your description can only be 200 characters long.', true),
					'last'			=> true,
				),
			),
			
			'email' => array(
				'empty' => array(
					'rule'			=> 'notEmpty',
					'message' 		=> __('Please enter your email adress.', true),
					'last' 			=> true,
				),
				'email' => array(
					'rule'			=> array('email'),
					'message'		=> __('Please enter a valid email address.', true),
					'last' 			=> true,
				),
				'unique' => array(
					'rule'			=> 'isUnique',
					'message'		=> __('This email address has already been registered.', true),
					'last' 			=> true,
				),
			),
			
			'username' => array(
				'empty' => array(
					'rule' 			=> 'notEmpty',
					'message' 		=> __('Please enter your desired username.', true),
					'last' 			=> true,
				),
				'length' => array(
					'rule'			=> array('between', 3, 15),
					'message'		=> __('Usernames must be between 3 and 15 characters long.', true),
					'last'			=> true,
				),
				'alpha' => array(
					'rule'			=> 'alphaNumeric',
					'message'		=> __('Usernames must only contain letters and numbers.', true),
					'last'			=> true,	
				),
	    		'unique' => array(
					'rule'			=> 'isUnique',
					'message'		=> __('This username has already been taken.', true),
					'last'			=> true,
				),
			),
			//the auth component hashes the pwd before the save method - before validation. so we are using a temp field for validating first.

			'passwd' => array(
				'length' => array(
					'rule' 			=> array('between', 5, 20),
					'message'		=> __('Passwords must be between 5 and 20 characters long.', true),
					'last'			=> true,
				),
			),
			'passwd_confirm' => array (  
                'match' =>  array(  
                    'rule'          => 'validatePasswdConfirm',   
                    'message'       => __('Passwords do not match', true),  
					'last'			=> true,
                )  
            ),
            'old_password' => array(
				'length' => array(
					'rule' 			=> 'validateOldPassword',
					'message'		=> __('Your old password does not match.', true),
					'last'			=> true,
				),
			),
            'tos_accept' => array (  
                'match' =>  array(  
                    'rule'          => array('equalTo', '1'),
                    'message'       => __('Please accept the terms of service and privacy policy.', true),  
					'last'			=> true,
                )  
            ) 
		);		
	}
	
   function validatePasswdConfirm($data)  
    {  
        if ($this->data['User']['passwd'] !== $data['passwd_confirm'])  
        {  
            return false;  
        }  
        return true;  
    }  
    
    function validateOldPassword($data)  
    {  
        if (Security::hash($this->data['User']['old_password'], null, true) !== $this->data['User']['password'])  
        {  
            return false;  
        }  
        return true;  
    }    

//		function afterFind($results){
			
			
//			//adding default user image to users without an image
//			if(isset($results['image'])){
//				if(empty($results['image'])){
//					$results['image'] =self::DEFAULT_USER_IMAGE;
//				}
//			} else {
//
//				foreach($results as $key => $val) {
//					if(isset($val['User'])){
//						if(isset($val['User']['image'])){
//							if(empty($val['User']['image'])){
//								$results[$key]['User']['image'] = self::DEFAULT_USER_IMAGE;
//							}
//						} else {
//							$results[$key]['User']['image'] = self::DEFAULT_USER_IMAGE;
//						}
//					}
//					if(isset($val['image'])){
//						if(empty($val['image'])){
//							$results[$key]['image'] = self::DEFAULT_USER_IMAGE;
//						}
//					} else {
//
//						if(isset($results[$key]['User']['image'])){
//							$results[$key]['User']['image'] = self::DEFAULT_USER_IMAGE;
//						}
//					}
//				}
//				if(isset($val['image'])){
//					if(empty($val['image'])){
//						$results[$key]['image'] = self::DEFAULT_USER_IMAGE;
//					}
//				} else {
//					$results[$key]['image'] = self::DEFAULT_USER_IMAGE;
//				}
//			}
//			if(isset($results['User'])){
//				if(isset($results['image'])){
//					if(empty($results['image'])){
//						$results['image'] = self::DEFAULT_USER_IMAGE;
//					}
//				} else {
//					$results['image'] = self::DEFAULT_USER_IMAGE;
//				}
//			}
//			
//
//			debug($results);
//			die();			
//
//			return $results;
//		}

		/**
		 *	hook into save process
		 * 
		 */
		function beforeSave(){
			if(!empty($this->data['User']['image']) && is_array($this->data['User']['image']) && !empty($this->data['User']['image'])){
				$this->data['User']['image'] = serialize($this->data['User']['image']);
			}
			// to prevent hashing before validation: temp field passwd is used
		    if (isset($this->data['User']['passwd'])){  
				$this->data['User']['password'] = Security::hash($this->data['User']['passwd'], null, true);  
				unset($this->data['User']['passwd']);  
    		}
			
			if (isset($this->data['User']['passwd_confirm']))  
			{  
				unset($this->data['User']['passwd_confirm']);  
			}  
			if (isset($this->data['User']['old_password']))  
			{  
				unset($this->data['User']['old_password']);  
			}  
			if (isset($this->data['User']['tos_accept']))  
			{  
				unset($this->data['User']['tos_accept']);  
			}  
			
			return true;
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
				$this->Comment->save($comment);
			}
			
			App::import('model','ConversationMessage');
			$this->ConversationMessage = new ConversationMessage();
				
			// reading all conversationmessages of the deleted user and reseting the user_id to null
			// "message from -deleted user-"
			$this->ConversationMessage->contain();
			$messages = $this->ConversationMessage->findAllByUser_id($this->id);
			foreach($messages as $message){
				$message['ConversationMessage']['user_id']= null;
				$this->ConversationMessage->save($message);
			}
			
			
			App::import('model','ConversationUser');
			$this->ConversationUser = new ConversationUser();
			// reading all conversationusers of the deleted user and reseting the user_id to null
			// "message between X,Y and deleted user"
			$this->ConversationUser->contain();
			$conversationusers = $this->ConversationUser->findAllByUser_id($this->id);
			foreach($conversationusers as $conversationuser){
				$conversationuser['ConversationUser']['user_id']= null;
				$conversationuser['ConversationUser']['status']= Conversation::STATUS_REMOVED;
				$this->ConversationUser->save($conversationuser);
			}
			
			return true;
				
		}
		/**
		 * 1.update solr index

		 */
		function afterSave(){
			if($this->updateSolr){
				//update solr index
				App::import('model','Solr');
	
				$this->data['User']['index_id'] = Solr::TYPE_USER.'_'.$this->id;
				$this->data['User']['type'] = Solr::TYPE_USER;
	
				if(!isset($this->data['User']['id'])){
					if($this->id){
						$this->data['User']['id'] = $this->id;
					}
						
				}
					
				$this->data['User']['user_name'] = $this->data['User']['name'];
				$this->data['User']['user_username'] = $this->data['User']['username'];
				$this->data['User']['user_id'] = $this->data['User']['id'];
				
				$solr = new Solr();
				$solr->add($this->addFieldsForIndex($this->data));
	
				/*
				 App::import('model','Cachekey');
				 $cachekey = new Cachekey();
				 $cachekey->create();
				 if ($cachekey->save(array('old_key' => 123, 'new_key' => 1234))) {}
				 //App::import('model','Route');
				 //$this->save(array('route_id', $route->id));
				 */

			}
		}

		function getWholeUserReferences($user_id){
			App::import('model','ContentPaper');
			App::import('model','Topic');
			$wholeUserReferences = array();
			$conditions = array('conditions' => array('ContentPaper.user_id' => $user_id));
			//$this->ContentPaper->recursive = 0;

			$this->ContentPaper = new ContentPaper();
			$this->ContentPaper->contain('Paper', 'Category');
			$wholeUserReferences = $this->ContentPaper->find('all', $conditions);
			return $wholeUserReferences;
		}

		function getUserTopicReferences($user_id){
			$topicReferences = array();

			//get all users topics
			$this->Topic = new Topic();
			$this->Topic->contain();
			$topics = $this->Topic->find('list', array('conditions' => array('Topic.user_id' => $user_id)));

			foreach($topics as $topid_id => $topic_name){
				$conditions = array('conditions' => array('ContentPaper.topic_id' => $topid_id));
				//$this->ContentPaper->recursive = 0;
				$this->ContentPaper->contain('Paper', 'Category', 'Topic');
				$topicRef = $this->ContentPaper->find('all', $conditions);
				if(isset($topicRef[0]['ContentPaper']['id']) && !empty($topicRef[0]['ContentPaper']['id'])){
					$topicReferences[] = $topicRef[0];
				}

			}
			return $topicReferences;
		}

		function addFieldsForIndex($data){
				
			$solrFields = array();
				
			$solrFields['User']['id'] = $data['User']['id'];
			$solrFields['User']['user_username'] = $data['User']['user_username'];
			$solrFields['User']['user_name'] = $data['User']['user_name'];
			$solrFields['User']['type'] = $data['User']['type'];
			$solrFields['User']['user_id'] = $data['User']['user_id'];
			$solrFields['User']['index_id'] = $data['User']['index_id'];
			if(isset($data['User']['image'])){
				$solrFields['User']['user_image'] = $data['User']['image'];
			}
			return $solrFields;
				
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