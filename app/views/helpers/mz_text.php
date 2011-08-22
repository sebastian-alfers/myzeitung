<?php
App::import('Helper', 'Text');


class MzTextHelper extends TextHelper {

    public $helpers = array('Session');

    function getSubscribeUrl(){

        $subscribe_link = '/login';
        if($this->Session->read('Auth.User.id')){
            $subscribe_link = '#';
        }

        return $subscribe_link;

    }



    /**
     * @param  $user - array
     * @return void
     */
    function getUsername($user){
        $name = '';

        if(isset($user['username']) && !empty($user['username'])){
            $name = '<h3>'.$user['username'].'</h3>';
        }


        if(isset($user['name']) && !empty($user['name'])){
           $name .= $user['name'];
        }

        return $name;

    }
}
?>