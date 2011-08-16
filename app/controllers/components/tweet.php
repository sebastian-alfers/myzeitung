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
            $this->_connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $this->Session->read($this->_getOAuthTokenPath()), $this->Session->read($this->_getOAuthTokenSecretPath()));

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

        if(!$this->isTokenAvailable()){

            $this->_connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);

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



    public function isTokenAvailable(){
        $this->_session = $this->Session->read();

        return isset($this->_session[self::AUTH][self::USER][self::SETTINGS][self::TWITTER][self::OAUTH_TOKEN]) &&
               isset($this->_session[self::AUTH][self::USER][self::SETTINGS][self::TWITTER][self::OAUTH_TOKEN_SECRET]);

    }


    function checkConfig(){
        if (CONSUMER_KEY === '' || CONSUMER_SECRET === '') {
          echo 'You need a consumer key and secret to test the sample code. Get one from <a href="https://twitter.com/apps">https://twitter.com/apps</a>';
          exit;
        }
    }

    function clearSessions(){
        $this->Session->write(self::AUTH, NULL);
    }

    function createTweet(){

        if($this->useTwiter()){
            $this->_connection = $this->getConnection();


            /* statuses/update */
            date_default_timezone_set('GMT');
            $parameters = array('status' => date(DATE_RFC822) . ' at myzeitung');
            $status = $this->_connection->post('statuses/update', $parameters);
            $this->twitteroauth_row('statuses/update', $status, $this->_connection->http_code, $parameters);
        }
        else{
            $this->Session->setFlash(__('You are not connected to twitter', true));
        }

    }

    function useTwiter(){
        return $this->isTokenAvailable();
    }

    function removeTwitterAccount(){
        $this->Settings->removeTwitter();
    }


function twitteroauth_row($method, $response, $http_code, $parameters = '') {
  echo '<tr>';
  echo "<td><b>{$method}</b></td>";
  switch ($http_code) {
    case '200':
    case '304':
      $color = 'green';
      break;
    case '400':
    case '401':
    case '403':
    case '404':
    case '406':
      $color = 'red';
      break;
    case '500':
    case '502':
    case '503':
      $color = 'orange';
      break;
    default:
      $color = 'grey';
  }
  echo "<td style='background: {$color};'>{$http_code}</td>";
  if (!is_string($response)) {
    $response = print_r($response, TRUE);
  }
  if (!is_string($parameters)) {
    $parameters = print_r($parameters, TRUE);
  }
  echo '<td>', strlen($response), '</td>';
  echo '<td>', $parameters, '</td>';
  echo '</tr><tr>';
  echo '<td colspan="4">', substr($response, 0, 400), '...</td>';
  echo '</tr>';

}


}

?>