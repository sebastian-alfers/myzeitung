<?php
class RssFeedsUser extends AppModel {
	var $name = 'RssFeedsUser';
	var $useDbConfig = 'local';
	var $validate = array(
		'feed_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'RssFeed' => array(
			'className' => 'RssFeed',
			'foreignKey' => 'feed_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);


    /*
    function beforeDelete(){
        //check if there are any other users associated to this feed. if not. delete feed
        $this->contain();
        $feed_user = $this->read(null, $this->id);
        debug('f');
        if(isset($feed_user['RssFeedsUser']['feed_id'])){
            $this->contain();
            debug('g');
            if(!($this->find('count', array('conditions' => array('feed_id' => $feed_user['RssFeedsUser']['feed_id']))) > 1)){
                // this is the last association to this feed -> feed must be deleted.
                debug('h');
               App::import('model','RssFeed');
               $this->RssFeed = new RssFeed();
               $this->RssFeed->unbindModel(array('hasAndBelongsToMany' => array('RssItem')));
                debug('feed_id');
               debug($feed_user['RssFeedsUser']['feed_id']);
               //$this->RssFeed->delete($feed_user['RssFeedsUser']['feed_id'], true);
            }

        }
        return true;

    }
    */
}
