<?php

require "libs/Social/FacebookOAuth/src/base_facebook.php";
require "libs/Social/FacebookOAuth/src/facebook.php";
require "libs/Social/FacebookOAuth/config.php";

class UsersController extends AppController {

	var $name = 'Users';

	var $components = array('Security', 'ContentPaperHelper', 'RequestHandler', 'JqImgcrop', 'Upload', 'Email', 'Settings', 'Tweet', 'MzSession');
	var $uses = array( 'User', 'Category', /*'Invitation',*/ 'Paper','Group', 'Topic', 'Route', 'ContentPaper', 'Subscription', 'JsonResponse');
	var $helpers = array('MzText', 'MzTime', 'Image', 'Js' => array('Jquery'), 'Reposter', 'Javascript','MzRss');


    var $cacheAction = array(
        //'viewSubscriptions'  => array('callbacks' => true, 'duration' => '+1 month')
        'feed'  => array('callbacks' => true, 'duration' => '+1 month')
    );


	public function beforeFilter(){
        //App::import('Helper', 'MzOpengraph');
        //$og = new MzOpengraphHelper();
        //$og->set('type', 'lala');

       // $this->set('settings', $this->MzSession->getUserSettings());
		parent::beforeFilter();
		$this->Auth->allow('forgotPassword', 'add','login','logout', 'view', 'index', 'viewSubscriptions', 'references', 'feed');

        $this->Security->disabledFields = array('image', 'User.image', 'data');
        $this->log($this->params['action']);
        if(in_array($this->params['action'],array('deleteProfilePicture', 'accImage', 'subscribe'))) {
            $this->log('hier');
            $this->Security->validatePost = false;

        }
	}

    public function beforeRender(){
        $this->_open_graph_data['type'] = 'blog';

        $user = $this->getUserForSidebar();
        if(!empty($user['User']['image'])){
            $img = $user['User']['image'];
            if(!is_array($user['User']['image'])){
                $img = unserialize($user['User']['image']);
            }
            $this->_open_graph_data['image'] = $img[0]['path'];
        }


        //need to be called after setting open_graph
        parent::beforeRender();
    }



	public function login(){




        //check, if the user is already logged in
        if($this->Session->read('Auth.User.id')){
            //redirct to his profile
            $this->redirect($this->Auth->redirect());

        }

		// login with username or email
		// the following code is just for the case that the combination of user.username(!) and user.password did not work:
		//	trying the combination of user.email and user.password

		if(
		!empty($this->data) &&
		!empty($this->Auth->data['User']['username']) &&
		!empty($this->Auth->data['User']['password'])
		){
			$user = $this->User->find('first', array('conditions' => array(
	     											'User.email' => $this->Auth->data['User']['username'],
	     											'User.password' => $this->Auth->data['User']['password']),
     												'recursive' => -1
			));
			if(!empty($user) && $this->Auth->login($user)) {
               
				if($this->Auth->autoRedirect){
					$this->redirect($this->Auth->redirect());
				}
			} else {
				$this->Session->setFlash($this->Auth->loginError);
			}
		}
	}

	function logout()
	{
        $this->Auth->sessionKey = 'Auth';
		$this->redirect($this->Auth->logout());
	}

	function index() {
		//writing all settings for the paginate function.

		$this->paginate = array(
	        'User' => array(
		//limit of records per page
	            'limit' => 12,
		//order
	            'order' => 'User.subscriber_count DESC',
		//fields - custom field sum...
		    	'fields' => array(	'User.id',
                                    'User.image',
								  	'User.username',
		    						'User.name',
		    						'User.created',
		    						'User.repost_count',
		    						'User.post_count',
		    						'User.comment_count',
                                    'User.subscriber_count'
		    						),
		    						//contain array: limit the (related) data and models being loaded per post
	            'contain' => array(),
                'conditions' => array('enabled' => true),
            )
        );

        $this->set('canonical_for_layout', '/authors');
        $this->set('users', $this->paginate());
	}
	/**
	 * @author Tim
	 * Action for the view of a users Blog. Show Basic Information and a Pagination of the user's posts and reposts.
	 * @param $id -
	 * @param $topic_id
	 */
	function view($username = null, $topic_id = null) {


		if (!$username) {

			//no param from url -> get from Auth
			$username = strtolower($this->Auth->User("username"));
			if(!$username){
                $this->Session->setFlash(__('Invalid user', true));
                //$this->redirect(array('action' => 'index'));
                //404
                $this->cakeError('error404');
            }
        }
        //unbinding irrelevant relations for the query
        $this->User->contain('Topic.id', 'Topic.name', 'Topic.post_count');

        $user = $this->getUserForSidebar($username);

        if(!isset($user['User']['id'])){
            $this->Session->setFlash(__('Invalid user', true));
            $this->cakeError('error404');
         //  $this->redirect(array('controller' => 'users', 'action' => 'index'));
        }
        if($user['User']['enabled'] == false){
            $this->Session->setFlash(__('This user has been blocked temporarily due to infringement.', true));
            //redirect to user index - 307 http statuscode temporary redirect
            $this->redirect(array('controller' => 'users', 'action' => 'index'),307);
        }

		/*writing all settings for the paginate function.
		 important here is, that only the user's posts are subject for pagination.*/
		$this->paginate = array(
	        'Post' => array(
		//setting up the join. this conditions describe which posts are gonna be shown
	            'joins' => array(
		array(
	                    'table' => 'posts_users',
	                    'alias' => 'PostUser',
	                    'type' => 'INNER',
	                    'conditions' => array(
	                        'PostUser.post_id = Post.id',
	                		'PostUser.user_id' => $user['User']['id'],
                            'Post.enabled' => true,
		                    ),
                        'contain' => array('Route'),
		                ),
		),
		//limit of records per page
	            'limit' => 12,
		//order
	            'order' => 'PostUser.created DESC',
	            'fields' => array('Post.*', 'PostUser.repost'),
		//contain array: limit the (related) data and models being loaded per post
	            'contain' => array('Route', 'User.id','User.username', 'User.name', 'User.image'),
		)
		);
		if($topic_id != null){
			//adding the topic to the conditions array for the pagination - join
			$this->paginate['Post']['joins'][0]['conditions']['PostUser.topic_id'] = $topic_id;
		}

		$this->set('user', $user);
    
        $this->set('canonical_for_layout', '/u/'.strtolower($user['User']['username']));
        $this->set('rss_for_layout', '/u/'.strtolower($user['User']['username']).'/feed');
		$this->set('posts', $this->paginate($this->User->Post));


        //check for post value to display newConversation form
        $newConversation = (boolean)(isset($this->params['form']['action']) && $this->params['form']['action'] == 'newConversation');
        $this->set('newConversation', $newConversation);

		//references
	/*	$wholeUserReferences = $this->User->getWholeUserReferences($user_id);
		$this->set('wholeUserReferences', $wholeUserReferences);


		//now all references to all topics
		$topicReferences = $this->User->getUserTopicReferences($user_id);

		$this->set('topicReferences', $topicReferences); */
	}

