<?php
class RssImportLog extends AppModel {
	var $name = 'RssImportLog';
	var $useDbConfig = 'local';
	var $useTable = 'rss_import_log';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'RssFeed' => array(
			'className' => 'RssFeed',
			'foreignKey' => 'rss_feed_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
