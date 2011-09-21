<?php

/**
 * access to session helper methods
 *
 * @author sebastianalfers
 *
 */
class MzSessionComponent extends Object {

    var $components = array('Session', 'Cookie', 'Settings');

    var $_setting = null;

    function __construct(){
        parent::__construct();

        App::import('Model', 'Setting');
        $this->_setting = new Setting();
    }


    function getUserSettings(){

        if(!$this->Session->read('Auth.User.id')) return array();

        if($this->Session->read('Auth.User.Setting')){
            return $this->Session->read('Auth.User.Setting');
        }

        $user_id = $this->Session->read('Auth.User.id');
        $data = $this->_setting->find('all', array('conditions' => array('Setting.model_type' => 'user', 'Setting.model_id = '. $user_id)));

        $settings = array();
        //prepare settings
        foreach($data as $sett){

            $namespace = $sett['Setting']['namespace'];
            $key = $sett['Setting']['key'];
            $settings[$namespace][$key] = $sett['Setting']['value'];
        }
        $this->Session->write('Auth.User.Setting', $settings);

        $locale = $settings[Setting::NAMESPACE_DEFAULT][Setting::KEY_LOCALE];
        if($this->Cookie->read('lang') != $locale){
            $this->Session->write('Config.language', $locale);
            $this->Cookie->write('lang', $locale, false, '20 days');
        }

        return $settings;
    }


    function setLocale($locale){


        $this->Session->write('Config.language', $locale);
        $this->Cookie->write('lang', $locale, false, '20 days');


        if($this->Session->read('Auth.User.id') && $this->Session->read('Auth.User.Setting')){
            $user_id = $this->Session->read('Auth.User.id');
            $this->Settings->save(Setting::MODEL_TYPE_USER, Setting::NAMESPACE_DEFAULT, array(Setting::KEY_LOCALE => $locale), $user_id);

            $this->Session->write('Auth.User.Setting.'.Setting::NAMESPACE_DEFAULT.'.'.Setting::KEY_LOCALE, $locale);
        }

    }


}

?>