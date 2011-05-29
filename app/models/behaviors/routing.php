<?php

class RoutingBehavior extends ModelBehavior {

	private $_params = null;
	
	var $uses = array('route');
	
	function setup(&$Model, $settings) {
	}

	function routeTo($params){
		$this->_params = $params;

		if(true){//$this->_validateParams()
			//save model
			$this->route->create();
			if($this->route->create(array('source' => 'zzz', 'target_controller' => 'derrrr',  'target_action' => 'aaa', 'target_param' => '23', 'parent_id' => '9', ))){
				
			}
			else{
				
			}
			
		}
		else{
			//error while validating params
		}
	}

	private function _validateParams(){
		if($this->_params != null){
			if(!empty($this->_params['source']) &&
			!empty($this->_params['target_controller']) &&
			!empty($this->_params['target_action']) &&
			!empty($this->_params['target_param']) &&
			!empty($this->_params['parent_id'])){
				//valid params
				//save route
				
			}
			else{
				//error while validating params
				
			}
		}
	}


}

