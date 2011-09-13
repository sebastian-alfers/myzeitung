<?php
class Helpelement extends AppModel {
	var $name = 'Helpelement';
	var $useDbConfig = 'local';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Page' => array(
			'className' => 'Helppage',
			'foreignKey' => 'page_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
