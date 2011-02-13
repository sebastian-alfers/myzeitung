<?php
class Paper extends AppModel {
	var $name = 'Paper';

	var $displayField = 'title';
	//The Associations below have been created with all possible keys, those that are not needed can be removed


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


		
	var $hasMany = array(
		'Category' => array(
			'className' => 'Category',
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

}
?>