    public function feed($username = null) {
        if (!$username) {

			//no param from url -> get from Auth
			$username = strtolower($this->Auth->User("username"));
			if(!$username){
                $this->Session->setFlash(__('Invalid user', true));
                //$this->redirect(array('action' => 'index'));
                //404
                $this->cakeError('error404');
            }
        }
        //unbinding irrelevant relations for the query
        $this->User->contain();

        $user = $this->getUserForSidebar($username);

        if(!isset($user['User']['id'])){
            $this->Session->setFlash(__('Invalid user', true));
            $this->cakeError('error404');
         //  $this->redirect(array('controller' => 'users', 'action' => 'index'));
        }
        if($user['User']['enabled'] == false){
            $this->Session->setFlash(__('This user has been blocked temporarily due to infringement.', true));
            //redirect to user index - 307 http statuscode temporary redirect
            $this->redirect(array('controller' => 'users', 'action' => 'index'),307);
        }


        $this->paginate = $this->paginate = array(
	        'Post' => array(
		//setting up the join. this conditions describe which posts are gonna be shown
	            'joins' => array(
	            	array(
                       'table' => 'posts_users',
	                    'alias' => 'PostUser',
	                    'type' => 'INNER',
	                    'conditions' => array(
	                        'PostUser.post_id = Post.id',
	                		'PostUser.user_id' => $user['User']['id'],
                            'Post.enabled' => true,
                          ),
                        'contain' => array('Route', 'User'),
		                ),
		),
		//limit of records per page
	            'limit' => 20,
		//order
	            'order' => 'PostUser.created DESC',
	            'fields' => array('Post.*', 'PostUser.repost','User.username','User.name','User.description','User.image'),
		//contain array: limit the (related) data and models being loaded per post
	            'contain' => array('Route', 'User.id','User.username', 'User.name', 'User.image'),
		    )
		);
        $posts = $this->paginate($this->User->Post);
        $this->set('posts' , $posts);
        $this->set('user', $user);

    }


	/**
	 * @author Tim
	 * Action for the view of of a user's subscriptions.
	 * @param $id -
	 * @param $topic_id
	 */
	function viewSubscriptions($username = null, $own_paper = null) {

       if (!$username) {
            //no param from url -> get from Auth
            $username = $this->Auth->User("id");
            if(!$username){
                $this->Session->setFlash(__('Invalid user', true));
                $this->cakeError('error404');
                //$this->redirect(array('action' => 'index'));
            }
        }
        $this->User->contain('Topic.id', 'Topic.name', 'Topic.post_count', 'Paper.id' , 'Paper.title', 'Paper.image');
        $user = $this->getUserForSidebar($username);

        if(!isset($user['User']['id'])){
            $this->Session->setFlash(__('Invalid user', true));
            $this->cakeError('error404');
         //  $this->redirect(array('controller' => 'users', 'action' => 'index'));
        }
        if($user['User']['enabled'] == false){
            $this->Session->setFlash(__('This user has been blocked temporarily due to infringement.', true));
            //redirect to user index - 307 http statuscode temporary redirect
            $this->redirect(array('controller' => 'users', 'action' => 'index'),307);
        }
        /*writing all settings for the paginate function.
           important here is, that only the user's paper subscriptions are subject for pagination.*/
        $this->paginate = array(
	        'Paper' => array(
        //setting up the join. this conditions describe which papers are gonna be shown
	            'joins' => array(
		array(
	                    'table' => 'subscriptions',
	                    'alias' => 'Subscription',
	                    'type' => 'INNER',
	                    'conditions' => array(
	                        'Subscription.paper_id = Paper.id',
	                		'Subscription.user_id' => $user['User']['id'],
                            'Paper.enabled' => true,
        ),
        ),
        ),
        //fields
	            'fields' =>  array('id', 'image', 'owner_id','title','description','created','subscription_count', 'author_count', 'post_count'),
        //limit of records per page
	            'limit' => 16,
        //order
	            'order' => 'Paper.title ASC',
        //contain array: limit the (related) data and models being loaded per post
	            'contain' => array('Route', 'User.id', 'User.image', 'User.username', 'User.name'),


        )
		);
        if($own_paper != null){
            //adding the additional conditions  for the pagination - join
            if($own_paper == Paper::FILTER_OWN){
                $this->paginate['Paper']['joins'][0]['conditions']['Subscription.own_paper'] = true;
            } elseif ($own_paper == Paper::FILTER_SUBSCRIBED){
                $this->paginate['Paper']['joins'][0]['conditions']['Subscription.own_paper'] = false;
            }
        }
      

        //unbinding irrelevant relations for the query
        $this->set('own_paper', $own_paper);
        $this->set('user', $user);
		$papers = $this->paginate($this->User->Paper);

		//add temp variable to papers array: subscribed = true, if user is logged in and has already subscribed the paper
		// @todo !! REDUNDANT users_subscriptions and papers index -> build a component or something like that for this
		if(is_array($papers)){
			for($i = 0; $i < count($papers); $i++){
				$papers[$i]['Paper']['subscribed'] = false;
				if($this->Auth->user('id')){

					//check for subscriptions - if yes -> subscribed = true
					if(($this->Subscription->find('count', array('conditions' => array('Subscription.user_id' => $this->Auth->user('id'),'Subscription.paper_id' => $papers[$i]['Paper']['id'])))) > 0){
						$papers[$i]['Paper']['subscribed'] = true;
					}
				}
			}
		}
        $this->set('canonical_for_layout', '/u/'.strtolower($user['User']['username'].'/papers'));
		$this->set('papers', $papers);


	}


