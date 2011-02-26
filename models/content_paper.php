<?php
class ContentPaper extends AppModel {
	var $name = 'ContentPaper';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	const PAPER 	= 'paper';
	const CATEGORY 	= 'category';
	const USER 		= 'user';
	const TOPIC 	= 'topic';
	const CONTENT_DATA = 'content_data';

	//to concatinate for frontend e.g. category_#id (category_44)
	const SEPERATOR = '_';


	var $belongsTo = array(
		'Paper' => array(
			'className' => 'Paper',
			'foreignKey' => 'paper_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
			),
		'Category' => array(
			'className' => 'Category',
			'foreignKey' => 'category_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
			),
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
}
?>