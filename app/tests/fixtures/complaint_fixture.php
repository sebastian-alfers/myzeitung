<?php
/* Complaint Fixture generated on: 2011-07-12 10:35:56 : 1310459756 */
class ComplaintFixture extends CakeTestFixture {
	var $name = 'Complaint';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'paper_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'post_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'comment_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'reason_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 3),
		'comments' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'reporter_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'reporter_email' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'complaintstatus_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 3),
		'created' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'updated' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'paper_id' => 1,
			'post_id' => 1,
			'comment_id' => 1,
			'user_id' => 1,
			'reason_id' => 1,
			'comments' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'reporter_id' => 1,
			'reporter_email' => 'Lorem ipsum dolor sit amet',
			'complaintstatus_id' => 1,
			'created' => 1,
			'updated' => 1
		),
	);
}
