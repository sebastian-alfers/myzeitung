<?php
class User extends AppModel {
	var $name = 'User';
	var $displayField = 'name';
	
	
	var $actsAs = array('Containable');
	
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
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $hasMany = array(
	/*'Post' => array(
	 'className' => 'Post',
	 'foreignKey' => 'user_id',
	 'dependent' => false,
	 'conditions' => '',
	 'fields' => '',
	 'order' => '',
	 'limit' => '',
	 'offset' => '',
	 'exclusive' => '',
	 'finderQuery' => '',
	 'counterQuery' => ''
		),*/
	
		'Topic' => array(
			'className' => 'Topic',
			'foreignKey' => 'user_id',
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


			var $hasAndBelongsToMany = array(
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
		
		function afterSave(){
			/*
			 App::import('model','Cachekey');
			 $cachekey = new Cachekey();
			 $cachekey->create();
			 if ($cachekey->save(array('old_key' => 123, 'new_key' => 1234))) {}
			 */

			//App::import('model','Route');

			//$this->save(array('route_id', $route->id));

		}
}
?>