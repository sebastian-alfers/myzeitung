<?php



/**
 * access to facebook
 *
 * @author sebastianalfers
 *
 */
class FaceComponent extends Object {

    var $components = array('Session');

    var $_fb = '';
    var $_user = '';

    var $_scope = array('publish_stream');

    function __construct(){
        parent::__construct();

        //$this->_fb = $this->getConnection();
    }

    /**
     * check if user is connected with FB or not
     *
     * @return bool
     */
    function isConnected(){
        $this->_fb = $this->getConnection();

        // Get User ID
        $this->_user = $this->_fb->getUser();

        if ($this->_user) {
            return true;
        }
        return false;
    }

    function getUser(){
        if($this->isConnected()){
            return $this->_user;
        }
    }

    function useFacebook(){
        return $this->isConnected();
    }

    function getUserProfile(){

        if($this->isConnected()){
            // Get User ID
            $user = $this->getConnection()->getUser();
            if ($user) {
                try {
                    // Proceed knowing you have a logged in user who's authenticated.
                    $user_profile = $this->getConnection()->api('/me');
                    return $user_profile;
                } catch (FacebookApiException $e) {
                    $this->log($e);
                    return false;
                }
            }
        }
    }

    /**
     * retun connection
     *
     * @return Facebook
     */
    function getConnection(){
        if($this->_fb == ''){
            $this->_fb = new Facebook(array(
                'appId'  => FB_APP_ID,
                'secret' => FB_APP_SECRET,
                'cookie' => true
              ));
        }
        return $this->_fb;

    }


    function getLogoutUrl(){
        return $this->getConnection()->getLogoutUrl();
    }

    function getLoginUrl(){
        return $this->getConnection()->getLoginUrl(array('scope' => 'publish_stream'));
    }

}

?>