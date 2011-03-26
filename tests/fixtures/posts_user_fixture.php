<?php
/* PostsUser Fixture generated on: 2011-03-23 20:39:59 : 1300909199 */
class PostsUserFixture extends CakeTestFixture {
	var $name = 'PostsUser';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'repost' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 4),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'topic_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'post_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'repost' => 1,
			'user_id' => 1,
			'topic_id' => 1,
			'post_id' => 1,
			'created' => '2011-03-23 20:39:59'
		),
	);
}
?>