<?php
class Helpelement extends AppModel {
	var $name = 'Helpelement';

	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Helppage' => array(
			'className' => 'Helppage',
			'foreignKey' => 'page_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
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