	function add() {
        //check, if the user is already logged in
        if($this->Session->read('Auth.User.id')){
            //redirct to his profile
            $this->redirect(array('controller' => 'users', 'action' => 'view', 'username' => strtolower($this->Session->read('Auth.User.username'))));
        }


		if (!empty($this->data)) {

            $locale = Configure::read('Config.language');
            if($this->Session->read('Config.language')){
                $locale = $this->Session->read('Config.language');
            }
            else if($this->Cookie->read('lang')){
                $locale = $this->Cookie->read('lang');
            }
            $this->data['Setting']['value'] = $locale;

			$this->data['User']['group_id'] = 1;
			$this->User->create();
			$this->User->updateSolr = true;
            $this->log($this->data);
			if ($this->User->save($this->data, true, array('username', 'name' ,'password' , 'passwd','email','group_id', 'tos_accept'))) {

				//after adding user -> add new topic
				//		$newUserId = $this->User->id;
				//	$topicData = array('name' => 'first_automatic_topic', 'user_id' => $newUserId);
				//$this->Topic->create();
				//$this->Topic->save($topicData);



                //send welcome email to new user
                $this->_sendWelcomeEmail($this->User->id);

                //check if someone invited this user
               // $this->_checkForInvitations();

                //Auto-Login after Register,0
                $userData = array('username' => $this->data['User']['username'], 'password' => $this->Auth->password($this->data['User']['passwd']));
                $this->Session->setFlash(__('Thank you for registration.', true), 'default', array('class' => 'success'));
                if($this->Auth->login($userData)){
					$this->redirect(array('controller' => 'users' , 'action' => 'accAboutMe'));
				}


			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));

			}
		}
	}
/*
	function edit($id = null) {

		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect(array('action' => 'index'));
		}

		if (!empty($this->data)) {
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__('The user has been saved', true), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->User->contain();
			$this->data = $this->User->read(null, $id);
		}
		$groups = $this->Group->find('list');
		$this->set('groups',$groups);
	} */
