<?php

/**
 * provides different helper methods for uploadging
 *
 * @author sebastianalfers
 *
 */
class SettingsComponent extends Object {

	var $components = array('Session');


    function get($model_id = '', $namespace = ''){

        $ses = $this->Session->read('Auth.User');

        if(!isset($ses['Settings']) || empty($ses['Settings'])){
            $this->loadSettings($model_id);
        }

        return $this->Session->read('Auth.User.Settings');
    }

    function save($model, $namespace, $values, $model_id = ''){

        if($model_id == ''){
            $model_id = $this->Session->read('Auth.User.id');
        }

        App::Import('Model', 'Setting');
        $settings = new Setting();
        $settings->contain();

        $conditions = array();
        foreach($values as $key => $value){
            $conditions['Setting.model_type'] = $model;
            $conditions['Setting.model_id'] = $model_id;
            $conditions['Setting.namespace'] = $namespace;
            $conditions['Setting.key'] = $key;


            $data = $settings->find('all', array('conditions' => $conditions));

            if(!isset($data['Setting']['id'])){
                $settings->create();
                $data['Setting'] = array('model_type' => $model,
                                         'model_id' => $model_id,
                                         'namespace' => $namespace,
                                         'key' => $key);
            }

            $data['Setting']['value'] = $value;



            $settings->save($data);
        }

    }

    function loadSettings($id = ''){

        if($id == ''){
            $id = $this->Session->read('Auth.User.id');
        }

        App::import('Model', 'User');
        $user = new User();
        $user->contain('Setting');
        $data = $user->read(array(), $id);

        //transform to key=>value list
        $settings = array();

        if(isset($data['Setting']) && count($data['Setting']) > 0){
            foreach($data['Setting'] as $setting){
                if(isset($setting['namespace']) && !empty($setting['namespace'])){
                    $settings[$setting['namespace']][$setting['key']]  = $setting['value'];
                }
                else{
                    $settings[$setting['key']]  = $setting['value'];
                }

            }
        }

        $this->Session->write('Auth.User.Settings', $settings);
    }

    /**
     * remove twitter from session and settings in db
     *
     * @return void
     */
    function removeTwitter(){
        $this->Session->delete('Auth.User.Settings.twitter');

        App::Import('Model', 'Setting');
        $settings = new Setting();
        $settings->contain();

        $conditions = array('Setting.model_type' => 'User', 'Setting.model_id' => $this->Session->read('Auth.User.id'),
                           'Setting.namespace' => 'twitter');

        $settings->deleteAll($conditions);

    }
}



?>