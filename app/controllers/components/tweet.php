<?php

require_once('libs/Social/TwitterOAuth/twitteroauth/twitteroauth.php');
require_once('libs/Social/TwitterOAuth/config.php');

/**
 * access to twitter
 *
 * @author sebastianalfers
 *
 */
class TweetComponent extends Object {

    const AUTH = 'Auth';
    const USER = 'User';
    const SETTING = 'Setting';
    const TWITTER = 'twitter';
    const OAUTH_TOKEN = 'oauth_token';
    const OAUTH_TOKEN_SECRET = 'oauth_token_secret';

    var $_session = array();

    var $_connection = '';
    var $_request_token = '';

	var $components = array('Session', 'Settings');

    function __construct(){
        parent::__construct();

        $this->checkConfig();

    }


    public function processCallback(){

        if($this->isValidCallback() && $this->isTemporaryTokenAvailable()){

            /* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
            $this->_connection = new TwitterOAuth(TW_CONSUMER_KEY, TW_CONSUMER_SECRET, $this->Session->read('tmp.'.self::OAUTH_TOKEN), $this->Session->read('tmp.'.self::OAUTH_TOKEN_SECRET));

            /* Request access tokens from twitter */
            $access_token = $this->_connection->getAccessToken($_REQUEST['oauth_verifier']);


            if($this->isValidAccessToken($access_token)){

                $settings = $this->Session->read('Auth.Setting.user.twitter');
                $settings['oauth_token']['value'] = $access_token['oauth_token'];
                $settings['oauth_token_secret']['value'] = $access_token['oauth_token_secret'];

                $this->Settings->save(array('user' => array('twitter' =>$settings)), $this->Session->read('Auth.User.id'));

                $this->Session->delete('tmp.'.self::OAUTH_TOKEN);
                $this->Session->delete('tmp.'.self::OAUTH_TOKEN_SECRET);
            }
        }
    }

    public function isValidAccessToken($access_token){
        if(!is_array($access_token)) return false;
        if(empty($access_token)) return false;

        if(!isset($access_token['oauth_token'])) return false;
        if(!isset($access_token['oauth_token_secret'])) return false;

        if(empty($access_token['oauth_token'])) return false;
        if(empty($access_token['oauth_token_secret'])) return false;

        return true;
    }

    public function isValidCallback(){

        return (isset($_REQUEST['oauth_token']) && $this->Session->read('tmp.'.self::OAUTH_TOKEN) == $_REQUEST['oauth_token']);
    }

    /**
     * redirect to twitter
     *
     * @return void
     */
    public function connect(){


        if(!$this->isTemporaryTokenAvailable()){



            $this->_connection = new TwitterOAuth(TW_CONSUMER_KEY, TW_CONSUMER_SECRET);

            $callback_url = Router::url('/', true);
            $callback_url.= 'twitter/callback';
            $this->_request_token = $this->_connection->getRequestToken($callback_url);

            if($this->_saveTemporaryCredentials()){

                switch ($this->_connection->http_code) {
                    case 200:
                        /* Build authorize URL and redirect user to Twitter. */
                        $url = $this->_connection->getAuthorizeURL($this->_request_token);
                        header('Location: ' . $url);
                        die();//important to use within debug=0 mode
                        break;

                    default:
                        /* Show notification if something went wrong. */
                        echo 'Could not connect to Twitter. Refresh the page or try again later.';
                        break;
                }
            }
        }

            //debug($this->_request_token);
    }

    public function getConnection(){
        if($this->isTokenAvailable()){
            $this->_connection = new TwitterOAuth(TW_CONSUMER_KEY, TW_CONSUMER_SECRET, $this->Session->read($this->_getOAuthTokenPath()), $this->Session->read($this->_getOAuthTokenSecretPath()));
            return $this->_connection;
        }
        return $this->_connection;
    }


    private function _saveTemporaryCredentials(){

        if(is_array($this->_request_token) && isset($this->_request_token[self::OAUTH_TOKEN]) && isset($this->_request_token[self::OAUTH_TOKEN_SECRET])){

            $this->Session->write('tmp.'.self::OAUTH_TOKEN, $this->_request_token[self::OAUTH_TOKEN]);
            $this->Session->write('tmp.'.self::OAUTH_TOKEN_SECRET, $this->_request_token[self::OAUTH_TOKEN_SECRET]);

            return true;
        }
        return false;
    }


