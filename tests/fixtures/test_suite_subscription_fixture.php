<?php
/* TestSuiteSubscription Fixture generated on: 2011-03-23 20:40:00 : 1300909200 */
class TestSuiteSubscriptionFixture extends CakeTestFixture {
	var $name = 'TestSuiteSubscription';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'paper_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'paper_id' => 1,
			'user_id' => 1
		),
	);
}
?>