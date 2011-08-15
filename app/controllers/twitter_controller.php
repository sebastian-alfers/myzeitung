<?php



class TwitterController extends AppController {

    var $name = 'Twitter';

    var $components = array('Tweet');

    public function beforeFilter(){
        parent::beforeFilter();
        //declaration which actions can be accessed without being logged in
        $this->Auth->allow('index', 'connect', 'clear', 'callback', 'reAuth');
    }

    function index(){

        debug($this->Session->read());

        if($this->Tweet->isTokenAvailable()){
            $conn = $this->Tweet->getConnection();

            debug($conn->get('account/verify_credentials'));
            //$content = $conn->get('account/rate_limit_status');
            //echo "Current API hits remaining: {$content->remaining_hits}.";

            /* statuses/update */
            //date_default_timezone_set('GMT');
            //$parameters = array('status' => date(DATE_RFC822));
            //$status = $conn->post('statuses/update', $parameters);
            //$this->twitteroauth_row('statuses/update', $status, $conn->http_code, $parameters);

        }
        else{
            $this->redirect(array('controller' => 'twitter',  'action' => 'connect'));
        }
    }








    function callback(){
        if($this->Tweet->isValidCallback()){
            $this->Tweet->processCallback();
        }
        $this->redirect(array('controller' => 'twitter', 'action' => 'index'));
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
        $this->redirect(array('controller' => 'twitter',  'action' => 'index'));
    }



}

?>