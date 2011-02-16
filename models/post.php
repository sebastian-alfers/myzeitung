<?php
class Post extends AppModel {
	var $name = 'Post';
	var $displayField = 'title';
	
	var $actsAs = array(/*'Serializeable'/* => array('reposters' => 'reposters'),*/'Containable');
	
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

    
}


	
	

?>