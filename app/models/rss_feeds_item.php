<?php
class RssFeedsItem extends AppModel {
	var $name = 'RssFeedsItem';
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
		'item_id' => array(
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

    
    function beforeDelete(){
        //check if there are any other feeds associated to this item. if not. delete item
        $this->contain();
        $feed_item = $this->read(null, $this->id);

        if(isset($feed_item['RssFeedsItem']['item_id'])){

            $this->contain();
            if(!($this->find('count', array('conditions' => array('item_id' => $feed_item['RssFeedsItem']['item_id']))) > 1)){
                // this is the last association to this item -> item must be deleted.
               App::import('model','RssItem');
               $this->RssItem = new RssItem();
               $this->RssItem->delete($feed_item['RssFeedsItem']['item_id'], true);
            }
        }
        return true;
    }
}
