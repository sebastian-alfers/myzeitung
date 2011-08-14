<?php



class TwitterController extends AppController {

    var $name = 'Twitter';

    var $components = array('Tweet');

    public function beforeFilter(){
        parent::beforeFilter();
        //declaration which actions can be accessed without being logged in
        $this->Auth->allow('index', 'connect');
    }


    function index(){
        //debug($this->twitter_call('http://twitter.com/account/verify_credentials.xml'));
        if($this->Tweet->isTokenAvailable()){
            $conn = $this->Tweet->getConnection();

            debug($conn->get('account/verify_credentials'));
        }
        else{
            $this->Tweet->clearSessions();
            $this->redirect(array('controller' => 'twitter',  'action' => 'connect'));
        }
    }


    function connect(){
        $this->Tweet->getConnection();

        //debug($this->Session->read());
    }



}

?>