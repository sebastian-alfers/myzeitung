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

    var $helpers = array('Cf', 'Session', 'Mzform');

	
	public function beforeFilter(){
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

		//load unread-message-count for logged in users with enabled messaging
		// called again in conversations/view action, cos the counter might have been updated (if a -new- conversations has been clicked)
		if($this->Session->read('Auth.User.id')){
			if($this->Session->read('Auth.User.allow_messages')){
				$this->setConversationCount();
			}
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
            'uploadPicture' => array(1)
        ),
		'users' => array(
			'index' => array(1),
			'add' => array(1,2),
			'edit' => array(1),
			'view' => array(1),
			'delete' => array(1),
			'references' => array(1),
			'subscribe' => array(1),
			'accImage' => array(1),
			'ajxProfileImageProcess' => array(1),		
			'accAboutMe' => array(2,1),
			'accGeneral' => array(2,1),
			'accPrivacy' => array(2,1),
        	'accDelete' => array(2,1),
			),
		'topics' => array(
			'ajax_add' => array(1)
			),
		'posts' => array(
			'index' => array(1),
			'repost' => array(1),
			'undoRepost' => array(1),
			'add' => array(1),
			'edit' => array(1),
			'view' => array(1,2),
			'delete' => array(1),
			'ajxImageProcess' => array(1),
			'url_content_extract' => array(1),
			'ajxRemoveImage' => array(1)					
			),	
		'papers' => array(
			'index' => array(1),
			'add' => array(1),
			'edit' => array(1),
			'view' => array(1,2),
			'delete' => array(1),
			'addcontent' => array(1),
			'references' => array(1),
			'subscribe' => array(1),
			'unsubscribe' => array(1),
            'settings' => array(1),
            'saveImage' => array(1),
			),				
		'categories' => array(
			'index' => array(1),
			'add' => array(1),
			'edit' => array(1),
			'view' => array(1,2),
			'delete' => array(1),
			),		
		'comments' => array(
			'add' => array(1),
			'ajxAdd' => array(1),
			'ajxGetForm' => array(1),
			'delete' => array(1)
			),
		'install' => array(
			'index' => array(1),
			),
		'conversations' => array(
			'add' => array(2,1),
			'view' => array(2,1),
			'reply' => array(2,1),
			'index' => array(2,1),
			'remove' => array(2,1),
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
            $this->log('User not logged in - can not load ' . $model . ' to check permissions');
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
                    $this->log('User ' . $logged_in_user_id . ' trys to change ' . $model . ' with id ' . $model->id . ' without beeing the owner!');
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
      die("You IP has been saved!");
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

Router::parseExtensions('json');