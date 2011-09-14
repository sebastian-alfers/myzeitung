<?php
class Helppage extends AppModel {
	var $name = 'Helppage';

	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $hasMany = array(
		'Helpelement' => array(
			'className' => 'Helpelement',
			'foreignKey' => 'page_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

    function afterSave($created){

        // remove helpcenter cache
        $this->deleteCache($this->data);
    }

    function beforeDelete(){
        $data = $this->read(null, $this->id);
        $this->deleteCache($data);
        return true;
    }

    function deleteCache($data){
        $cacheKey = Configure::read('Config.language').'.Helpcenter.'.$data['Helppage']['controller'].'.'.$data['Helppage']['action'];
        Cache::delete($cacheKey);
    }



}
