<?php



class TwitterController extends AppController {

    var $name = 'Twitter';

    var $components = array('Tweet', 'Settings');

    public function beforeFilter(){
        parent::beforeFilter();
        //declaration which actions can be accessed without being logged in
        $this->Auth->allow('connect', 'clear', 'callback', 'reAuth', 'remove');
    }


    /*
    function index(){
        if($this->Tweet->isTokenAvailable()){
            $this->redirect(array('controller' => 'settings', 'action' => 'social-media'));
            //$conn = $this->Tweet->getConnection();

            //$content = $conn->get('account/rate_limit_status');
            //echo "Current API hits remaining: {$content->remaining_hits}.";


            //date_default_timezone_set('GMT');
            //$parameters = array('status' => date(DATE_RFC822));
            //$status = $conn->post('statuses/update', $parameters);
            //$this->twitteroauth_row('statuses/update', $status, $conn->http_code, $parameters);

        }
        else{
            $this->redirect(array('controller' => 'twitter',  'action' => 'clear'));
        }
    }
    */


    /**
     * remove settings from session and settings in DB
     *
     * @return void
     */
    function remove(){
        $this->Settings->removeTwitter();
        $this->Session->setFlash(__('Your twitter profile has been removed. You can activate it ', true));
        $this->redirect($this->referer());
    }








    function callback(){
        if($this->Tweet->isValidCallback()){
            $this->Tweet->processCallback();
        }
        $this->redirect(array('controller' => 'settings', 'action' => 'social-media'));
    }

    /**
     * redirect to twitter and build connection if possible
     *
     * @return void
     */
    function connect(){

        if(!$this->Tweet->isTokenAvailable()){
            //only redirect if session is empty
            $this->Tweet->connect();
        }
        else{
            $this->redirect(array('controller' => 'twitter', 'action' => 'index'));
        }




        //debug($this->Session->read());
    }

    function clear(){
        $this->Tweet->clearSessions();
        $this->redirect(array('controller' => 'twitter',  'action' => 'connect'));
    }



}

?>