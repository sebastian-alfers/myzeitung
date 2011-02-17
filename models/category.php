<?php
class Category extends AppModel {
	var $name = 'Category';
	var $displayField = 'name';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Paper' => array(
			'className' => 'Paper',
			'foreignKey' => 'paper_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Parent' => array(
			'className' => 'Category',
			'foreignKey' => 'parent_id'
		)		
	);
	
	var $hasOne = array(
		'Route' => array(
			'className' => 'Route',
			'foreignKey' => 'ref_id',//important for FK
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	
	var $hasMany = array(
		'Children' => array(
			'className' => 'Category',
			'foreignKey' => 'parent_id',
		)
	);

}
?>