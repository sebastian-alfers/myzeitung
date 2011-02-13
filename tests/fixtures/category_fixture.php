<?php
/* Category Fixture generated on: 2011-02-12 17:45:48 : 1297529148 */
class CategoryFixture extends CakeTestFixture {
	var $name = 'Category';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'paper_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'route_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'unique'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'route_id' => array('column' => 'route_id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'parent_id' => 1,
			'paper_id' => 1,
			'route_id' => 1,
			'name' => 'Lorem ipsum dolor sit amet'
		),
	);
}
?>