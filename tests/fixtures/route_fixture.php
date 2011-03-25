<?php
/* Route Fixture generated on: 2011-03-23 20:39:59 : 1300909199 */
class RouteFixture extends CakeTestFixture {
	var $name = 'Route';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'ref_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'source' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'target_controller' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'target_action' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'target_param' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 20, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'parent_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'ref_id' => 1,
			'source' => 'Lorem ipsum dolor sit amet',
			'target_controller' => 'Lorem ipsum dolor sit amet',
			'target_action' => 'Lorem ipsum dolor sit amet',
			'target_param' => 'Lorem ipsum dolor ',
			'parent_id' => 1
		),
	);
}
?>