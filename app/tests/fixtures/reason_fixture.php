<?php
/* Reason Fixture generated on: 2011-07-12 10:43:41 : 1310460221 */
class ReasonFixture extends CakeTestFixture {
	var $name = 'Reason';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 3, 'key' => 'primary'),
		'value' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 2255, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'value' => 'Lorem ipsum dolor sit amet'
		),
	);
}
