<?php

/**
 * provides different helper methods for settings
 *
 * @author sebastianalfers
 *          timwiegard
 *
 */
class SettingsComponent extends Object {

	var $components = array('Session');


    /*
    function get($model_id = '', $namespace = ''){

        $ses = $this->Session->read('Auth');

        if(!isset($ses['Settings']) || empty($ses['Settings'])){
            $this->loadSettings($model_id);
        }

        return $this->Session->read('Auth.Settings');
    }
*/

    /* function save($model, $namespace, $values, $model_id = ''){


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



            if(!isset($data[0]['Setting']['id'])){
                $settings->create();
                $data['Settings'] = array('model_type' => $model,
                                         'model_id' => $model_id,
                                         'namespace' => $namespace,
                                         'key' => $key,
                                         'value' => $value);
            }
            else{
                $data = $settings->read(null, $data[0]['Setting']['id']);
                $data['Setting']['value'] = $value;
            }
            $settings->save($data);
        }

    } */

    /**
     * @param null $settingData
     * @param null $model_id
     * @return bool
     */
    function save($settingData = null, $model_id = null){
        //$this->log($settingData);
        //$this->log($this->Session->read('Auth'));
        if(($settingData == null || !is_array($settingData)) || $model_id == null){
            return false;
        }

        App::Import('Model', 'Setting');
        $this->Setting = new Setting();



        foreach($settingData as $modelType => $modelTypeValue){
            foreach($modelTypeValue as $namespace => $namespaceValue){
                foreach($namespaceValue as $key => $keyValue){
                    if($keyValue['specific_id'] != null){
                        $this->Setting->save(array('id' => $keyValue['specific_id'], 'value' => $keyValue['value']), false, array('value'));
                        $this->log('UPDATE');

                    }else{
                        $this->log('CREATE');
                        $this->Setting->create();

                        $settingData = array('model_type' => $modelType, 'model_id' => $model_id, 'namespace' => $namespace, 'key' => $key, 'value' => $keyValue['value'], 'value_data_type' => $keyValue['value_data_type']);
                        $this->Setting->save($settingData);
                    }
                    $this->log('sessionwrite:  '.'Auth.Setting.'.$modelType.'.'.$namespace.'.'.$key);
                    $this->log($keyValue);
                    $this->Session->write('Auth.Setting.'.$modelType.'.'.$namespace.'.'.$key, $keyValue);
                }
            }
        }


    }

    function loadSettings($id = ''){

        if($id == ''){
            $id = $this->Session->read('Auth.User.id');
        }

        App::import('Model', 'User');
        $user = new User();
        $user->contain();
        $data = $user->getSettings($id);

        //transform to key=>value list
     /*   $settings = array();

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
     */
        $this->Session->write('Auth.Settings', /*$settings*/ $data);
    }

    /**
     * remove twitter from session and settings in db
     *
     * @return void
     */
    function removeTwitter(){
        $settings = $this->Session->read('Auth.Setting.user.twitter');

        $settings['oauth_token']['value'] = '';
        $settings['oauth_token_secret']['value'] = '';

        $id = $this->Session->read('Auth.User.id');

        if($this->save(array('user' => array('twitter' =>$settings)), $id)){

        }

    }
}



?>