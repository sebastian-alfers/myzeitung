<?php
class Topic extends AppModel {
	var $name = 'Topic';
	var $displayField = 'name';

	
	var $validate = array(
		'name' => array(
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
		)
	);

	var $hasMany = array(
		'Post' => array(
			'className' => 'Post',
			'foreignKey' => 'topic_id',
			//if a topic is being deleted, the posts of this topic won't be deleted.
			//-> afterdelete callback resets the topic of all posts of this deleted topic to null
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
	
	function beforeDelete(){
		App::import('model','Post');
		$this->Post = new Post();
		
		$posts = $this->Post->find('All',array('conditions' => array('topic_id' => $this->id)));
		foreach($posts as $post){
			$post['Post']['topic_id'] = null;
			$this->Post->save($post);
		}
		
		
		
	}

}
?>