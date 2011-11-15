<?php
class RssItem extends AppModel {
	var $name = 'RssItem';

	var $validate = array(
		'hash' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
/*
    var $hasAndBelongsToMany = array(
        'RssFeed' =>
            array(
                'className'              => 'RssFeed',
                'joinTable'              => 'rss_feeds_items',
                'foreignKey'             => 'item_id',
                'associationForeignKey'  => 'feed_id',
                'unique'                 => true,
                'conditions'             => '',
                'fields'                 => '',
                'order'                  => '',
                'limit'                  => '',
                'offset'                 => '',
                'finderQuery'            => '',
                'deleteQuery'            => '',
                'insertQuery'            => ''
            )
    );
*/
    var $hasMany = array(
		'RssItemContent' => array(
                'className' => 'RssItemContent',
                'foreignKey' => 'item_id',
                'dependent' => true,
                'conditions' => '',
                'fields' => '',
                'order' => '',
                'limit' => '',
                'offset' => '',
                'exclusive' => '',
                'finderQuery' => '',
                'counterQuery' => ''
			),
    );




}