/*
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for user', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->User->delete($id, true)) {
			$this->Session->setFlash(__('User deleted', true), 'default', array('class' => 'success'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('User was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
*/
	/**
	 * show all references from content_papers table to this user
	 * - whole user references (to paper itself or category)
	 * - references to a specific topic (to paper itself or category)
	 * Enter description here ...
	 */
	function references($user_id){


		//check for param in get url
		if(empty($user_id) || !isset($user_id)){
			$this->Session->setFlash(__('No user id passed.', true));
			$this->redirect(array('action' => 2));
		}
		$user_id = $this->params['pass'][0];

		$references = $this->User->getAllUserContentReferences($user_id);
        $this->log($references);
		//debug($references);
		$this->set('references', $references);


		$this->render('references', 'ajax');
	}

	/**
	 * list all paper and categories by logged in user
	 * @param in $user_id - the id of the user, who should be associated with a paper or a category of logged in user
	 */
	function associate($user_id){
		$logged_in_user_id = $this->Session->read('Auth.User.id');

		echo "loged in: " . $logged_in_user_id;

		echo "to associate user:  " . $user_id;
	}


	/**
	 * subscribe a user to a paper /category
	 * @param $user_id - id of user, who should be associatet to a paper / category
	 *
	 */
	function subscribe($user_id = '', $created = false){




        $email_user_id = null;
        $email_topic_id = null;
        $email_paper_id = null;
        $email_category_id = null;

        $logged_in_user_id = $this->Session->read('Auth.User.id');

        if(isset($this->data) && !empty($this->data)){
            //build data
            $this->data['User']['paper_category_content_data'] = '';
            $paper_id = $this->data['User']['paper_content_data'];
            $category = $this->data['User']['category_content_data_'.$paper_id];
            if($category == 'front_page'){
                $this->data['User']['paper_category_content_data'] = 'paper_'.$paper_id;
            }
            else{
                $this->data['User']['paper_category_content_data'] = $category;
            }

			//save subscription and redirect

			//prepare data for association
			$data = array();

			if(isset($this->data['User']['user_topic_content_data'])){
				//process the selected drop down for user/category
				$data['Paper'][ContentPaper::CONTENT_DATA] = $this->data['User']['user_topic_content_data'];

                $target = explode(ContentPaper::SEPERATOR, $this->data['User']['user_topic_content_data']);
				$target_type = $target[0];
                if($target_type == ContentPaper::TOPIC){
                    $email_topic_id = $target[1];
                }
			}
			else{
				//build static content data
				$data['Paper'][ContentPaper::CONTENT_DATA] = ContentPaper::USER.ContentPaper::SEPERATOR.$this->data['User']['user_id'];
			}
			$data['Paper']['user_id'] = $this->data['User']['user_id'];

			$email_user_id = $this->data['User']['user_id'];
			//check if we have options or not
			if(isset($this->data['User']['paper_category_content_data'])){
				//determine what is the target type, paper or categorty ?
				$target = explode(ContentPaper::SEPERATOR, $this->data['User']['paper_category_content_data']);
				$target_type = $target[0];

				//check, if submitted target type is valid
				if(!$this->_validateTargetType($target_type)){
					$this->Session->setFlash(__('Not able to subscribe user/topic to paper - invalid target type', true));

                    $this->redirect($this->referer());
					//$this->redirect(array('controller' => 'users', 'action' => 'view', 'username' => strtolower($this->data['User']['username'])));
				}

				$data['Paper']['target_id'] = $target[1];
				//now, we have a valid target type
				$data['Paper']['target_type'] = $target_type;

				//if target is category -> read paper id from category
				if($target_type == ContentPaper::CATEGORY){
					$category_id = $target[1];
                    $email_category_id = $category_id;
                    $this->Category->contain();
					$category_data = $this->Category->read('paper_id', $category_id);
                    $email_paper_id = $category_data['Category']['paper_id'];
					$this->data['User']['paper_id'] = $category_data['Category']['paper_id'];
				}
				else{
					//taraget is paper
					$this->data['User']['paper_id'] = $target[1];
                    $email_paper_id = $target[1];
      				}

			}
			else{
				//only one paper without category
				$data['Paper']['target_id'] = $this->data['User']['paper_id'];
                $email_paper_id = $this->data['User']['paper_id'];

				//now, we have a valid target type
				$data['Paper']['target_type'] = ContentPaper::PAPER;
			}



			if($this->Paper->read(null, $this->data['User']['paper_id'])){

				//save association with prepared data
                $return_code = $this->Paper->associateContent($data);
				if(in_array($return_code,$this->Paper->return_codes_success)){
					$msg = $this->Paper->return_code_messages[$return_code];

                    $this->_sendSubscriptionEmail($email_user_id, $email_topic_id, $email_paper_id, $email_category_id);
					$this->Session->setFlash($msg,'default', array('class' => 'success'));

                    $this->redirect($this->referer());
					//$this->redirect(array('controller' => 'users', 'action' => 'view', 'username' => strtolower($this->data['User']['username'])));
				}
				else{
                    $msg = $this->Paper->return_code_messages[$return_code];
					$this->Session->setFlash($msg, true);

					//$this->redirect(array('controller' => 'users', 'action' => 'view', 'username' => strtolower($this->data['User']['username'])));
                    $this->redirect($this->referer());

				}
			}
			else{

			}
		}


		if(empty($user_id)){
			$this->Session->setFlash(__('No user param', true));
			$this->redirect(array('action' => 'view', 'username' => strtolower($this->Session->read('Auth.User.username'))));
		}

		//get paper of logged in user
		$this->Paper->contain('Category');//load only paper data
		$papers = $this->Paper->find('all', array('conditions' => array('Paper.owner_id' => $logged_in_user_id, 'Paper.enabled' => true)));

		if(count($papers) == 0){
			//user has no paper
			//create new initial paper for user
            $owner_id = $logged_in_user_id;
            $title = __('myZeitung', true);
            $data = array('Paper' => array('owner_id' => $owner_id,
                                           'title'    => $title,
                                           'description' => ''
                                            ));

            $this->Paper->create();
			if ($this->Paper->save($data)) {

                //perform recursion
                return $this->subscribe($user_id, true);
            }

		}


		//determine to show the user a selection of papers / categories or not
		$show_options = false;
		$has_more_papers = false;
		$has_one_paper = false;
		$has_categories = false;
		if(count($papers) == 1){
			$has_one_paper = true;
			//only one paper available

			if(isset($papers[0]['Category'][0])){
				//show select
				$has_categories = true;
				$show_options = true;
				//debug('has categoriys');
			}
			else{
				//debug('has not categoriys');
				$show_options = false;
			}
		}
		else if(count($papers) > 1){
			$show_options = true;
			$has_more_papers = true;
		}

		//check, if the user has a topic or not
		$has_topics = false;
		$this->User->contain('Topic');
		$user_data = $this->User->read(null, $user_id);
		if(!isset($user_data['User']['id']) || empty($user_data['User']['id'])){
			$this->Session->setFlash(__('Can not load user', true));
			$this->redirect($this->referer());
		}
		if(isset($user_data['Topic'][0])){
			//has at least one topic -> show options to select
			$show_options = true;
			$has_topics = true;
		}

		if($show_options){

            $json_data = array();

			//set user id, who will be subscribed, for view

			//$json_data['user_id'] = $user_id;
            $json_data['User'] = $user_data['User'];
           //@todo alf bitte username in die formulardaten packen, damit der redirect oben funzen kann
          //   $this->set('username', $user_data['User']['username']);

			//the user has at least one paper with one category in it
			if($has_one_paper){

				//user has exactly one paper
				$json_data['paper_id'] = $papers[0]['Paper']['id'];

				if($has_categories){
					//only one paper given with one or more category the user gets as an option
					$paper_category_drop_down = $this->_generatePaperSelectData($logged_in_user_id);
					$json_data['paper_category_chooser'] = $paper_category_drop_down;
				}
				else{
					//no paper / category options -> just display paper name
					$json_data['paper_name'] = $papers[0]['Paper']['title'];
                    $this->log($created);
                    $json_data['created'] = $created;
                    $json_data['paper'] = $papers[0];


				}
			}


			if($has_more_papers){
				//read all papers
				$paper_category_drop_down = $this->_generatePaperSelectData($logged_in_user_id);
				$json_data['paper_category_chooser'] = $paper_category_drop_down;
			}

			if($has_topics){
				//the user who wil be associated, has one or more topics. choose whole user or a specifict topic
				$user_topic_drop_down = $this->_generateUserSelectData($user_id);
				$json_data['user_topic_chooser'] = $user_topic_drop_down;
			}

            $this->set(JsonResponse::RESPONSE, $this->JsonResponse->success($json_data));


			//die();
			//$this->redirect(array('action' => 'associate', $user_id));
		}
		else{

			//one paper, no category, no topics for user
			//read paper, prepare data, associate
			$paper_id = $papers[0]['Paper']['id'];
            $this->Paper->contain();

			if($this->Paper->read(null, $paper_id)){

				//prepare data
				$data = array();

				//@todo : implement if user has a topic
				$data['Paper'][ContentPaper::CONTENT_DATA] = ContentPaper::USER.ContentPaper::SEPERATOR.$user_id;
				$data['Paper']['target_type'] = ContentPaper::PAPER;
				$data['Paper']['target_id'] = $paper_id;
              /*
                    debug($data);
                    debug($email_user_id);
                    debug($email_topic_id);
                    debug($email_paper_id);
                    debug($email_category_id);die();*/
                     $email_paper_id = $paper_id;
                     $email_user_id = $user_id;

				    $return_code =$this->Paper->associateContent($data);
                    if(in_array($return_code,$this->Paper->return_codes_success)){
					$msg = $this->Paper->return_code_messages[$return_code];

                    $this->_sendSubscriptionEmail($email_user_id, $email_topic_id, $email_paper_id, $email_category_id);
					$this->Session->setFlash($msg,'default', array('class' => 'success'));


                    $this->set(JsonResponse::RESPONSE, $this->JsonResponse->customStatus('reload'));
					//$this->redirect(array('controller' => 'users', 'action' => 'view', $this->data['User']['user_id']));

				}
				else{
                    $msg = $this->Paper->return_code_messages[$return_code];
					$this->Session->setFlash($msg, true);
                    $this->set(JsonResponse::RESPONSE, $this->JsonResponse->customStatus('reload'));


				}

				//echo 'macht user ' . $user_id . ' in paper ' .$papers[0]['Paper']['id'];
			}
			else{
				$this->Session->setFlash(__('Error while reading paper', true));
                $this->set(JsonResponse::RESPONSE, $this->JsonResponse->customStatus('reload'));
				//$this->redirect(array('action' => 'view', $logged_in_user_id));

			}

		}
        //$this->render('subscribe', 'ajax');

	}

	/**
	 * build data for dropdown so select user or topic
	 * to as reference for paper_content
	 *
	 * @return array()
	 */
	private function _generateUserSelectData($user_id){
		$content_data = array();

		if($user_id){


			//build array for user
			$this->User->contain('Topic');
			$users = $this->User->find('all', array('conditions' => array('User.id' => $user_id)));
			foreach($users as $user){
				$content_data['options'][ContentPaper::USER.ContentPaper::SEPERATOR.$user['User']['id']] = __('All articles', true);
				$topics = $user['Topic'];
				if(isset($topics) && count($topics >0)){
					foreach($topics as $topic){
						$content_data['options'][ContentPaper::TOPIC.ContentPaper::SEPERATOR.$topic['id']] = $topic['name'];
					}

				}//all topics
				//debug($content_data);die();
			}//all users
		}//if user id

		return $content_data;
	}

	/**
	 * list all paper with containing categories for dropdown by user
	 * Enter description here ...
	 */
	private function _generatePaperSelectData($user_id){
		$content_data = array();

		if($user_id){
			$this->Paper->contain(array('Category'));
			$paper_data = $this->Paper->find('all', array('conditions' => array('Paper.enabled' => true,  'Paper.owner_id' => $user_id)));

			foreach($paper_data as $paper){
                $this->log($paper);
                $categories = array();
				//$content_data['options'][ContentPaper::PAPER.ContentPaper::SEPERATOR.$paper['Paper']['id']] = $paper['Paper']['title'];//.' (' .('front page').')'
                $content_data['options'][$paper['Paper']['id']] = $paper['Paper']['title'];//.' (' .('front page').')'
				if(isset($paper['Category']) && count(isset($paper['Category']) > 0)){
                    $content_data['categories'][$paper['Paper']['id']]['options']['front_page'] = __('Front Page', true);
                    $content_data['categories'][$paper['Paper']['id']]['paper_id'] = $paper['Paper']['id'];
                    $content_data['categories'][$paper['Paper']['id']]['paper'] = $paper;
					foreach ($paper['Category'] as $category){
						//$content_data['options'][ContentPaper::CATEGORY.ContentPaper::SEPERATOR.$category['id']] = '    '.$category['name'].' (category)';
                        $content_data['categories'][$paper['Paper']['id']]['options'][ContentPaper::CATEGORY.ContentPaper::SEPERATOR.$category['id']] = $category['name'];
					}

				}
			}
		}
		return $content_data;
	}

	/**
	 * checks if target type is valid
	 *
	 * @param string $target_type
	 * @return boolean
	 */
	private function _validateTargetType($target_type){
		return ($target_type == ContentPaper::PAPER || $target_type == ContentPaper::CATEGORY);
	}

	/*
	 function ajxSubscribe(){
	 $json = array();
	 $json['type'] = 'errorrrr';

	 $this->set('return', $json);

	 $this->render('ajx_subscribe');//file users/json/ajx_subscribe.ctp with layouts/json/default.ctp
	 }
	 */
    function forgotPassword() {
        //check, if the user is already logged in
        if($this->Session->read('Auth.User.id')){
            //redirct to his profile
            $this->redirect(array('controller' => 'users', 'action' => 'view', 'username' => strtolower($this->Session->read('Auth.User.username'))));
        }

      if(!empty($this->data)) {
        $this->User->contain();
        $user = $this->User->findByEmail($this->data['User']['email']);
        if($user) {
          $user['User']['tmp_password'] = $this->User->createTempPassword(7);
          $user['User']['password'] = $this->Auth->password($user['User']['tmp_password']);
          if($this->User->save($user, false, array('password'))) {
            $this->_sendPasswordEmail($user['User']['id'], $user['User']['tmp_password']);
            $this->Session->setFlash('An email has been sent with your new password.','default', array('class' => 'success'));
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
          }
        } else {
          $this->Session->setFlash('No user was found with the submitted email address.');
        }
      }
    }

	function accGeneral(){

		$id = $this->Session->read('Auth.User.id');

		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect($this->referer());
		}

		if (!empty($this->data)) {
            $this->data['User']['id'] = $this->Session->read('Auth.User.id');
			//save/validate email AND password
			if(!empty($this->data['User']['old_password']) || !empty($this->data['User']['passwd']) || !empty($this->data['User']['passwd_confirm']) ){

				$this->User->contain();
				$this->User->read('password', $id);
				if ($this->User->save($this->data, true, array('email', 'password', 'old_password', 'passwd', 'passwd_confirm'))) {
					$this->Session->setFlash(__('The changes have been saved', true), 'default', array('class' => 'success'));
					//clear password fields
					$this->data['User']['old_password'] = '';
					$this->data['User']['passwd'] = '';
					$this->data['User']['passwd_confirm'] = '';
					//update session variables:
					$this->Session->write("Auth.User.email", $this->data['User']['email']);
				} else {
					$this->Session->setFlash(__('The changes could not be saved. Please, try again.', true));
				}
			//just save/validate email
			}else{
				if ($this->User->save($this->data, true, array('email'))) {
					$this->Session->setFlash(__('The changes have been saved', true), 'default', array('class' => 'success'));

					//update session variables:
					$this->Session->write("Auth.User.email", $this->data['User']['email']);
				} else {
					$this->Session->setFlash(__('The changes could not be saved. Please, try again.', true));
				}
			}
		}
		if (empty($this->data)) {
			$this->User->contain();
			$this->data = $this->User->read(null, $id);
		}

		$this->User->contain();
		$user= $this->getUserForSidebar();
		$this->set('user', $user);
        $this->set('hash', $this->Upload->getHash());
	}



	function accAboutMe(){

		$id = $this->Session->read('Auth.User.id');

		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect($this->referer());
		}

		if (!empty($this->data)) {
            $this->data['User']['id'] = $this->Session->read('Auth.User.id');
			$this->User->updateSolr = true;
			if ($this->User->save($this->data, true, array('name', 'description', 'url'))) {
				$this->Session->setFlash(__('The changes have been saved', true), 'default', array('class' => 'success'));
				//update session variables:
				$this->Session->write("Auth.User.name", $this->data['User']['name']);
				$this->Session->write("Auth.User.description", $this->data['User']['description']);
			} else {
				$this->Session->setFlash(__('The changes could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->User->contain();
			$this->data = $this->User->read(null, $id);
		}

		$this->User->contain();
		$user= $this->getUserForSidebar();
		$this->set('user', $user);
        $this->set('hash', $this->Upload->getHash());
	}

    function accSocial(){
        $id = $this->Session->read('Auth.User.id');

		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect($this->referer());
		}

        $settings = $this->Settings->get($id);
        $this->data['User']['use_twitter'] = false;

        //always the same url
        $tw_toggle_url =  $callback_url = Router::url('/twitter/toggle', true);
        $this->set('tw_toggle_url', $tw_toggle_url);
        if($this->Tweet->useTwitter()){
            $this->data['User']['use_twitter'] = true;
            $userProfile = $this->Tweet->getUserProfile();
            if(is_array($userProfile)){
                $this->data['User']['twitter_account_data'] = $userProfile;
            }
        }


        $this->data['User']['use_fb'] = false;
        $facebook = new Facebook(array(
            'appId'  => FB_APP_ID,
            'secret' => FB_APP_SECRET,
            'cookie' => true
        ));
        // Get User ID
        $user = $facebook->getUser();
        if ($user) {
            $this->set('fb_user', $user);
            $this->data['User']['use_fb'] = true;

            if ($user) {
              try {
                // Proceed knowing you have a logged in user who's authenticated.
                $user_profile = $facebook->api('/me');
                $this->data['User']['fb_account_data'] = $user_profile;
              } catch (FacebookApiException $e) {
                    //echo($e);
                    $user = null;
                    $this->data['User']['use_fb'] = false;
                }
            }
        }
        if ($user) {
            $logoutUrl = $facebook->getLogoutUrl();
            $this->set('fb_url', $logoutUrl);
        }
        else{
            $loginUrl = $facebook->getLoginUrl(array('scope' => 'publish_stream'));
            $this->set('fb_url', $loginUrl);
        }



		$this->User->contain();
		$user= $this->getUserForSidebar();
		$this->set('user', $user);
        $this->set('hash', $this->Upload->getHash());
    }

	function accPrivacy(){

		$id = $this->Session->read('Auth.User.id');

		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect($this->referer());
		}

        $this->User->contain();
        $user= $this->getUserForSidebar($this->Session->read('Auth.User.username'));

		if (!empty($this->data)) {

            /* saving each entry - creating new entry if no specific id is set.
                                - just saving if id is set
            */
            App::import('model','Setting');
            $this->Setting = new Setting();

            $user['Setting']['user']['default']['allow_comments']['value'] = $this->data['User']['allow_comments'];
            $user['Setting']['user']['default']['allow_messages']['value'] = $this->data['User']['allow_messages'];
            $user['Setting']['user']['email']['new_comment']['value'] = $this->data['User']['email_new_comment'];
            //$user['Setting']['user']['email']['invitee_registered']['value'] = $this->data['User']['email_invitee_registered'];
            $user['Setting']['user']['email']['new_message']['value'] = $this->data['User']['email_new_message'];
            $user['Setting']['user']['email']['subscription']['value'] = $this->data['User']['email_subscription'];

            $this->Settings->save($user['Setting'], $this->Session->read('Auth.User.id'));

            $this->Session->setFlash(__('The changes have been saved', true), 'default', array('class' => 'success'));
		}

		if(empty($this->data)) {
            $this->data['User']['allow_messages']               = $user['Setting']['user']['default']['allow_messages']['value'];
            $this->data['User']['allow_comments']               = $user['Setting']['user']['default']['allow_comments']['value'];
            $this->data['User']['email_new_comment']            = $user['Setting']['user']['email']['new_comment']['value'];
            $this->data['User']['email_invitee_registered']     = $user['Setting']['user']['email']['invitee_registered']['value'];
            $this->data['User']['email_new_message']            = $user['Setting']['user']['email']['new_message']['value'];
            $this->data['User']['email_subscription']           = $user['Setting']['user']['email']['subscription']['value'];
        }

		$this->set('user', $user);
        $this->set('hash', $this->Upload->getHash());
	}

	function accDelete(){

		$id = $this->Session->read('Auth.User.id');


		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect($this->referer());
		}

		if (!empty($this->data)) {

			if($this->data['User']['delete'] == true){
				if($this->User->delete($id, true)) {

					$this->Session->setFlash(__('Your account has been deleted.', true), 'default', array('class' => 'success'));
					$this->redirect(array('controller' => 'users', 'action' => 'logout'));
				} else {
					$this->Session->setFlash(__('Your account could not be deleted.', true));
					$this->log('User/accDelete: user '.$id.' could not delete his/her account');
				}
			}
		}

		$this->User->contain();
		$user= $this->getUserForSidebar();
		$this->set('user', $user);
        $this->set('hash', $this->Upload->getHash());
	}

	/**
	 * handle upload of profile picture
	 *
	 */
	function accImage(){
		if(!empty($this->data)){

			//check, if user exists
			$this->User->contain();
			$user_data = $this->User->read(null, $this->Session->read('Auth.User.id'));
			if(!isset($user_data['User']['id'])){
				$this->Session->setFlash(__('Error while loading user', true));
				$this->redirect($this->referer());
			}

			if($this->Upload->hasImagesInHashFolder($this->data['User']['hash'])){
				$image = array();
				$user_id = $this->Session->read('Auth.User.id');
				$user_created = $user_data['User']['created'];
				$image = $this->Upload->copyImagesFromHash($this->data['User']['hash'], $user_id, $user_created, $this->data['User']['new_image'], 'user');

				if(is_array($image)){
					$this->User->contain();
					$user_data = $this->User->read(null, $this->Session->read('Auth.User.id'));
					$user_data['User']['image'] = $image;

					$this->User->updateSolr = true;

					if($this->User->save($user_data, true, array('image'))){
   						$this->Session->setFlash(__('Your new profile picture has been saved.', true), 'default', array('class' => 'success'));

						$this->Session->write("Auth.User.image", $user_data['User']['image']);
						$this->redirect($this->referer());
					}
					else{
						$this->Session->setFlash(__('Could not save profile picture, please try again.', true));
						$this->redirect($this->referer());
					}

				}
			}
			else{
				$this->Session->setFlash(__('Could not save profile picture, please try again.', true));
				$this->redirect($this->referer());
			}
		}
		$this->User->contain();
		$this->set('user', $this->getUserForSidebar());
		$this->set('hash', $this->Upload->getHash());
	}

    function accInvitations(){
        App::import('model','Invitation');
        $this->Invitation = new Invitation();
        $user_id = $this->Session->read('Auth.User.id');

		if (!$user_id) {
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect($this->referer());
		}
        $this->paginate = array(
                'Invitation' => array(
            //limit of records per page
                    'limit' => 9,
            //order
                    'order' => 'Invitation.created DESC',
            //fields - custom field sum...
                    'fields' => array(),
                    'conditions' => array('Invitation.user_id' => $user_id),
            //contain array: limit the (related) data and models being loaded per post
                    'contain' => array('Invitee'),
            )
        );
        $invitations = $this->paginate("Invitation");
        
       
        //check if a user is already registered,
        /*foreach($invitations as &$invitation){
            foreach($invitation['Invitee'] as &$invitee){
                $this->User->contain();

                $user = $this->User->find('first', array('conditions' => array('User.email' => $invitee['email']),'fields' => array('id', 'username','name','email')));
                $invitee['User'] = $user['User'];

            }

        }*/
        
        $this->set('user', $this->getUserForSidebar());
        $this->set('invitations', $invitations);
        $this->set('hash', $this->Upload->getHash());
    }



	/**
	 * handle upload of user profile image
	 *
	 */
	function ajxProfileImageProcess(){

		if(isset($_FILES['file'])){


			$file = $_FILES['file'];

			if(!isset($_POST['hash']) || empty($_POST['hash'])){
				$this->log('error. hash value not available. can not upload picture');
				return '{"name":"error"}';
			}
			$hash = $_POST['hash'];
			$img = $file['name'];
			if(!$img){
				return '{"name":"error"}';
			}

			$imgPath = 'img'.DS.'tmp'.DS.$hash.DS;

			//remove whitespace etc from img name
			$file['name'] = $this->Upload->transformFileName($file['name']);

			$uploaded = $this->JqImgcrop->uploadImage($file, $imgPath, '');

			$ret = '{"name":"'.$file['name'].'","path":"' . $imgPath.$file['name'] . '","type":"'.$file['type'].'","size":"'.$file['size'].'"}';
			//$this->log($ret);
			$this->set('files', $ret);
		}

		$this->render('ajxProfileImageProcess', 'ajax');//custom ctp, ajax for blank layout


	}

	/**
	 * reading user from session or db, depending of the view.
	 * IMPORTANT : containments must be defined in the action
	 * @param unknown_type $user_id
	 */
	protected function getUserForSidebar($username = null){
		$user = array();
        if($username == null){
		//reading logged in user from session
			$user = $this->Session->read('Auth');
            if(!isset($user['Setting'])){
                $settings = $this->User->getSettings($this->Session->read('Auth.User.id'));
                $this->Session->write('Auth.Setting', $settings);
                $user['Setting'] = $settings;
            }

		} else {
		//reading user
            $user = $this->User->getUserForSidebar(strtolower($username));
		}
        
        return $user;
	}

    protected function _sendWelcomeEmail($user_id){
        $this->User->contain();
        $user = $this->User->read(null, $user_id);

        //setting params for template
        $this->set('recipient', $user);
        //send mail
        $this->_sendMail($user['User']['email'], __('Welcome to myZeitung', true),'welcome');
    }
    protected function _sendPasswordEmail($user_id, $password) {
        $this->User->contain();
        $user = $this->User->read(null, $user_id);
        $userSettings = $this->User->getSettings($user_id);


        $tempLocale = $this->Session->read('Config.language');

        $this->Session->write('Config.language', $userSettings['user']['default']['locale']['value']);

      $this->set('recipient', $user);
      $this->set('password', $password);
      $this->_sendMail($user['User']['email'], __('Password change request', true),'forgot_password');
      $this->Session->write('Config.language', $tempLocale);

      $this->Session->setFlash(__('A new password has been sent to your supplied email address.',true));
    }

    /**
     * send an email to a user that got subscribed by another user.
     */
    protected function _sendSubscriptionEmail($user_id, $topic_id, $paper_id, $category_id) {
        $userSettings = $this->User->getSettings($user_id);

        if($userSettings['user']['email']['subscription']['value'] == true){



             $tempLocale = $this->Session->read('Config.language');

            $this->Session->write('Config.language', $userSettings['user']['default']['locale']['value']);
            $user = array();
            $topic = null;
            $paper = array();
            $category = null;

            $this->Paper->contain('Route', 'User.id','User.name', 'User.username');
            $paper = $this->Paper->read(array('id', 'title', 'owner_id'), $paper_id);
            //send only an email if the user did not subscribe himself
            if($paper['Paper']['owner_id'] != $user_id){
                $this->User->contain();
                $user = $this->User->read(array('id', 'username', 'name', 'email'), $user_id);

                if($topic_id != null){
                    $this->Topic->contain();
                    $topic = $this->Topic->read(array('id', 'name'), $topic_id);
                }
                if($category_id != null){
                    $this->Category->contain();
                    $category = $this->Category->read(array('id', 'name'), $category_id);
                }


                $this->set('recipient', $user);
                $this->set('paper', $paper);
                $this->set('category', $category);
                $this->set('topic', $topic);

                $this->_sendMail($user['User']['email'], __('You have been subscribed', true),'subscription');
                $this->Session->write('Config.language', $tempLocale);
            }
        }
    }

    function deleteProfilePicture(){
        if(!empty($this->data)){
            $user_id = $this->Session->read('Auth.User.id');
            $this->User->updateSolr = true;
            $data = array('User' => array('id' => $user_id,
                                          'image' => NULL,
                                           'username' => $this->Session->read('Auth.User.username'),
                                           'name'  => $this->Session->read('Auth.User.name')));

            if($this->User->save($data, true, array('id','image','username','name'))){
                $this->Session->write('Auth.User.image', '');
                $this->Session->setFlash(__('Your Profile Picture has been removed', true),'default', array('class' => 'success'));
                $this->redirect('/settings');
            }
        }
		$this->User->contain();
		$user= $this->getUserForSidebar();
		$this->set('user', $user);
        $this->set('hash', $this->Upload->getHash());
    }

    protected function _checkForInvitations(){
        App::import('model','Invitee');
        $this->Invitee = new Invitee();

        $this->Invitee->contain('Invitation', 'Invitation');
        $invitations = $this->Invitee->findAllByEmail($this->data['User']['email']);
        $new_user_id = $this->User->id;
        foreach($invitations as $invitation){
            $this->_sendMailForInviteeRegistration($invitation, $new_user_id);
            $this->_generateConversationForRegisteredInvitee($invitation, $new_user_id);
        }
    }

    protected function _generateConversationForRegisteredInvitee($invitation, $invitee_id){
        App::import('model','Conversation');
        $this->Conversation = new Conversation();
        $this->Conversation->generateConversationForRegisteredInvitee($invitation, $invitee_id);
    }

    protected function _sendMailForInviteeRegistration($invitation, $invitee_id){
        $this->User->contain();

        $invitee = $this->User->read(null, $invitee_id);
        $userSettings = $this->User->getSettings($invitee_id);

        if($userSettings['user']['email']['invitee_registered']['value'] == true){
             $tempLocale = $this->Session->read('Config.language');

             $this->Session->write('Config.language', $userSettings['user']['default']['locale']['value']);
            $this->User->contain();
            $recipient = $this->User->read(null, $invitation['Invitation']['user_id']);
            //setting params for template
            $this->set('invitee', $invitee );
            $this->set('recipient', $recipient);

            //send mail
            $this->_sendMail($recipient['User']['email'], __('An Invitee has just registered',true), 'invitee_registered');

            $this->Session->write('Config.language', $tempLocale);
        }
    }

	function admin_index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('user', $this->User->read(null, $id));
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->User->create();
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__('The user has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
			}
		}
		$groups = $this->User->Group->find('list');
		$this->set(compact('groups'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__('The user has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->User->read(null, $id);
		}

		$groups = array(AppController::ROLE_USER => __('Author', true),
                        AppController::ROLE_ADMIN => __('Admin', true),
                        AppController::ROLE_SUPERADMIN => __('Superadmin', true));

		$this->set(compact('groups'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for user', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->User->delete($id)) {
			$this->Session->setFlash(__('User deleted', true),'default', array('class' => 'success'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('User was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
    function admin_disable($user_id){
        $this->User->contain();
        $this->User->data = $this->User->read(null, $user_id);
        if(isset($this->User->data['User']['id']) && !empty($this->User->data['User']['id'])){
            if($this->User->data['User']['enabled'] == false){
                $this->Session->setFlash('This user is already disabled');
                $this->redirect($this->referer());
            }else{
                if($this->User->disable()){
                    $this->Session->setFlash('User has been disabled successfully','default', array('class' => 'success'));
                    $this->redirect($this->referer());
            $this->redirect($this->referer());
                }else{
                    $this->Session->setFlash('This user could not be disabled. Please try again.');
                    $this->redirect($this->referer());
                }
            }
        }else{
            $this->Session->setFlash('Invalid user');
            $this->redirect($this->referer());

        }
    }
    function admin_enable($user_id){
        $this->User->contain();
        $this->User->data = $this->User->read(null, $user_id);

        if(isset($this->User->data['User']['id']) && !empty($this->User->data['User']['id'])){
            if($this->User->data['User']['enabled'] == true){
                $this->Session->setFlash('This user is already enabled');
                $this->redirect($this->referer());
            }else{
                if($this->User->enable()){
                    $this->Session->setFlash('User has been enabled successfully','default', array('class' => 'success'));
                    $this->redirect($this->referer());
            $this->redirect($this->referer());
                }else{
                    $this->Session->setFlash('This user could not be enabled. Please try again.');
                    $this->redirect($this->referer());
                }
            }
        }else{
            $this->Session->setFlash('Invalid user');
            $this->redirect($this->referer());

        }
    }

    function admin_toggleVisible($type, $id = null){

		if (!$type || (!in_array($type, array('home', 'index'))) ||!$id) {
			$this->Session->setFlash(__('Invalid id for user', true));
			$this->redirect($this->referer());
		}
        $field = 'visible_home';
        if($type == 'index'){
            $field = 'visible_index';
        }

        $this->User->contain();
        $user = $this->User->read(null, $id);

        $this->User->set($field, !((boolean)$user['User'][$field]));

        if($this->User->save()){
            $this->Session->setFlash(__('User saved', true), 'default', array('class' => 'success'));
            $this->redirect($this->referer());
        }
        else{
            $this->Session->setFlash(__('Could not save user, please try again.', true));
            $this->redirect($this->referer());
        }

        // second param = cascade -> delete associated records from hasmany , hasone relations

    }


}
