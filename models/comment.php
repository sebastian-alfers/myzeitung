<?php
class Comment extends AppModel {
	var $name = 'Comment';
	var $actsAs = array('Tree');
	var $validate = array(
		'post_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
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
		'Post' => array(
			'className' => 'Post',
			'foreignKey' => 'post_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ParentComment' => array(
			'className' => 'Comment',
			'foreignKey' => 'parent_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	var $hasMany = array(
		'ChildComment' => array(
			'className' => 'Comment',
			'foreignKey' => 'parent_id',
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

	function afterSave($created){
		if($created){
			//incrementing the comment-counter in the related post
			App::import('model','Post');
			App::import('model','User');
			$this->Post = new Post();
			// params : id, digit to add, field to increment
			$this->Post->doIncrement($this->data['Comment']['post_id'], 1, 'count_comments');
			
			$this->User = new User();
			$this->User->contain();
			$userData = $this->User->read(null, $this->data['Comment']['user_id']);
			//@todo if redudant : in aftersave and beforedelete
			if($userData['User']['id']){	
				//count users reposts
				$userData['User']['count_comments'] = $this->find('count',array('conditions' => array('Comment.user_id' => $userData['User']['id'])));
				$this->User->save($userData);
			}	
		}
	}
	
	function beforeDelete(){
		$this->contain();
		$this->data = $this->read(null, $this->id);
		$this->Post = new Post();
		// params : id, digit to add, field to increment
		$this->Post->doIncrement($this->data['Comment']['post_id'], -1, 'count_comments');
		
		$this->User = new User();
		$this->User->contain();
		$userData = $this->User->read(null, $this->data['Comment']['user_id']);
		//@todo if redudant : in aftersave and beforedelete
		if($userData['User']['id']){	
			//count users reposts ( "-1 + count" because the 
			$userData['User']['count_comments'] = -1 + $this->find('count',array('conditions' => array('Comment.user_id' => $userData['User']['id'])));
			$this->User->save($userData);
		}	
		return true;
	}
}
?>