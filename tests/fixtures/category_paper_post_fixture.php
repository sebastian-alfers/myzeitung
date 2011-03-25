<?php
/* CategoryPaperPost Fixture generated on: 2011-03-23 20:39:57 : 1300909197 */
class CategoryPaperPostFixture extends CakeTestFixture {
	var $name = 'CategoryPaperPost';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'post_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'paper_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'category_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'post_id' => 1,
			'paper_id' => 1,
			'category_id' => 1,
			'created' => '2011-03-23 20:39:57'
		),
	);
}
?>