<?php
class Route extends AppModel {
	var $name = 'Route';


    const TYPE_POST = 'POST';
    const TYPE_PAPER = 'PAPER';

    const TYPE_NEW_ROUTE = 'NEW_ROUTE_';
    const TYPE_OLD_ROUTE_ID = 'OLD_ROUTE_';
   // const TYPE_USER = 'USER';







	var $validate = array(

	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'ParentRoute' => array(
			'className' => 'Route',
			'foreignKey' => 'parent_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	var $hasMany = array(
		'ChildRoute' => array(
			'className' => 'Route',
			'foreignKey' => 'parent_id',
			'dependent' => false,
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
     //moved to post and paper model
      //  $this->deleteRouteCache();

    }
    function deleteRouteCache($source){
        $cache = ClassRegistry::init('cache');
        $cache->delete('url_'.$source);
    }
  /*  function updateRouteCache(){
        $cache = ClassRegistry::init('cache');
        $cache->update('url_'.$this->data['source'], $this->data);
    }*/
 /*   function klatschMirRoutenRein(){
        $this->log('klatsch');
        for($i=0;$i <= 999999;$i++){
            $this->create();
            $this->data['Route'] = array('source' => '/test/'.$i,
                                         'target_controller' => 'users',
                                         'target_action' => 'view',
                                          'target_param' => 6,
                                        'ref_id' =>'USER_6');
           $this->save($this->data);
            if($i == 0){
            $this->log('schleife');
            }
        }
    }*/

}
