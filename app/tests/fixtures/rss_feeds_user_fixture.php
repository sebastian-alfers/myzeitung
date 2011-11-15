<?php
/* RssFeedsUser Fixture generated on: 2011-11-10 15:16:26 : 1320934586 */
class RssFeedsUserFixture extends CakeTestFixture {
	var $name = 'RssFeedsUser';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'feed_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'feed_id' => 1,
			'user_id' => 1
		),
	);
}
