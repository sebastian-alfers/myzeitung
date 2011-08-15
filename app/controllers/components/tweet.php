<?php

require_once('libs/Social/TwitterOAuth/twitteroauth/twitteroauth.php');
require_once('libs/Social/TwitterOAuth/config.php');

/**
 * provides different helper methods for uploadging
 *
 * @author sebastianalfers
 *
 */
class TweetComponent extends Object {

    const SOCIAL = 'Social';
    const TWITTER = 'Twitter';
    const ACCESS_TOKEN = 'access_token';
    const OAUTH_TOKEN = 'oauth_token';
    const OAUTH_TOKEN_SECRET = 'oauth_token_secret';

    var $_session = array();

    var $_connection = '';
    var $_request_token = '';

	var $components = array('Session');

    function __construct(){
        parent::__construct();

        $this->checkConfig();

    }


    public function processCallback(){
        if($this->isValidCallback()){
            /* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
            $this->_connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $this->Session->read($this->_getOAuthTokenPath()), $this->Session->read($this->_getOAuthTokenSecretPath()));

            /* Request access tokens from twitter */
            $access_token = $this->_connection->getAccessToken($_REQUEST['oauth_verifier']);

            $this->Session->write($this->_getAccessTokenPath(), $access_token);


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
        if(!$this->isTokenAvailable()){

            $this->_connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
            $this->_request_token = $this->_connection->getRequestToken('http://127.0.0.1:8180/twitter/callback');

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
            $this->_connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $this->Session->read($this->_getOAuthTokenPath()), $this->Session->read($this->_getOAuthTokenSecretPath()));
            return $this->_connection;
        }
        return $this->_connection;
    }


    private function _saveTemporaryCredentials(){

        if(is_array($this->_request_token) && isset($this->_request_token[self::OAUTH_TOKEN]) && isset($this->_request_token[self::OAUTH_TOKEN_SECRET])){

            $this->Session->write($this->_getOAuthTokenPath(), $this->_request_token[self::OAUTH_TOKEN]);
            $this->Session->write($this->_getOAuthTokenSecretPath(), $this->_request_token[self::OAUTH_TOKEN_SECRET]);

            return true;
        }
        return false;
    }

    private function _getAccessTokenPath(){
        return self::SOCIAL.'.'.self::TWITTER.'.'.self::ACCESS_TOKEN;
    }

    /**
     * build cakephp path for session->read
     *
     * @return string
     */
    private function _getOAuthTokenPath(){
        return self::SOCIAL.'.'.self::TWITTER.'.'.self::ACCESS_TOKEN.'.'.self::OAUTH_TOKEN;
    }

    /**
     * build cakephp path for session->read
     *
     * @return string
     */
    private function _getOAuthTokenSecretPath(){
        return self::SOCIAL.'.'.self::TWITTER.'.'.self::ACCESS_TOKEN.'.'.self::OAUTH_TOKEN_SECRET;
    }



    public function isTokenAvailable(){
        $this->_session = $this->Session->read();


        return isset($this->_session[self::SOCIAL][self::TWITTER][self::ACCESS_TOKEN]) &&
               isset($this->_session[self::SOCIAL][self::TWITTER][self::ACCESS_TOKEN][self::OAUTH_TOKEN]) &&
               isset($this->_session[self::SOCIAL][self::TWITTER][self::ACCESS_TOKEN][self::OAUTH_TOKEN_SECRET]);

    }


    function checkConfig(){
        if (CONSUMER_KEY === '' || CONSUMER_SECRET === '') {
          echo 'You need a consumer key and secret to test the sample code. Get one from <a href="https://twitter.com/apps">https://twitter.com/apps</a>';
          exit;
        }
    }

    function clearSessions(){
        $this->Session->write(self::SOCIAL, NULL);
    }


}

?>