<?php
class IndexController extends AppController {

    var $name = 'Index';
    var $components = array('Auth', 'Session');

    var $uses = array('PostUser', 'Post','Paper', 'ContentPaper');
    var $helpers = array('Text' ,'MzTime', 'Image', 'Html', 'Javascript', 'Ajax', 'Reposter');


    var $utf8_models = array('Category','Comment', 'Complaint', 'Reason','Conversation', 'ConversationMessage', 'Helpelement', 'Helppage','Invitation','Paper','Post','Topic', 'User');

    function admin_cleanUpPostUserIndex(){
        $this->PostUser->cleanUpIndex();
        $this->Session->setFlash(__('The index table posts_users has been cleaned up', true));
        $this->redirect($this->referer());
    }
    function admin_cleanUpContentPaperIndex(){
        $this->ContentPaper->cleanUpIndex();
        $this->Session->setFlash(__('The index table ContentPaper and CategoryPaperPosts has been cleaned up', true));
        $this->redirect($this->referer());
    }
    function admin_index(){

    }
    function admin_refreshPostPaperRoutes(){
        $this->Post->refreshRoutes();
        $this->Paper->refreshRoutes();
        $this->redirect($this->referer());
    }
    function utf8read(){

        foreach($this->utf8_models as $modelName){
           App::import('Model', $modelName);
           $this->$modelName = new $modelName();
           debug(get_class($this->$modelName));
            $this->$modelName->contain();

            $entries = array();
            $entries = $this->$modelName->find('all');

            Cache::write($modelName.'_project_utf8', $entries);

        }
    }

    function utf8write(){
        foreach($this->utf8_models as $modelName){
           App::import('Model', $modelName);
           $this->$modelName = new $modelName();
           $this->log($modelName);
           $this->$modelName->updateSolr = false;
           $this->$modelName->updateRoute = false;
           $entries = array();
            $entries = Cache::read($modelName.'_project_utf8');
            if(count($entries) > 0){
                foreach($entries as $entry){
                    $this->$modelName->id = $entry[$modelName]['id'];
                    $this->$modelName->save($entry[$modelName]);
                }
            }
            Cache::delete($modelName.'_project_utf8');
        }

    }
}