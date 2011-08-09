<?php
class IndexController extends AppController {

    var $name = 'Index';
    var $components = array('Auth', 'Session');
    var $uses = array('PostUser');
    var $helpers = array('Text' ,'MzTime', 'Image', 'Html', 'Javascript', 'Ajax', 'Reposter');

    function admin_cleanUpPostUserIndex(){
        $this->PostUser->cleanUpIndex();
        $this->Session->setFlash(__('The index table posts_users has been cleaned up', true));
        $this->redirect($this->referer());
    }
    function admin_index(){

    }
}