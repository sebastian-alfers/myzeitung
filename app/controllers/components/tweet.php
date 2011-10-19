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
    const SETTINGS = 'Settings';
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
        if($this->isValidCallback()){
            /* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
            $this->_connection = new TwitterOAuth(TW_CONSUMER_KEY, TW_CONSUMER_SECRET, $this->Session->read($this->_getOAuthTokenPath()), $this->Session->read($this->_getOAuthTokenSecretPath()));

            /* Request access tokens from twitter */
            $access_token = $this->_connection->getAccessToken($_REQUEST['oauth_verifier']);

            $this->Session->write($this->_getOAuthTokenPath(), $access_token['oauth_token']);
            $this->Session->write($this->_getOAuthTokenSecretPath(), $access_token['oauth_token_secret']);

            //write new values to user and session
            unset($access_token['user_id']);
            unset($access_token['screen_name']);
            $this->Settings->save('User', 'twitter', $access_token);
            //'User', session user id,

            /* Save the access tokens. Normally these would be saved in a database for future use. */
            //$_SESSION['access_token'] = $access_token;

        }
    }

    public function isValidCallback(){
        return (isset($_REQUEST['oauth_token']) && $this->Session->read($this->_getOAuthTokenPath()) === $_REQUEST['oauth_token']);
    }

    /**
     * redirect to twitter
     *
     * @return void
     */
    public function connect(){
		debug('jo?');
        if(!$this->isTokenAvailable()){

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

			debug($this->Session->read());
			debug($this->_request_token);

			$data = $this->Session->read('Auth');
			debug($data);
            $data['Setting']['user']['social']['twitter.oauth_token']['value'] = $this->_request_token[self::OAUTH_TOKEN];
            $data['Setting']['user']['social']['twitter.oauth_token_secret']['value'] = $this->_request_token[self::OAUTH_TOKEN_SECRET];            

            if($this->Settings->save($data['Setting'], $this->Session->read('Auth.User.id'))){            
            	echo 'jo';
        	}
        	else{
        		echo 'no';
        	}
            
            
			debug($this->Session->read());
			die();
			
			
//			    function save($settingData = null, $model_id = null){

            //$this->Session->write($this->_getOAuthTokenPath(), $this->_request_token[self::OAUTH_TOKEN]);
            //$this->Session->write($this->_getOAuthTokenSecretPath(), $this->_request_token[self::OAUTH_TOKEN_SECRET]);

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
        return self::AUTH.'.'.self::USER.'.'.self::SETTINGS.'.'.self::TWITTER.'.'.self::OAUTH_TOKEN;
    }

    /**
     * build cakephp path for session->read
     *
     * @return string
     */
    private function _getOAuthTokenSecretPath(){
        return self::AUTH.'.'.self::USER.'.'.self::SETTINGS.'.'.self::TWITTER.'.'.self::OAUTH_TOKEN_SECRET;
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

    public function isTokenAvailable(){
	    if($this->Session->check('Auth.Setting.user.social')){
    		$ses = $this->Session->read('Auth.Setting.user.social');
    		return isset($ses['twitter.oauth_token']['value']) && !empty($ses['twitter.oauth_token']['value']);
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
        $this->Session->write("Auth.User.Settings.twitter", NULL);
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