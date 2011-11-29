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

    /*
     * use custom helper to override core CacheHelper
     * @see http://cakebaker.42dh.com/2008/11/07/an-idea-for-hacking-core-helpers/
     */
    public $view = 'Mz';

    const LOG_LEVEL_SECURITY = 'security';

	//load debug toolbar in plugins/debug_toolbar/
	var $components = array('AutoLogin', 'Cookie','Auth', 'Session', 'RequestHandler');
	var $uses = array('User','ConversationUser', 'Helppage');

    /*
     * options for the Asses.asset plugin
     */
    var $helpers = array('MzHtml','MzHelpcenter', 'Session', 'Form', 'Mzform', 'Image','MzNumber', 'MzText', 'MzJavascript', 'Asset.asset' => array('md5FileName' => true, 'debug' => true), 'Cache');

    //acl groups
    var $user = array(self::ROLE_USER, self::ROLE_ADMIN, self::ROLE_SUPERADMIN);
    var $admin = array(self::ROLE_ADMIN, self::ROLE_SUPERADMIN);
    var $superadmin = array(self::ROLE_SUPERADMIN);
    var $robot = array(self::ROLE_ROBOT);

    //acl roles
    const ROLE_USER = 1; //normal, logged in user
    const ROLE_ADMIN = 2; //normal, logged in user
    const ROLE_SUPERADMIN = 3; //
    const ROLE_ROBOT = 4; //for shell worker

    var $_open_graph_data = array();


	public function beforeFilter(){

        //set permission for robot from shell
        if(isset($this->params['robot']) && !empty($this->params['robot'])){
            $_SESSION['Auth']['User']['group_id'] = self::ROLE_ROBOT;
        }


        $this->Auth->loginError = __('Login fehlgeschlagen. Ungültiger Benutzername/Email oder ungültiges Passwort.', true);


        $this->_open_graph_data = array('title'    => 'myZeitung',
                             'type'     => 'website',
                             'url'      => Router::url($this->here, true),
                             'image'    => '',
                             'site_name'=> 'myZeitung',
                             'admins'   => 123123231);

        //getting settings of logged in user if not already in session
        if($this->Session->read('Auth.User.id') && !$this->Session->read('Auth.Setting')){
            App::import('model', 'User');
            $user = new User();

            $this->Session->write('Auth.Setting', $user->getSettings($this->Session->read('Auth.User.id')));
        }

        $this->_setLanguage();

        //App::import('Core', 'I18n');
        //$trans = new I18n();
        //$_this =& I18n::getInstance();
        //$_this->__domains['default']['deu']['LC_MESSAGES']['%plural-c'].= ';';

        //load locale





        $this->Security->blackHoleCallback = 'blackHole';

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
				$this->setConversationCount();
		}

        if(isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI'])){
            //change layout if admin
            $pos = strpos($_SERVER['REQUEST_URI'], CAKE_ADMIN);
            if($pos == true)
            {
                $this->layout='admin';
            }
        }


	}




	protected function setConversationCount(){
		$this->ConversationUser->contain();

        $key = 'conv_count_'.$this->Session->read('Auth.User.id');
        $conversation_count = Cache::read($key);
        if($conversation_count === false){
            $conversation_count = $this->ConversationUser->find('count',
											array('conditions' => array('user_id' => $this->Session->read('Auth.User.id'),
												 'status' => Conversation::STATUS_NEW)));
            Cache::write($key, $conversation_count);
        }
        $this->set('conversation_count', $conversation_count);
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
            'validateNewMessage' => $this->user,
            'validateNewCategory' => $this->user,
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
            'accSocial' => $this->user,
        	'accDelete' => $this->user,
            'accInvitations' => $this->user,
            'accRssImport' => $this->user,
            'accRemoveRssFeed' => $this->user,
            'accAddRssFeed' => $this->user,
            'admin_index' => $this->admin,
            'admin_edit' => $this->superadmin,
            'admin_delete' => $this->superadmin,
            'admin_view' => $this->admin,
            'admin_disable' => $this->admin,
            'admin_enable' => $this->admin,
            'admin_toggleVisible' => $this->admin,
            'deleteProfilePicture' => $this->user,
            'feed' => $this->user,
			),
		'topics' => array(
			'ajax_add' => $this->user,
            'getTopics' => $this->user,
            'delete' => $this->user,
            'view_topic_name' => $this->user,
            'edit' => $this->user
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
            'deleteImage' => $this->user,
            'admin_disable' => $this->admin,
            'admin_enable' => $this->admin,
            'admin_index' => $this->admin,
            'admin_delete' => $this->superadmin,
            'admin_editPremium' => $this->superadmin,
            'admin_toggleVisible' => $this->admin,
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
			'delete' => $this->user,
            'admin_disable' => $this->admin,
            'admin_enable' => $this->admin,
            'admin_index' => $this->admin,
            'admin_delete' => $this->superadmin,
			),
		'install' => array(
			'index' => $this->superadmin,
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
            'admin_delete' => $this->superadmin,
            ),
        'index' => array(
             'admin_cleanUpContentPaperIndex' => $this->superadmin,
            'admin_cleanUpPostUserIndex' => $this->superadmin,
            'admin_index' => $this->admin,
            'admin_refreshPostPaperRoutes' => $this->superadmin,
            'utf8read' => $this->superadmin,
            'utf8write' => $this->superadmin,
            
            ),
        'twitter' => array(
            'callback'  => $this->user,
            'toggle'    => $this->user,
            ),
        'facebook' => array(
            'test'    => $this->user,
            'toggle'  => $this->user,
            'logout'  => $this->user,
            'login'  => $this->user,
        ),
        'settings' => array(
            'admin_index'    => $this->user,
            'admin_edit'  => $this->user,
        ),
         'aws' => array(
             'admin_ses' => $this->admin,
             'admin_ec2' => $this->admin,
             'admin_cf' => $this->admin,
         ),
        'invitations' => array(
            'add' => $this->user,
            'delete' => $this->user,
            'remindInvitee' => $this->user,

            ),
        'helppages' => array(
                 'admin_index' => $this->admin,
                 'admin_add' => $this->superadmin,
                 'admin_edit' => $this->superadmin,
                 'admin_delete' => $this->superadmin,
         ),
        'helpelements' => array(
                 'admin_add' => $this->superadmin,
                 'admin_edit' => $this->admin,
                 'admin_delete' => $this->superadmin,
         ),

         'rss' => array(
             'robotlanding' => $this->robot,
             'feedCrawl' => $this->robot,
             'admin_analyzeFeed' => $this->superadmin,
             'scheduleAllFeedsForCrawling' => $this->admin,
             'removeFeedForUser' => $this->user,
             'addFeedForUser' => $this->user,
             'admin_robotTasks' => $this->superadmin,
             'crawlNextFeeds' => $this->robot,
         ),
          'rssimportlogs' => array(
                'admin_index' => $this->admin,
              'admin_view' => $this->admin,
            ),
        'sitemaps' => array(
             'admin_send_sitemap' => $this->superadmin,
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
            $this->log('Model ' . $model_name . ' not found');
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
      $this->log("blackHole()"/*, self::LOG_LEVEL_SECURITY */);
      $this->log($_SERVER /*, self::LOG_LEVEL_SECURITY */);
      $this->log($_REQUEST /*, self::LOG_LEVEL_SECURITY*/);
      die("Your IP has been saved!");
    }
    function _sendMail($to,$subject,$template) {

        $this->Email->to = $to;
        $this->Email->subject = $subject;
        $this->Email->replyTo = 'noreply@myzeitung.de';
        $this->Email->template = $template;
        $this->Email->sendAs = 'both'; //Send as 'html', 'text' or 'both' (default is 'text')
        $this->Email->delivery = 'mail';
       /* if($template == 'welcome'){
            $this->Email->bcc = array('sebastian.alfers@myzeitung.de', 'otto.schulz@myzeitung.de', 'tim.wiegard@myzeitung.de');
        } */
        //to be changed on live
        $this->Email->additionalParams = '-fno-reply@myzeitung.de';
        $this->Email->from = 'myZeitung <no-reply@myzeitung.de>';

        $this->Email->send();


    }

    /**
     * set language based on cookie
     *
     * @return void
     */
    function _setLanguage() {
        if($this->Session->read('Auth.Setting.user.default.locale.value')){
            $this->Session->write('Config.language', $this->Session->read('Auth.Setting.user.default.locale.value'));

        }
        if ($this->Cookie->read('lang') && !$this->Session->check('Config.language')) {
            $this->Session->write('Config.language', $this->Cookie->read('lang'));
        }
        else if (isset($this->params['language']) && ($this->params['language']
                                                      !=  $this->Session->read('Config.language'))) {
            $this->Session->write('Config.language', $this->params['language']);
            $this->Cookie->write('lang', $this->params['language'], false, '20 days');
        }
        if(!$this->Cookie->read('lang')){
            $this->Cookie->write('lang', Configure::read('Config.language'), false, '20 days');
        }

    }

	function beforeRender()
    {
        $this->set('open_graph', $this->_open_graph_data);

        //publis body class
        if($this->Session->read('Auth.Setting.user.default.locale.value')){
            $this->set('body_class', $this->Session->read('Auth.Setting.user.default.locale.value'));
        }else{
            $this->set('body_class', $this->Cookie->read('lang'));
        }



        // no helpcenter in admin
        if(!in_array($this->params['controller'], array('helpcenter', 'admin'))){

            $locale = $this->Cookie->read('lang');
            $cache_key = 'Helpcenter'.DS.$locale;

            $helpcenter_data = Cache::read($cache_key);

            if(!$helpcenter_data || $helpcenter_data === false){

                $helpcenter_data = array();
                $this->Helppage->contain('Helpelement');
                $data = $this->Helppage->find('all');



                if(count($data) > 0){
                    //prepare data
                    foreach($data as $page){

                        if(count($page['Helpelement']) > 0){
                            $url = $page['Helppage']['controller'].DS.$page['Helppage']['action'];

                            //set default string in header of helpcenter
                            $helpcenter_data[$url]['default'][$locale] = $page['Helppage'][$locale];

                            //prepare data for js
                            foreach($page['Helpelement'] as $element){
                                $helpcenter_data[$url]['elements'][] = array('key' => $element['accessor'], 'value' => $element[$locale]);
                            }
                        }

                    }
                }
                Cache::write($cache_key, $helpcenter_data);
            }//end building cache

            $action = $this->params['action'];
            $controller = $this->params['controller'];
            //special cases for routes
            if($controller == 'posts'){
                if($action == 'edit' || $action == 'add'){
                    $action = 'add_edit';
                }
            }
            if($action == 'viewSubscriptions'){
                $action.= DS.$this->params['own_paper'];
            }
            $url = $controller.DS.$action;

            if(isset($helpcenter_data[$url])){
                $this->set('helpcenter_data', $helpcenter_data[$url]['elements']);
                $this->set('default_helptext', $helpcenter_data[$url]['default'][$locale]);
            }
        }//end if not is admin



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