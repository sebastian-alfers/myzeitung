<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.app
 */
class AppController extends Controller {

    const LOG_LEVEL_SECURITY = 'secutity';

	//load debug toolbar in plugins/debug_toolbar/
	var $components = array('AutoLogin', 'Cookie','Auth', 'Session', 'DebugKit.Toolbar', 'RequestHandler');
	var $uses = array('User','ConversationUser');

    var $helpers = array('Cf', 'Session', 'Mzform', 'Image');

    //acl groups
    var $user = array(self::ROLE_USER, self::ROLE_ADMIN, self::ROLE_SUPERADMIN);
    var $admin = array(self::ROLE_ADMIN, self::ROLE_SUPERADMIN);
    var $superadmin = array(self::ROLE_SUPERADMIN);
    
    //acl roles
    const ROLE_USER = 1; //normal, logged in user
    const ROLE_ADMIN = 2; //normal, logged in user
    const ROLE_SUPERADMIN = 3; //


	public function beforeFilter(){
        App::import('Core', 'I18n');
        $trans = new I18n();
        $_this =& I18n::getInstance();
        $_this->__domains['default']['deu']['LC_MESSAGES']['%plural-c'].= ';';

        //load locale
     //   App::import('Core', 'L10n');
     //   $this->L10n = new L10n();
     //   $this->L10n->get('deu');


        //$this->Security->blackHoleCallback = 'blackHole';

	    $this->RequestHandler->setContent('json', 'text/x-json');
		
		
		$this->AutoLogin->cookieName = 'myZeitung';
		$this->AutoLogin->expires = '+1 month';
		$this->AutoLogin->settings = array(
			'controller' => 'users',
			'loginAction' => 'login',
			'logoutAction' => 'logout'
		
		);
         
		if(isset($this->Auth)) {
			// this makes the Auth-component use the isAuthorized()-method below
			$this->Auth->authorize = 'controller';
			// only enabled users are able to log in
			$this->Auth->userScope = array('User.enabled' => 1);
			$this->Auth->logoutRedirect = array('controller' => 'home', 'action' => 'index');
		}

        //set user role to frontend
        $is_user = (int)$this->Session->read('Auth.User.group_id') === self::ROLE_USER;
        $is_admin = (int)$this->Session->read('Auth.User.group_id') === self::ROLE_ADMIN;
        $is_superadmin = (int)$this->Session->read('Auth.User.group_id') === self::ROLE_SUPERADMIN;
        $this->set(compact('is_user', 'is_admin', 'is_superadmin'));

		//load unread-message-count for logged in users with enabled messaging
		// called again in conversations/view action, cos the counter might have been updated (if a -new- conversations has been clicked)
		if($this->Session->read('Auth.User.id')){
			if($this->Session->read('Auth.User.allow_messages')){
				$this->setConversationCount();
			}
		}

        //change layout if admin
		$pos = strpos($_SERVER['REQUEST_URI'], CAKE_ADMIN);
		if($pos == true)
		{
		    $this->layout='admin';
		}

	}


	
	
	protected function setConversationCount(){
		$this->ConversationUser->contain();
		$this->set('conversation_count', $this->ConversationUser->find('count', 
											array('conditions' => array('user_id' => $this->Session->read('Auth.User.id'),
												 'status' => Conversation::STATUS_NEW))));
	}
	
	
	public function _autoLogin($user) {
		// Do something
		// $user contains the Auth session
	}
		
	
	function isAuthorized() {


		// defining the authorized usergroups for every action of every controller 
		// 1 = admin 2=common user
		$allowedActions = array(
        'ajax' => array(
            'uploadPicture' => $this->user,
            'validateEmail' => $this->user,
            'validateUrl' => $this->user,
            'getVideoPreview' => $this->user,
        ),
		'users' => array(
			'index' => $this->user,
			'add' => $this->user,
			'edit' => $this->user,
			'view' => $this->user,
			'delete' => $this->user,
			'subscribe' => $this->user,
			'accImage' => $this->user,
			'ajxProfileImageProcess' => $this->user,		
			'accAboutMe' => $this->user,
			'accGeneral' => $this->user,
			'accPrivacy' => $this->user,
        	'accDelete' => $this->user,
            'admin_index' => $this->admin,
            'admin_edit' => $this->superadmin,
            'admin_delete' => $this->superadmin,
            'admin_view' => $this->admin,
            'admin_disable' => $this->admin,
            'admin_enable' => $this->admin,
			),
		'topics' => array(
			'ajax_add' => $this->user,
            'getTopics' => $this->user,
            'delete' => $this->user,
			),
		'posts' => array(
			'index' => $this->user,
			'repost' => $this->user,
			'undoRepost' => $this->user,
			'add' => $this->user,
			'edit' => $this->user,
			'view' => $this->user,
			'delete' => $this->user,
			'ajxImageProcess' => $this->user,
			'url_content_extract' => $this->user,
			'ajxRemoveImage' => $this->user,
            'admin_disable' => $this->admin,
            'admin_enable' => $this->admin,
            'admin_index' => $this->admin,
            'admin_delete' => $this->superadmin,
			),	
		'papers' => array(
			'index' => $this->user,
			'add' => $this->user,
			'edit' => $this->user,
			'view' => $this->user,
			'delete' => $this->user,
			'addcontent' => $this->user,
			'references' => $this->user,
			'subscribe' => $this->user,
			'unsubscribe' => $this->user,
            'settings' => $this->user,
            'saveImage' => $this->user,
            'admin_disable' => $this->admin,
            'admin_enable' => $this->admin,
            'admin_index' => $this->admin,
            'admin_delete' => $this->superadmin,
			),				
		'categories' => array(
			'index' => $this->user,
			'add' => $this->user,
			'edit' => $this->user,
			'view' => $this->user,
			'delete' => $this->user,
			),		
		'comments' => array(
			'add' => $this->user,
			'ajxAdd' => $this->user,
			'ajxGetForm' => $this->user,
			'delete' => $this->user
			),
		'install' => array(
			'index' => $this->user,
			),
		'conversations' => array(
			'add' => $this->user,
			'view' => $this->user,
			'reply' => $this->user,
			'index' => $this->user,
			'remove' => $this->user,
 			),
		'complaints' => array(
			'admin_index' => $this->admin,
            'admin_view' => $this->admin,
            'admin_edit' => $this->superadmin,
            'admin_delete' => $this->superadmin,
 			),
        'admin' => array(
            'admin_index' => $this->admin,
            ),
        'search' => array(
            'admin_index' => $this->admin,
            'admin_refreshUsersIndex' => $this->superadmin,
            'admin_refreshPostsIndex' => $this->superadmin,
            'admin_refreshPapersIndex' => $this->superadmin,
            ),
        'index' => array(
            'admin_cleanUpPostUserIndex' => $this->superadmin,
            'admin_index' => $this->admin,
            ),
		);
		
		
		// check if the specific controller and action is set in the allowedAction array and if the group of the specific user is allowed to use it
			if(isset($allowedActions[low($this->name)])) {
			$controllerActions = $allowedActions[low($this->name)];
			if(isset($controllerActions[$this->action]) && 
			in_array($this->Auth->user('group_id'), $controllerActions[$this->action])) {
				return true;
			}
		}
        $this->Session->setFlash(__('No Permission', true));
		return false;
	}

