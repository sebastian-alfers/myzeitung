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
			'order' => '',
            'counterCache' => true
		)
	);

	var $hasMany = array(
		'Post' => array(
			'className' => 'Post',
			'foreignKey' => 'topic_id',
			//if a topic is being deleted, the posts of this topic won't be deleted.
			//-> beforedelete callback resets the topic of all posts of this deleted topic to null
			'dependent' => false,
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
			'foreignKey' => 'topic_id',
			//if a topic is being deleted, the posts of this topic won't be deleted.
			//-> beforedelete callback resets the topic of all posts of this deleted topic to null
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
	
	function beforeDelete(){

		App::import('model','Post');
		$this->Post = new Post();
		$this->Post->Contain();
		//change all Post's topics that have the deleted topic. the posts_users entry will be update automatically for those
        $posts = $this->Post->findAllByTopic_id($this->id);
		foreach($posts as $post){

			$post['Post']['topic_id'] = null;
            $this->Post->topicChanged= true;
			$this->Post->save($post);
		}
        //change all reposts with the deleted topic id to topic = null;
		App::import('model','PostUser');
        $this->PostUser = new PostUser;

        $this->PostUser->contain();
        $reposts = $this->PostUser->find('all', array('conditions' => array('PostUser.topic_id' => $this->id, 'PostUser.repost' => true)));
        foreach($reposts as $repost){
           //delete old posts_users entry (true = dependent = delete all cascading stuff)
           $this->PostUser->delete($repost['PostUser']['id'], true);             
           //save new entry with no topic and old date (new subscriptions will be created)
           $repost['PostUser']['topic_id'] = null;
           unset($repost['PostUser']['id']);
           $this->PostUser->create();
           $this->PostUser->save($repost);
        }
		return true;
	}


}
?>