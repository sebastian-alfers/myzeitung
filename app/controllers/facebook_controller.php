<?php


class FacebookController extends AppController {

    var $name = 'Facebook';

    var $components = array('Face', 'Settings');

    var $uses = array();

    /**
     * entry point for FB response url
     * start redirect to FB
     * action to connect or disconnect with FB
     *
     * @return void
     */
    function toggle(){

        if($this->Face->isConnected()){
            $this->redirect(array('controller' => 'facebook', 'action' => 'logout'));
        }
        else{
            $this->redirect(array('controller' => 'facebook', 'action' => 'login'));
        }
    }

    function login(){
        if(!$this->Face->isConnected()){
            header('Location: '. $this->Face->getLoginUrl());
        }
        else{
            $this->redirect(array('controller' => 'facebook', 'action' => 'test'));
        }
    }

    function logout(){
        if($this->Face->isConnected()){
            header('Location: '. $this->Face->getLogoutUrl());
        }
        else{
            $this->redirect(array('controller' => 'facebook', 'action' => 'test'));
        }
    }


    function test(){



        /**
         *
    [fb_262404740453707_code] =>
    [fb_262404740453707_access_token] =>
    [fb_262404740453707_user_id] =>
)
         */

        //$this->Session->write('fb_262404740453707_code', 'AQBjOXk0_MyB5Rc1uyH_kTYvVa7y0kTuUD5tgrDbLwSbV-KGCBlYHWvU1U4K0EgQd4XgysvgO0l1Ii61yCJlJsLKJtzlDtsqdPsVLeoVAVtpcW2JMcSZNSe5GbEMnCZVVGvOd4qAxQXMrXZ32zMCdyX-gXDDaMtNdlH02uNhxHnLayLWeGmBTDkKfTNMZq9yd9A');
        //$this->Session->write('fb_262404740453707_access_token', '262404740453707|2.AQDQSqzchcB9afy7.3600.1313600400.1-510644844|9IYoV9BY_EzMZTTPMSJ_rM_oaIc');
        //$this->Session->write('fb_262404740453707_user_id', '510644844');

        $facebook = new Facebook(array(
            'appId'  => FB_APP_ID,
            'secret' => FB_APP_SECRET,
            'cookie' => true
          ));




                 // Get User ID
          $user = $facebook->getUser();



          if ($user) {
            try {
              // Proceed knowing you have a logged in user who's authenticated.
              $user_profile = $facebook->api('/me');
                $this->set('user_profile', $user_profile);
            } catch (FacebookApiException $e) {
              error_log($e);
              $user = null;
            }
          }

          // Login or logout url will be needed depending on current user state.
          if ($user) {
            $logoutUrl = $facebook->getLogoutUrl();
              $this->set('logoutUrl', $logoutUrl);
          } else {
            $loginUrl = $facebook->getLoginUrl();
              $this->set('loginUrl', $loginUrl);
          }

          // This call will always work since we are fetching public data.
          $naitik = $facebook->api('/naitik');

        $this->set('naitik', $naitik);

          $this->set('user', $user);







        /**
        if($this->Tweet->useTwitter()){

            $this->Settings->removeTwitter();
            $this->Session->setFlash(__('Your twitter profile has been removed. You can always activate it. We do not store any of your twitter data.', true), 'default', array('class' => 'success'));
            $this->redirect($this->referer());

        }
        else{
            $this->Tweet->clearSessions();
            $this->Tweet->connect();
        }
         * */
    }


    /**
     * entry point where twitter redirects back to us
     *
     * @return void
     */
    function callback(){

        /**
        if($this->Tweet->isValidCallback()){
            $this->Tweet->processCallback();
        }
        $this->redirect(array('controller' => 'settings', 'action' => 'social-media'));
         */
    }


}

?>