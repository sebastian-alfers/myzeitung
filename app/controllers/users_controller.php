<?php
class UsersController extends AppController {

	var $name = 'Users';
	var $components = array('ContentPaperHelper', 'RequestHandler', 'JqImgcrop', 'Upload');
	var $uses = array('User', 'Category', 'Paper','Group', 'Topic', 'Route', 'ContentPaper', 'Subscription');
	var $helpers = array('Time', 'Image', 'Js' => array('Jquery'));


	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('add','login','logout', 'view', 'index', 'viewSubscriptions');

	}


	public function login(){
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
	            'limit' => 10,
		//order
	            'order' => 'username ASC',
		//fields - custom field sum...
		    	'fields' => array(	'User.id',
								  	'User.username',
		    						'User.name',
		    						'User.created',
		    						'User.posts_user_count',	
		    						'User.post_count',
		    						'User.comment_count'  									
		    						),
		    						//contain array: limit the (related) data and models being loaded per post
	            'contain' => array(),
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
	                		'PostUser.user_id' => $user_id
		),
		),
		),
		//limit of records per page
	            'limit' => 9,
		//order
	            'order' => 'PostUser.created DESC',
	            'fields' => array('Post.*', 'PostUser.repost'),
		//contain array: limit the (related) data and models being loaded per post
	            'contain' => array( 'User.id','User.username', 'User.image'),
		)
		);
		if($topic_id != null){
			//adding the topic to the conditions array for the pagination - join
			$this->paginate['Post']['joins'][0]['conditions']['PostUser.topic_id'] = $topic_id;
		}

		//unbinding irrelevant relations for the query
		$this->User->contain('Topic.id', 'Topic.name', 'Topic.post_count', 'Paper.id' , 'Paper.title', 'Paper.image');

		$this->set('user', $this->getUserForSidebar($user_id));

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
	                		'Subscription.user_id' => $user_id
		),
		),
		),
		//fields
	            'fields' => array('id','owner_id','title','description','created','subscription_count'),
		//limit of records per page
	            'limit' => 9,
		//order
	            'order' => 'Subscription.own_paper ASC , Paper.title ASC',
		//contain array: limit the (related) data and models being loaded per post
	            'contain' => array(),


		)
		);
		if($own_paper != null){
			//adding the additional conditions  for the pagination - join
			$this->paginate['Paper']['joins'][0]['conditions']['Subscription.own_paper'] = $own_paper;
		}

		//unbinding irrelevant relations for the query
		$this->User->contain('Topic.id', 'Topic.name', 'Topic.post_count', 'Paper.id' , 'Paper.title', 'Paper.image');
		$this->set('user', $this->User->read(array('id','name','username','created','image' ,'posts_user_count','post_count','comment_count', 'content_paper_count', 'subscription_count', 'paper_count', 'allow_messages'), $user_id));
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

		//debug($papers);die();
	}


	function add() {
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
				
				//Auto-Login after Register
				$userData = array('username' => $this->data['User']['username'], 'password' => $this->Auth->password($this->data['User']['passwd']));
				$this->Session->setFlash(__('Thank you for registration.', true), 'default', array('class' => 'success'));
				if($this->Auth->login($userData)){
					$this->redirect(array('controller' => 'papers' , 'action' => 'index'));
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

	/**
	 * show all references from content_papers table to this user
	 * - whole user references (to paper itself or category)
	 * - references to a specific topic (to paper itself or category)
	 * Enter description here ...
	 */
	function references(){


		//check for param in get url
		if(empty($this->params['pass'][0]) || !isset($this->params['pass'][0])){
			$this->Session->setFlash(__('No user param', true));
			$this->redirect(array('action' => 2));
		}
		$user_id = $this->params['pass'][0];

		//now all references to whole user
		$wholeUserReferences = $this->User->getWholeUserReferences($user_id);
		$this->set('wholeUserReferences', $wholeUserReferences);


		//now all references to all topics
		$topicReferences = $this->User->getUserTopicReferences($user_id);

		$this->set('topicReferences', $topicReferences);

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
		$logged_in_user_id = $this->Session->read('Auth.User.id');

		if(isset($this->data) && !empty($this->data)){
			//save subscription and redirect

			//prepare data for association
			$data = array();

			if(isset($this->data['User']['user_topic_content_data'])){
				//process the selected drop down for user/category
				$data['Paper'][ContentPaper::CONTENT_DATA] = $this->data['User']['user_topic_content_data'];
			}
			else{
				//build static content data
				$data['Paper'][ContentPaper::CONTENT_DATA] = ContentPaper::USER.ContentPaper::SEPERATOR.$this->data['User']['user_id'];
			}
			$data['Paper']['user_id'] = $this->data['User']['user_id'];



			//check if we have options or not
			if(isset($this->data['User']['paper_category_content_data'])){
				//determine what is the target type, paper or categorty ?
				$target = explode(ContentPaper::SEPERATOR, $this->data['User']['paper_category_content_data']);
				$target_type = $target[0];

				//check, if submitted target type is valid
				if(!$this->_validateTargetType($target_type)){
					$this->Session->setFlash(__('Not able to associate content to paper - invalid target type', true));
					$this->redirect(array('controller' => 'users', 'action' => 'view', $logged_in_user_id));
				}

				$data['Paper']['target_id'] = $target[1];
				//now, we have a valid target type
				$data['Paper']['target_type'] = $target_type;

				//if target is category -> read paper id from category
				if($target_type == ContentPaper::CATEGORY){
					$category_id = $target[1];

					$this->Category->contain();
					$category_data = $this->Category->read('paper_id', $category_id);

					$this->data['User']['paper_id'] = $category_data['Category']['paper_id'];
				}
				else{
					//taraget is paper
					$this->data['User']['paper_id'] = $target[1];
				}

			}
			else{
				//only one paper without category
				$data['Paper']['target_id'] = $this->data['User']['paper_id'];
				//now, we have a valid target type
				$data['Paper']['target_type'] = ContentPaper::PAPER;
			}



			if($this->Paper->read(null, $this->data['User']['paper_id'])){

				//save association with prepared data
				if($this->Paper->associateContent($data)){
					$msg = __('Content has been associated to paper', true);

					$this->Session->setFlash($msg,'default', array('class' => 'success'));
					$this->redirect(array('controller' => 'users', 'action' => 'view', $logged_in_user_id));
				}
				else{

					$this->Session->setFlash(__('Not able to associate content to paper', true));
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

		/**
		 * can not associate user himself
		 */
		if($logged_in_user_id == $user_id){
			$this->Session->setFlash(__('Can not subscribe yourself', true));
			$this->redirect(array('action' => 'view', $logged_in_user_id));
		}

		//get paper of logged in user
		$this->Paper->contain('Category');//load only paper data
		$papers = $this->Paper->find('all', array('conditions' => array('Paper.owner_id' => $logged_in_user_id)));

		if(count($papers) == 0){
			//user has no paper
			//what do do?
			$this->Session->setFlash(__('User has no paper', true));
			$this->redirect(array('action' => 'view', $logged_in_user_id));
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
					debug('jas');
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
			if($this->Paper->read(null, $paper_id)){

				//prepare data
				$data = array();

				//@todo : implement if user has a topic
				$data['Paper'][ContentPaper::CONTENT_DATA] = ContentPaper::USER.ContentPaper::SEPERATOR.$user_id;
				$data['Paper']['target_type'] = ContentPaper::PAPER;
				$data['Paper']['target_id'] = $paper_id;

				if($this->Paper->associateContent($data)){
					$msg = __('Content has been associated to paper', true);

					$this->Session->setFlash($msg, 'default', array('class' => 'success'));
					$this->redirect(array('controller' => 'users', 'action' => 'view', $logged_in_user_id));
				}
				else{
					$this->Session->setFlash(__('Not able to associate content to paper', true));
					$this->redirect(array('controller' => 'users', 'action' => 'view', $logged_in_user_id));
				}

				echo 'macht user ' . $user_id . ' in paper ' .$papers[0]['Paper']['id'];
			}
			else{
				$this->Session->setFlash(__('Error while reading paper', true));
				$this->redirect(array('action' => 'view', $logged_in_user_id));
			}

		}

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
				//debug($user);die();
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
			$paper_data = $this->Paper->find('all', array('conditions' => array('Paper.owner_id' => $user_id)));

			foreach($paper_data as $paper){
				$content_data['options'][ContentPaper::PAPER.ContentPaper::SEPERATOR.$paper['Paper']['id']] = $paper['Paper']['title'].' (' .('direct into paper').')';
				if(isset($paper['Category']) && count(isset($paper['Category']) > 0)){
					foreach ($paper['Category'] as $category){
						$content_data['options'][ContentPaper::CATEGORY.ContentPaper::SEPERATOR.$category['id']] = '> category:'.$category['name'];
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
			$user = $this->User->read(array('id','name','username','created','image' , 'allow_messages', 'allow_comments','description','posts_user_count','post_count','comment_count', 'content_paper_count', 'subscription_count', 'paper_count'), $user_id);
		}
		
		return $user;

	}
	

}
?>