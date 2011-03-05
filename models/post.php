<?php
class Post extends AppModel {
	var $name = 'Post';
	var $displayField = 'title';
	
	const TEST = 666;
	
	var $actsAs = array('Containable');
	
	var $CategoryPaperPost = null;
	
	var $validate = array(
		'user_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'content' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Topic' => array(
			'className' => 'Topic',
			'foreignKey' => 'topic_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	var $hasMany = array(
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'post_id',
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

// temp. not necessary

/*	var $hasAndBelongsToMany = array(
		'User' => array(
			'className' => 'User',
			'joinTable' => 'posts_users',
			'foreignKey' => 'post_id',
			'associationForeignKey' => 'user_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);*/

	// CALLBACKS
	
	function afterFind($results) {
		foreach ($results as $key => $val) {
			if (!empty($val['Post']['reposters'])) {
				$results[$key]['Post']['reposters'] = unserialize($results[$key]['Post']['reposters']);
			}else {
				$results[$key]['Post']['reposters'] = array();
			}
		}
		return $results;
	}
	
		
		
	function beforeSave(&$Model) {
		if(!empty($this->data['Post']['reposters'])){
			$this->data['Post']['reposters'] = serialize($this->data['Post']['reposters']);
		}
		return true;
	}
	
	/**
	 * after a topic has been saved, it it hast do be added to CategoryPaperPost table
	 * a post can come to this table bacause:
	 * - the posts user is associated to a paper/category or
	 * - the posts topic is associated to a paper/category
	 * 
	 * so: this function does:
	 * 1. get all associations to the user who created this post (all posts by this user)
	 *  - this can be a paper itself or one of it's categories
	 * 
	 * 2. get all associations to the post's topic
	 *  - this can be paper itself or one of its categories
	 *  
	 * 3. validate collected data
	 *  - it is very important, that a paper (and its categories) contain the post
	 *    only once!
	 * 
	 */
	function afterSave(){
		App::import('model','CategoryPaperPost');
		//debug($this->data);
		$this->CategoryPaperPost = new CategoryPaperPost(); 
		$post_id = $this->id;
		$topic_id = $this->data['Post']['topic_id'];
		$user_id = $this->data['Post']['user_id'];
		
		
		//now all references to whole user
		$wholeUserReferences = $this->User->getWholeUserReferences($user_id);
		foreach($wholeUserReferences as $wholeUserReference){
			if($this->addPostToIndex($post_id, $wholeUserReference['Paper']['id'])){
				
			}
			
			//place post in paper or category associated to the whole user
			$categoryPaperPostData = array('post_id' => $post_id, 'paper_id' => $wholeUserReference['Paper']['id']);
			if($wholeUserReference['Category']['id']){
				$categoryPaperPostData = array('category_id' => $wholeUserReference['Category']['id']);	
			}
			$this->CategoryPaperPost->create();
			$this->CategoryPaperPost->save($categoryPaperPostData);			
		}
		
		//now all references to all topics
		$topicReferences = $this->User->getUserTopicReferences($user_id);
		foreach($topicReferences as $topicReferences){
						//place post in paper or category associated to the posts topic
			$categoryPaperPostData = array('post_id' => $post_id, 'paper_id' => $wholeUserReference['Paper']['id']);
			if($wholeUserReference['Category']['id']){
				$categoryPaperPostData = array('category_id' => $wholeUserReference['Category']['id']);	
			}
			$this->CategoryPaperPost->create();
			$this->CategoryPaperPost->save($categoryPaperPostData);			
		}		
		
		//update index to associate paper->content / category->content


	}
	
	
	private function addPostToIndex($post_id, $paper_id, $categry_id = null){
		
	}
	

    
}


	
	

?>