    /**
     * build cakephp path for session->read
     *
     * @return string
     */
    private function _getOAuthTokenPath(){
        return "Auth.Setting.user.twitter.oauth_token.value";
    }

    /**
     * build cakephp path for session->read
     *
     * @return string
     */
    private function _getOAuthTokenSecretPath(){
        return "Auth.Setting.user.twitter.oauth_token_secret.value";
    }


    /**
     * load user profile data via tw api
     *
     * @return void
     */
    public function getUserProfile(){

        if($this->useTwitter()){
            $this->_connection = $this->getConnection();

            /* account/verify_credentials */
            $method = 'account/verify_credentials';
            $response = $this->_connection->get($method);

            if($this->isStatusValid($this->_connection->http_code)){
                $filerResponse = array();

                if(isset($response->name) && !empty($response->name))
                    $filerResponse['name'] = $response->name;

                if(isset($response->screen_name) && !empty($response->screen_name))
                    $filerResponse['screen_name'] = $response->screen_name;

                if(isset($response->profile_image_url) && !empty($response->profile_image_url))
                    $filerResponse['profile_image_url'] = $response->profile_image_url;


                return $filerResponse;

            }
            else{
                return false;
            }
        }
        else{
            $this->Session->setFlash(__('You are not connected to twitter', true));
        }


    }

    public function isTemporaryTokenAvailable(){
	    if($this->Session->check('tmp.'.self::OAUTH_TOKEN) && $this->Session->check('tmp.'.self::OAUTH_TOKEN_SECRET)){
            $token = $this->Session->read('tmp.'.self::OAUTH_TOKEN);
            $secret = $this->Session->read('tmp.'.self::OAUTH_TOKEN_SECRET);
            return !empty($token) && !empty($secret);
	    }
        return false;
    }

    public function isTokenAvailable(){
	    if($this->Session->check('Auth.Setting.user.twitter.oauth_token')){

            $token = $this->Session->read('Auth.Setting.user.twitter.oauth_token.value');

            return !(boolean)empty($token);
	    }
    
        return false;
    
    }

    function checkConfig(){
        if (TW_CONSUMER_KEY === '' || TW_CONSUMER_SECRET === '') {
          echo 'You need a consumer key and secret to test the sample code. Get one from <a href="https://twitter.com/apps">https://twitter.com/apps</a>';
          exit;
        }
    }

    function clearSessions(){
        $this->Session->write("tmp.".self::OAUTH_TOKEN, '');
        $this->Session->write("tmp.".self::OAUTH_TOKEN_SECRET, '');
    }


    /**
     * create tweet on users wall
     *
     * @return bool
     */
    function createTweet($msg = ''){

        if($this->useTwitter()){
            debug('use me');
            $this->_connection = $this->getConnection();
            if($msg != ''){
                debug('mgs ist ' . $msg);

                $parameters = array('status' => $msg);
                $status = $this->_connection->post('statuses/update', $parameters);

                debug($this->_connection);
                if($this->isStatusValid($this->_connection->http_code)){
                        debug('is valid');
                       return $status;
                }
                else{
                    debug($status);
                    debug('nooooot');
                    return false;
                }
            }
        }
        else{
            $this->Session->setFlash(__('You are not connected to twitter', true));
        }

    }

    /**
     * tweet that a post has been created
     *
     * @return void
     */
    function newPost($msg){
        return $this->createTweet($msg);
    }

    function useTwitter(){
        return $this->isTokenAvailable();
    }

    function removeTwitterAccount(){
        $this->Settings->removeTwitter();
    }


    /**
     * analyze request and return data
     *
     * @param obs $response - result from e.g. $this->_connection->post or $this->_connection->get
     * @param  $http_code - from api call
     * @return bool
     */
    function isStatusValid($http_code) {

        switch ($http_code) {
            case '400':
            case '401':
            case '403':
            case '404':
            case '406':
              return false;
              break;
            case '500':
            case '502':
            case '503':
                return false;
                break;
        }

        //200 or 304
        return true;


    }


}

?>