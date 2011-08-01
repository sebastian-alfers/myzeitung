<?php
class UsersController extends AppController {

	var $name = 'Users';
	var $components = array('ContentPaperHelper', 'RequestHandler', 'JqImgcrop', 'Upload', 'Email');
	var $uses = array('User', 'Category', 'Paper','Group', 'Topic', 'Route', 'ContentPaper', 'Subscription');
	var $helpers = array('Text', 'MzTime', 'Image', 'Js' => array('Jquery'), 'Reposter');


	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('forgotPassword', 'add','login','logout', 'view', 'index', 'viewSubscriptions', 'references');

	}


	public function login(){
        //check, if the user is already logged in
        if($this->Session->read('Auth.User.id')){
            //redirct to his profile
            $this->redirect(array('controller' => 'users', 'action' => 'view'));
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

		$this->redirect($this->Auth->logout());
	}

	function index() {
		//writing all settings for the paginate function.

		$this->paginate = array(
	        'User' => array(
		//limit of records per page
	            'limit' => 12,
		//order
	            'order' => 'User.content_paper_count DESC',
		//fields - custom field sum...
		    	'fields' => array(	'User.id',
                                    'User.image',
								  	'User.username',
		    						'User.name',
		    						'User.created',
		    						'User.posts_user_count',
		    						'User.post_count',
		    						'User.comment_count',
                                    'User.content_paper_count'
		    						),
		    						//contain array: limit the (related) data and models being loaded per post
	            'contain' => array(),
                'conditions' => array('enabled' => true),
            )
        );


        $this->set('users', $this->paginate());
	}
	/**
	 * @author Tim
	 * Action for the view of a users Blog. Show Basic Information and a Pagination of the user's posts and reposts.
	 * @param $id -
	 * @param $topic_id
	 */
	function view($user_id = null, $topic_id = null) {

		if (!$user_id) {
			//no param from url -> get from Auth
			$user_id = $this->Auth->User("id");
			if(!$user_id){
				$this->Session->setFlash(__('Invalid user', true));
				$this->redirect(array('action' => 'index'));
			}
		}
        //unbinding irrelevant relations for the query
        $this->User->contain('Topic.id', 'Topic.name', 'Topic.post_count', 'Paper.id' , 'Paper.title', 'Paper.image');
        $user = $this->getUserForSidebar($user_id);
        if($user['User']['enabled'] == false){
           $this->Session->setFlash(__('This user has been blocked temporarily due to infringement.', true));
           $this->redirect($this->referer());
       }


		//check, if user exists in db
		$this->User->contain();
		$user_data = $this->User->read('id', $user_id);
		if(!isset($user_data['User']['id'])){
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect(array('controller' => 'users', 'action' => 'view', $this->Auth->User("id")));
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
	                		'PostUser.user_id' => $user_id,
                            'Post.enabled' => true,
		),
		),
		),
		//limit of records per page
	            'limit' => 9,
		//order
	            'order' => 'PostUser.created DESC',
	            'fields' => array('Post.*', 'PostUser.repost'),
		//contain array: limit the (related) data and models being loaded per post
	            'contain' => array( 'User.id','User.username', 'User.name', 'User.image'),
		)
		);
		if($topic_id != null){
			//adding the topic to the conditions array for the pagination - join
			$this->paginate['Post']['joins'][0]['conditions']['PostUser.topic_id'] = $topic_id;
		}


		$this->set('user', $user);

		$this->set('posts', $this->paginate($this->User->Post));

		//references
	/*	$wholeUserReferences = $this->User->getWholeUserReferences($user_id);
		$this->set('wholeUserReferences', $wholeUserReferences);


		//now all references to all topics
		$topicReferences = $this->User->getUserTopicReferences($user_id);

		$this->set('topicReferences', $topicReferences); */
	}

	/**
	 * @author Tim
	 * Action for the view of of a user's subscriptions.
	 * @param $id -
	 * @param $topic_id
	 */
	function viewSubscriptions($user_id = null, $own_paper = null) {

        if (!$user_id) {
            //no param from url -> get from Auth
            $user_id = $this->Auth->User("id");
            if(!$user_id){
                $this->Session->setFlash(__('Invalid user', true));
                $this->redirect(array('action' => 'index'));
            }
        }
        $this->User->contain('Topic.id', 'Topic.name', 'Topic.post_count', 'Paper.id' , 'Paper.title', 'Paper.image');
        $user = $this->User->read(array('id','enabled','name','username','created','image' ,'posts_user_count','post_count','comment_count', 'content_paper_count', 'subscription_count', 'paper_count', 'allow_messages'), $user_id);
        if($user['User']['enabled'] == false){
            $this->Session->setFlash(__('This user has been blocked temporarily due to infringement.', true));
			$this->redirect($this->referer());
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
	                		'Subscription.user_id' => $user_id,
                            'Paper.enabled' => true,
        ),
        ),
        ),
        //fields
	            'fields' =>  array('id', 'image', 'owner_id','title','description','created','subscription_count', 'content_paper_count', 'category_paper_post_count'),
        //limit of records per page
	            'limit' => 9,
        //order
	            'order' => 'Paper.title ASC',
        //contain array: limit the (related) data and models being loaded per post
	            'contain' => array('User.id', 'User.image', 'User.username', 'User.name'),


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
		$this->set('papers', $papers);


	}


	function add() {
        //check, if the user is already logged in
        if($this->Session->read('Auth.User.id')){
            //redirct to his profile
            $this->redirect(array('controller' => 'users', 'action' => 'view'));
        }


		if (!empty($this->data)) {
			$this->data['User']['group_id'] = 1;
			$this->User->create();
			$this->User->updateSolr = true;
			if ($this->User->save($this->data)) {

				//after adding user -> add new topic
				//		$newUserId = $this->User->id;
				//	$topicData = array('name' => 'first_automatic_topic', 'user_id' => $newUserId);
				//$this->Topic->create();
				//$this->Topic->save($topicData);

				//@todo move it to a better place -> to user model
				//afer adding user -> add new route
				/*		$route = new Route();
				$route->create();

				if( $route->save(array('source' => $this->data['User']['username'] ,
				'target_controller' 	=> 'users',
				'target_action'     	=> 'view',
				'target_param'		=> $this->User->id)))
				{
				if($route->id){
				$this->data['User']['route_id'] = $route->id;
				$this->User->save($this->data);
				$this->redirect('/'.$this->data['User']['username']);
				}
				else{
				$this->Session->setFlash(__('The user has been saved', true));
				$this->redirect(array('action' => 'add'));
				}
				}
				else{
				$this->Session->setFlash(__('Please choose a valid url key', true));
				$this->redirect(array('action' => 'add'));
				}
				*/

                //send welcome email to new user
                $this->_sendWelcomeEmail($this->User->id);
                //Auto-Login after Register
                $userData = array('username' => $this->data['User']['username'], 'password' => $this->Auth->password($this->data['User']['passwd']));
                $this->Session->setFlash(__('Thank you for registration.', true), 'default', array('class' => 'success'));
                if($this->Auth->login($userData)){
					$this->redirect(array('controller' => 'posts' , 'action' => 'index'));
				}


			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));

			}
		}
	}

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
	}
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
	function subscribe($user_id = ''){

        $email_user_id = null;
        $email_topic_id = null;
        $email_paper_id = null;
        $email_category_id = null;

        $logged_in_user_id = $this->Session->read('Auth.User.id');

        if(isset($this->data) && !empty($this->data)){
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
					$this->redirect(array('controller' => 'users', 'action' => 'view', $this->data['User']['user_id']));
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
                   $this->log('oben');
                    $this->log($email_paper_id);
                    $this->log($email_user_id);
                    $this->log($email_category_id);
                    $this->log($email_topic_id);
                    $this->_sendSubscriptionEmail($email_user_id, $email_topic_id, $email_paper_id, $email_category_id);
					$this->Session->setFlash($msg,'default', array('class' => 'success'));
					$this->redirect(array('controller' => 'users', 'action' => 'view', $this->data['User']['user_id']));
				}
				else{
                    $msg = $this->Paper->return_code_messages[$return_code];
					$this->Session->setFlash($msg, true);
					$this->redirect(array('controller' => 'users', 'action' => 'view', $this->data['User']['user_id']));

				}
			}
			else{

			}
		}


		if(empty($user_id)){
			$this->Session->setFlash(__('No user param', true));
			$this->redirect(array('action' => 'view', $logged_in_user_id));
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
                $this->set('new_paper', 'jau neu wa');

                $this->subscribe($user_id);
            }
            else{

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
			//set user id, who will be subscribed, for view
			$this->set('user_id', $user_id);

			//the user has at least one paper with one category in it
			if($has_one_paper){

				//user has exactly one paper
				$this->set('paper_id', $papers[0]['Paper']['id']);

				if($has_categories){
					//only one paper given with one or more category the user gets as an option
					$paper_category_drop_down = $this->_generatePaperSelectData($logged_in_user_id);
					$this->set('paper_category_chooser', $paper_category_drop_down);
				}
				else{
					//no paper / category options -> just display paper name
					$this->set('paper_name', $papers[0]['Paper']['title']);
				}
			}


			if($has_more_papers){
				//read all papers
				$paper_category_drop_down = $this->_generatePaperSelectData($logged_in_user_id);
				$this->set('paper_category_chooser', $paper_category_drop_down);
			}

			if($has_topics){
				//the user who wil be associated, has one or more topics. choose whole user or a specifict topic
				$user_topic_drop_down = $this->_generateUserSelectData($user_id);
				$this->set('user_topic_chooser', $user_topic_drop_down);

			}


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
					$this->redirect(array('controller' => 'users', 'action' => 'view', $this->data['User']['user_id']));
				}
				else{
                    $msg = $this->Paper->return_code_messages[$return_code];
					$this->Session->setFlash($msg, true);
					$this->redirect(array('controller' => 'users', 'action' => 'view', $this->data['User']['user_id']));

				}

				//echo 'macht user ' . $user_id . ' in paper ' .$papers[0]['Paper']['id'];
			}
			else{
				//$this->Session->setFlash(__('Error while reading paper', true));
				//$this->redirect(array('action' => 'view', $logged_in_user_id));

			}

		}
        $this->render('subscribe', 'ajax');

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
				$content_data['options'][ContentPaper::USER.ContentPaper::SEPERATOR.$user['User']['id']] = $user['User']['username'].' (all topics)';
				$topics = $user['Topic'];
				if(isset($topics) && count($topics >0)){
					foreach($topics as $topic){
						$content_data['options'][ContentPaper::TOPIC.ContentPaper::SEPERATOR.$topic['id']] = '> topic:'.$topic['name'];
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
				$content_data['options'][ContentPaper::PAPER.ContentPaper::SEPERATOR.$paper['Paper']['id']] = $paper['Paper']['title'].' (' .('front page').')';
				if(isset($paper['Category']) && count(isset($paper['Category']) > 0)){
					foreach ($paper['Category'] as $category){
						$content_data['options'][ContentPaper::CATEGORY.ContentPaper::SEPERATOR.$category['id']] = '    '.$category['name'].' (category)';
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
            $this->redirect(array('controller' => 'users', 'action' => 'view'));
        }

      if(!empty($this->data)) {
        $this->User->contain();
        $user = $this->User->findByEmail($this->data['User']['email']);
        if($user) {
          $user['User']['tmp_password'] = $this->User->createTempPassword(7);
          $user['User']['password'] = $this->Auth->password($user['User']['tmp_password']);
          if($this->User->save($user, false)) {
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
			$this->User->updateSolr = true;
			if ($this->User->save($this->data, true, array('name', 'description'))) {
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

	function accPrivacy(){

		$id = $this->Session->read('Auth.User.id');


		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect($this->referer());
		}

		if (!empty($this->data)) {
			if ($this->User->save($this->data, true, array('allow_comments', 'allow_messages'))) {
				$this->Session->setFlash(__('The changes have been saved', true), 'default', array('class' => 'success'));
				//update session variables:
				$this->Session->write("Auth.User.allow_messages", $this->data['User']['allow_messages']);
				$this->Session->write("Auth.User.allow_comments", $this->data['User']['allow_comments']);
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
	protected function getUserForSidebar($user_id = ''){
		if($user_id == ''){
		//reading logged in user from session
			$user['User'] = $this->Session->read('Auth.User');
		} else {
		//reading user
			$user = $this->User->read(array('id','enabled','name','username','created','image' , 'allow_messages', 'allow_comments','description','posts_user_count','post_count','comment_count', 'content_paper_count', 'subscription_count', 'paper_count'), $user_id);
		}
		return $user;
	}

    protected function _sendWelcomeEmail($user_id){
        $this->User->contain();
        $user = $this->User->read(null, $user_id);

        //setting params for template
        $this->set('username', $user['User']['username']);
        //send mail
        $this->_sendMail($user['User']['email'], __('Welcome to myZeitung', true),'welcome');
    }
    protected function _sendPasswordEmail($user_id, $password) {
        $this->User->contain();
        $user = $this->User->read(null, $user_id);

      $this->set('user', $user['User']);
      $this->set('password', $password);
      $this->_sendMail($user['User']['email'], __('Password change request', true),'forgot_password');
      $this->Session->setFlash('A new password has been sent to your supplied email address.');
    }

    /**
     * send an email to a user that got subscribed by another user.
     */
    protected function _sendSubscriptionEmail($user_id, $topic_id, $paper_id, $category_id) {
        $user = array();
        $topic = null;
        $paper = array();
        $category = null;

        $this->Paper->contain();
        $paper = $this->Paper->read(array('id', 'title', 'owner_id'), $paper_id);
        //send only an email if the user did not subscribe himself
        if($paper['Paper']['owner_id'] != $user_id){
            $this->User->contain();
            $user = $this->User->read(array('id', 'username', 'name', 'email'), $user_id);

            if($topic_id != null){
                $this->Topic->contain();
                $topic = $this->Topic->read(array('id', 'name'), $topic_id);
            }
            if($category != null){
                $this->Category->contain();
                $category = $this->Category->read(array('id', 'name'), $category_id);
            }

            $this->set('user', $user);
            $this->set('paper', $paper);
            $this->set('category', $category);
            $this->set('topic', $topic);

            $this->_sendMail($user['User']['email'], __('Subscription info', true),'subscription');
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
}
