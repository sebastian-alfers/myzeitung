<?php
class Helppage extends AppModel {
	var $name = 'Helppage';

	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $hasMany = array(
		'Helpelement' => array(
			'className' => 'Helpelement',
			'foreignKey' => 'page_id',
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
