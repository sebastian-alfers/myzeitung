<?php
class Route extends AppModel {
	var $name = 'Route';
	//var $displayField = 'name';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	function afterSave(){
		$cache = ClassRegistry::init('cache');
		$cache->delete('routes');
		/*
		App::import('model','Cachekey');
		$cachekey = new Cachekey();
		$routeKey = $cachekey->findByKey('routes');
		if($routeKey === false){
			
			$cachekey->create();
			$data = array('key' => 'routes',  'new_key' => md5(mktime()));
			$data['old_key'] =  md5(mktime());
			$cachekey->save($data);
		}
		else{
			$routeKey['Cachekeys']['new_key'] = md5(mktime());
			$cachekey->save($routeKey);
		}
		*/




		//$parentRoute = $menusModel->findById($menuitem['Route']['parent_id']);

	}
}
?>