    /**
     * get a name of a model and an id (primary key) and
     * checks, if the user is the owner of the moddel
     *
     * @param  $model - name of model
     * @param  $id - primary key
     * @return boolean
     */
    protected function canEdit($model_name, $id, $field){
        $logged_in_user_id = $this->Session->read('Auth.User.id');

        if(!$logged_in_user_id){
            $this->log('User not logged in - can not load ' . $model_name . ' to check permissions');
            return false;
        }

        if(class_exists($model_name)){
            $model = new $model_name();
            $model->contain();
            $model_data = $model->read(null, $id);


            if($model->id){
                if($model_data[$model_name][$field] == $logged_in_user_id){
                    return true;
                }
                else{
                    $this->log('User ' . $logged_in_user_id . ' trys to change ' . get_class($model) . ' with id ' . $model->id . ' without beeing the owner!');
                    return false;
                }
            }
            else{
                $this->log('Can not load ' . get_class($model) . ' with id ' . $id);
                return false;
            }
        }
        else{
            $this->log('Model ' . $model . ' not found');
            return false;
        }





    }

    /**
     * @param  $method
     * @param  $messages
     * @return void
     */
    /*
    function appError($method, $messages){

        switch($method){
            //case 'missingController':
                // s.th
                //break
            default:
                $this->cakeError('error404');
        }
        die();
    }
     */

    function _setErrorLayout() {
      if ($this->name == 'CakeError') {
        $this->layout = 'error';
      }
    }

    /**
     * is triggered, when user manipulates the security hash
     */
    function blackHole() {
      $this->log("blackHole()", self::LOG_LEVEL_SECURITY);
      $this->log($_SERVER, self::LOG_LEVEL_SECURITY);
      $this->log($_REQUEST, self::LOG_LEVEL_SECURITY);
      die("Your IP has been saved!");
    }
    function _sendMail($to,$subject,$template) {

        $this->Email->to = $to;
        // $this->Email->bcc = array('secret@example.com'); // copies
        $this->Email->subject = $subject;
        $this->Email->replyTo = 'noreply@myzeitung.de';
        $this->Email->template = $template;
        $this->Email->sendAs = 'both'; //Send as 'html', 'text' or 'both' (default is 'text')
        $this->Email->delivery = 'mail';

        //do be changed on live
        $this->Email->additionalParams = '-fsebastian.alfers@gmail.com';
        $this->Email->from = 'Sebastian Alfers <sebastian.alfers@gmail.com>';

        $this->Email->send();


    }


	function beforeRender()
    {
        //$this->_setErrorLayout();

        //???????????????????????????????
        //if (Configure::read('debug') == 0) {
            //ob_start();
       //}
    } 
/*
	function afterFilter()
    {
       if (Configure::read('debug') == 0) {
            $output = ob_get_contents();
            ob_end_clean();
            echo $this->_clean($output);
        }
    } 
	
	function _clean($string){
        $string = str_replace("\n", '', $string);
        $string = str_replace("\t", '', $string);
        $string = preg_replace('/[ ]+/', ' ', $string);
        $string = preg_replace('/<!--[^-]*-->/', '', $string);
        return $string;
    } 
	*/
}


/*function __construct(){
	parent::__construct();
	$this->Auth->autoRedirect = false;

}*/

//Router::parseExtensions('json');