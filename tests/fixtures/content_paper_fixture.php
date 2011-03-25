<?php
/* ContentPaper Fixture generated on: 2011-03-23 20:39:58 : 1300909198 */
class ContentPaperFixture extends CakeTestFixture {
	var $name = 'ContentPaper';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'paper_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'category_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'topic_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'paper_id' => 1,
			'category_id' => 1,
			'user_id' => 1,
			'topic_id' => 1
		),
	);
}
?>