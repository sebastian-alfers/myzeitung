<?php

/**
 * access to session helper methods
 *
 * @author sebastianalfers
 *
 */
class MzSessionComponent extends Object {

    var $components = array('Session', 'Cookie', 'Settings');

   // var $_setting = null;

    function __construct(){
        parent::__construct();

     //   App::import('Model', 'Setting');
     //   $this->_setting = new Setting();
    }


    function getUserSettings(){

        if(!$this->Session->read('Auth.User.id')) return array();

        if($this->Session->read('Auth.Setting')){
            return $this->Session->read('Auth.Setting');
        }

        $user_id = $this->Session->read('Auth.User.id');

        App::Import('Model', 'User');
        $this->User = new User();

        $settings = $this->User->getSettings($user_id);
        $this->Session->write('Auth.Setting', $settings);

        return $settings;


      /*  $data = $this->_setting->find('all', array('conditions' => array('Setting.model_type' => 'user', 'Setting.model_id = '. $user_id)));

        if(empty($data)){
            //only for old accounts -> new registered users have default settings
            return array(Setting::NAMESPACE_DEFAULT => array(Setting::KEY_LOCALE => $this->Cookie->read('lang')));
        }

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

        return $settings;*/
    }


    function setLocale($locale){

        $this->Session->write('Config.language', $locale);
        $this->Cookie->write('lang', $locale, false, '20 days');
       
        if($this->Session->read('Auth.User.id')){
            App::import('Model', 'Setting');
            $this->_setting = new Setting();

            $user_id = $this->Session->read('Auth.User.id');
            $localeSetting= $this->_setting->get(Setting::MODEL_TYPE_USER,$user_id ,Setting::NAMESPACE_DEFAULT,Setting::KEY_LOCALE);
            $localeSetting[Setting::MODEL_TYPE_USER][Setting::NAMESPACE_DEFAULT][Setting::KEY_LOCALE]['value'] = $locale;

            $this->Settings->save($localeSetting, $user_id);

        }

    }


}

?>