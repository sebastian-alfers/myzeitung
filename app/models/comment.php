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
			'order' => '',
			'counterCache' => true,
            'counterScope' => array('Comment.enabled' => true),

		),
		'Post' => array(
			'className' => 'Post',
			'foreignKey' => 'post_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'counterCache' => true,
            'counterScope' => array('Comment.enabled' => true),
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
	
    function disable(){

        if($this->data['Comment']['enabled'] == true){
            //disable Comment
            $this->data['Comment']['enabled'] = false;
            $this->save($this->data);


            return true;
        }
        //already disabled
        return false;
    }
    function enable(){

        if($this->data['Comment']['enabled'] == false){
            //delete all posts_users entries with cascading and callbacks

            //disable Comment
            $this->data['Comment']['enabled'] = true;

            $this->save($this->data);

            return true;
        }
        //already enabled
        return false;
    }


}
?>