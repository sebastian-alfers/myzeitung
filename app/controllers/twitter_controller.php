<?php

class TwitterController extends AppController {

    var $name = 'Twitter';

    var $components = array('Tweet', 'Settings');

    function toggle(){

        if($this->Tweet->useTwitter()){
            $this->Settings->removeTwitter();
            $this->Session->setFlash(__('Your twitter profile has been removed. You can always activate it. We do not store any of your twitter data.', true), 'default', array('class' => 'success'));
            $this->redirect($this->referer());

        }
        else{

            $this->Tweet->clearSessions();
            $this->Tweet->connect();
        }
    }


    /**
     * entry point where twitter redirects back to us
     *
     * @return void
     */
    function callback(){
        if($this->Tweet->isValidCallback()){
            $this->Tweet->processCallback();
        }
        $this->redirect(array('controller' => 'settings', 'action' => 'social-media'));
    }


}

?>