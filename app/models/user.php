<?php
class User extends AppModel {

    const DEFAULT_USER_IMAGE 	= 'assets/default-user-img.png';

	//field-validation in constructor -> otherwise it's not possible to use "__('translate this', true)" in error messages.



	var $name = 'User';
	var $displayField = 'name';

	var $ContentPaper = null;

	var $updateSolr = false;
	var $uses = array('Route', 'Cachekey');

	var $hasMany = array(
	 'Post' => array(
	 	'className' => 'Post',
	 	'foreignKey' => 'user_id',
	 	'dependent' => true,
	 	'conditions' => '',
		 'fields' => '',
		 'order' => '',
		 'limit' => '',
		 'offset' => '',
		 'exclusive' => '',
		 'finderQuery' => '',
		 'counterQuery' => ''
		 ),
	 'PostUser' => array(
	 	'className' => 'PostUser',
	 	'foreignKey' => 'user_id',
	 	'dependent' => true,
	 	'conditions' => '',
		 'fields' => '',
		 'order' => '',
		 'limit' => '',
		 'offset' => '',
		 'exclusive' => '',
		 'finderQuery' => '',
		 'counterQuery' => ''
		 ),
	'Topic' => array(
		'className' => 'Topic',
		'foreignKey' => 'user_id',
		'dependent' => true,
		'conditions' => '',
		'fields' => '',
		'order' => '',
		'limit' => '',
		'offset' => '',
		'exclusive' => '',
		'finderQuery' => '',
		'counterQuery' => '',
        'counterCache' => true
		),

	'ContentPaper' => array(
		'className' => 'ContentPaper',
		'foreignKey' => 'user_id',
		'dependent' => true,
		'conditions' => '',
		'fields' => '',
		'order' => '',
		'limit' => '',
		'offset' => '',
		'exclusive' => '',
		'finderQuery' => '',
		'counterQuery' => ''
		),
	'Paper' => array(
		'className' => 'Paper',
		'foreignKey' => 'owner_id',
		'dependent' => true,
		'fields' => '',
		'order' => 'subscription_count DESC',
		'limit' => 3,
		'offset' => '',
		'exclusive' => '',
		'finderQuery' => '',
		'counterQuery' => ''
		),
	'Subscription' => array(
		'className' => 'Subscription',
		'foreignKey' => 'user_id',
		'dependent' => true,
		'conditions' => '',
		'fields' => '',
		'order' => '',
		'limit' => '',
		'offset' => '',
		'exclusive' => '',
		'finderQuery' => '',
		'counterQuery' => ''
		),
	'Setting' => array(
		'className' => 'Setting',
		'foreignKey' => 'model_id',
		'dependent' => true,
		'conditions' => "Setting.model_type = 'user'",
		'fields' => 'Setting.namespace, Setting.key, Setting.value',
		'order' => '',
		'limit' => '',
		'offset' => '',
		'exclusive' => '',
		'finderQuery' => '',
		'counterQuery' => ''
		)

		);

		var $hasOne = array(
			'Route' => array(
				'className' => 'Route',
				'foreignKey' => 'ref_id',//important to have FK
		),
		);


	function __construct(){
		parent::__construct();
			
		$this->validate = array(

			'name' => array(
				'maxlength' => array(
					'rule'			=> array('maxlength', 40),
					'message'		=> __('Names can only be 40 characters long.', true),
					'last' 			=> true,
				),
			),
			'description' => array(
				'maxlength' => array(
					'rule'			=> array('maxlength', 1000),
					'message'		=> __('Your description can only be 1000 characters long.', true),
					'last'			=> true,
				),
			),
			
			'email' => array(
				'empty' => array(
					'rule'			=> 'notEmpty',
					'message' 		=> __('Please enter your email address.', true),
					'last' 			=> true,
				),
				'email' => array(
					'rule'			=> array('email'),
					'message'		=> __('Please enter a valid email address.', true),
					'last' 			=> true,
				),
				'unique' => array(
					'rule'			=> 'isUnique',
					'message'		=> __('This email address has already been registered.', true),
					'last' 			=> true,
				),
			),
			
			'username' => array(
				'empty' => array(
					'rule' 			=> 'notEmpty',
					'message' 		=> __('Please enter your desired username.', true),
					'last' 			=> true,
				),
				'length' => array(
					'rule'			=> array('between', 3, 15),
					'message'		=> __('Usernames must be between 3 and 15 characters long.', true),
					'last'			=> true,
				),
				'alpha' => array(
                       // alphaNumeric rule did somehow allow accented characters...
					'rule'			=> array('custom', '/^[a-z0-9]*$/i'),
					'message'		=> __('Usernames must only contain letters and numbers (no special characters).', true),
					'last'			=> true,	
				),
	    		'unique' => array(
					'rule'			=> 'isUnique',
					'message'		=> __('This username has already been taken.', true),
					'last'			=> true,
				),
			),
			//the auth component hashes the pwd before the save method - before validation. so we are using a temp field for validating first.

			'passwd' => array(
				'length' => array(
					'rule' 			=> array('between', 5, 20),
					'message'		=> __('Passwords must be between 5 and 20 characters long.', true),
					'last'			=> true,
				),
			),
			'passwd_confirm' => array (  
                'match' =>  array(  
                    'rule'          => 'validatePasswdConfirm',   
                    'message'       => __('Passwords do not match', true),  
					'last'			=> true,
                )  
            ),
            'old_password' => array(
				'length' => array(
					'rule' 			=> 'validateOldPassword',
					'message'		=> __('Your old password does not match.', true),
					'last'			=> true,
				),
			),
            'tos_accept' => array (  
                'match' =>  array(  
                    'rule'          => array('equalTo', '1'),
                    'message'       => __('Please accept the terms of service and privacy policy.', true),  
					'last'			=> true,
                )  
            ),
            'url' => array(
				'valid_url' => array(
					'rule'			=> array('url', true), /* second param defines wether you force an input of a protocol like http:// ftp:// etc */
					'message'		=> __('Please provide a valid URL. http://your-link.domain', true),
					'allowEmpty'    => true,
                    'last'			=> true,
				),
			),
		);		
	}

	
   function validatePasswdConfirm($data)  
    {  
        if ($this->data['User']['passwd'] !== $data['passwd_confirm'])  
        {  
            return false;  
        }  
        return true;  
    }  
    
    function validateOldPassword($data)  
    {  
        if (Security::hash($this->data['User']['old_password'], null, true) !== $this->data['User']['password'])  
        {  
            return false;  
        }  
        return true;  
    }
    function createTempPassword($len) {
      $pass = '';
      $lchar = 0;
      $char = 0;
      for($i = 0; $i < $len; $i++) {
        while($char == $lchar) {
          $char = rand(48, 109);
          if($char > 57) $char += 7;
          if($char > 90) $char += 6;
        }
        $pass .= chr($char);
        $lchar = $char;
      }
      return $pass;
    }


		/**
		 *	hook into save process
		 * 
		 */
         function disable(){

            if($this->data['User']['enabled'] == true){

                $userData = $this->data;

                //disable all posts of this user
                App::import('model','Post');
                $this->Post = new Post();
                $this->Post->contain();
                $posts = $this->Post->find('all',array('conditions' => array('user_id' => $this->id)));
                foreach($posts as $post){
                    $this->Post->data = $post;
                    $this->Post->disable();
                }
                //disable all reposts of this user
                App::import('model','PostUser');
                $this->PostUser = new PostUser();
                $this->PostUser->contain();
                $reposts = $this->PostUser->find('all',array('conditions' => array('repost' => true, 'user_id' => $this->id)));
                foreach($reposts as $repost){
                    $this->PostUser->data = $repost;
                    $this->PostUser->disable();
                }
                //disable all papers of this user
                App::import('model','Paper');
                $this->Paper = new Paper();
                $this->Paper->contain();
                $papers = $this->Paper->find('all',array('conditions' => array('owner_id' => $this->id)));
                foreach($papers as $paper){
                    $this->Paper->data = $paper;
                    $this->Paper->disable();
                }
                 //disable all content_paper entries of this user
                App::import('model','ContentPaper');
                $this->ContentPaper = new ContentPaper();
                $this->ContentPaper->contain();
                $associations = $this->ContentPaper->find('all',array('conditions' => array('user_id' => $this->id)));
                foreach($associations as $association){
                    $this->ContentPaper->data = $association;
                    $this->ContentPaper->disable();
                }
                 //disable all subscription entries of this user
                App::import('model','Subscription');
                $this->Subscription = new Subscription();
                $this->Subscription->contain();
                $subscriptions = $this->Subscription->find('all',array('conditions' => array('user_id' => $this->id)));
                foreach($subscriptions as $subscription){
                    $this->Subscription->data = $subscription;
                    $this->Subscription->disable();
                }
                 //disable all subscription entries of this user
                App::import('model','Comment');
                $this->Comment = new Comment();
                $this->Comment->contain();
                $comments = $this->Comment->find('all',array('conditions' => array('user_id' => $this->id)));
                foreach($comments as $comment){
                    $this->Comment->data = $comment;
                    $this->Comment->disable();
                }
                //disable routes
             //   $this->disableRoutes();



                
                $this->data = $userData;
                $this->data['User']['enabled'] = false;
                $this->save($this->data);
                //delete solr entry
                $this->deleteFromSolr();

                return true;
            }
            //already disabled
            return false;
        }
        function enable(){

            if($this->data['User']['enabled'] == false){
                $userData = $this->data;
               //enable all posts of this user
                App::import('model','Post');
                $this->Post = new Post();
                $this->Post->contain();
                $posts = $this->Post->find('all',array('conditions' => array('user_id' => $this->id)));
                foreach($posts as $post){
                    $this->Post->data = $post;
                    $this->Post->enable();
                }
                //enable all reposts of this user
                App::import('model','PostUser');
                $this->PostUser = new PostUser();
                $this->PostUser->contain();
                $reposts = $this->PostUser->find('all',array('conditions' => array('repost' => true, 'user_id' => $this->id)));
                foreach($reposts as $repost){
                    $this->PostUser->data = $repost;
                    $this->PostUser->enable();
                }
                //enable all papers of this user
                App::import('model','Paper');
                $this->Paper = new Paper();
                $this->Paper->contain();
                $papers = $this->Paper->find('all',array('conditions' => array('owner_id' => $this->id)));
                foreach($papers as $paper){
                    $this->Paper->data = $paper;
                    $this->Paper->enable();
                }
                 //enable all content_paper entries of this user
                App::import('model','ContentPaper');
                $this->ContentPaper = new ContentPaper();
                $this->ContentPaper->contain();
                $associations = $this->ContentPaper->find('all',array('conditions' => array('user_id' => $this->id)));
                foreach($associations as $association){
                    $this->ContentPaper->data = $association;
                    $this->ContentPaper->enable();
                }
                 //enable all subscription entries of this user
                App::import('model','Subscription');
                $this->Subscription = new Subscription();
                $this->Subscription->contain();
                $subscriptions = $this->Subscription->find('all',array('conditions' => array('user_id' => $this->id)));
                foreach($subscriptions as $subscription){
                    $this->Subscription->data = $subscription;
                    $this->Subscription->enable();
                }
                 //enable all subscription entries of this user
                App::import('model','Comment');
                $this->Comment = new Comment();
                $this->Comment->contain();
                $comments = $this->Comment->find('all',array('conditions' => array('user_id' => $this->id)));
                foreach($comments as $comment){
                    $this->Comment->data = $comment;
                    $this->Comment->enable();
                }
                //enable Routes
                //$this->enableRoutes();


                
                $this->data = $userData;

                App::import('model','Solr');
                $this->Solr = new Solr();
                $this->addToOrUpdateSolr();

                $this->data['User']['enabled'] = true;
                $this->save($this->data);
                //add solr entry


                return true;
            }
            //already enabled
            return false;
        }
    function beforeSave(){
        if(!empty($this->data['User']['image']) && is_array($this->data['User']['image']) && !empty($this->data['User']['image'])){
            $this->data['User']['image'] = serialize($this->data['User']['image']);
        }
        // to prevent hashing before validation: temp field passwd is used
        if (isset($this->data['User']['passwd'])){
            $this->data['User']['password'] = Security::hash($this->data['User']['passwd'], null, true);
            unset($this->data['User']['passwd']);
        }

        if (isset($this->data['User']['passwd_confirm']))
        {
            unset($this->data['User']['passwd_confirm']);
        }
        if (isset($this->data['User']['old_password']))
        {
            unset($this->data['User']['old_password']);
        }
        if (isset($this->data['User']['tos_accept']))
        {
            unset($this->data['User']['tos_accept']);
        }
        return true;
    }

    function beforeValidate(){
        if (isset($this->data['User']['url']) && $this->data['User']['url'] == 'http://') {
            $this->data['User']['url'] = '';
        }
        $this->log($this->data);
    }

    function beforeDelete(){

        App::import('model','Comment');
        $this->Comment = new Comment();

        // reading all comments of the deleted user and calling custom delete method. see in comments model
        $this->Comment->contain();
        $comments = $this->Comment->findAllByUser_id($this->id);
        foreach($comments as $comment){
            $this->Comment->id = $comment['Comment']['id'];
            $this->Comment->delete($comment['Comment']['id']);
        }

        App::import('model','ConversationMessage');
        $this->ConversationMessage = new ConversationMessage();

        // reading all conversationmessages of the deleted user and reseting the user_id to null
        // "message from -deleted user-"
        $this->ConversationMessage->contain();
        $messages = $this->ConversationMessage->findAllByUser_id($this->id);
        foreach($messages as $message){
            $message['ConversationMessage']['user_id']= null;
            $this->ConversationMessage->save($message);
        }


        App::import('model','ConversationUser');
        $this->ConversationUser = new ConversationUser();
        // reading all conversationusers of the deleted user and reseting the user_id to null
        // "message between X,Y and deleted user"
        $this->ConversationUser->contain();
        $conversationusers = $this->ConversationUser->findAllByUser_id($this->id);
        foreach($conversationusers as $conversationuser){
            $conversationuser['ConversationUser']['user_id']= null;
            $conversationuser['ConversationUser']['status']= Conversation::STATUS_REMOVED;
            $this->ConversationUser->save($conversationuser);
        }

        return true;

    }
    /**
     * 1.update solr index

     */
    function afterSave($created){
    /*    if($created){
            $this->addRoute();
        }*/
        if($this->updateSolr){
            //update solr index
            App::import('model','Solr');
            $this->Solr = new Solr();
            $this->addToOrUpdateSolr();
        }


        if($created){
            //create initial settings
            $this->setting['Setting']['model_type'] = Setting::MODEL_TYPE_USER;
            $this->setting['Setting']['model_id'] = $this->data['User']['id'];
            $this->setting['Setting']['namespace'] = Setting::NAMESPACE_DEFAULT;
            $this->setting['Setting']['key'] = Setting::KEY_LOCALE;
            $this->setting['Setting']['value_data_type'] = 'locale_chooser';
            $this->setting['Setting']['value'] = $this->data['Setting']['value'];

            unset($this->data['Setting']);

            $this->Setting->save($this->setting);
        }

        App::import('model','Post');
        $username = $this->data['User']['username'];
        $this->Post->deleteRssCache($username);


    }
    function addToOrUpdateSolr(){
        //update user itself
        $this->Solr->add(array($this->generateSolrData()));


        //update his posts and papers in case he changed his profile picture and or real name (its shown in some cases for papers and posts)
            //papers
        App::import('model','Paper');
        $this->Paper = new Paper();
        $this->Paper->contain();
        $papers = $this->Paper->find('all', array('conditions' => array('owner_id' => $this->id),'fields' => array('id')));
        $paperList = array();
        foreach($papers as $paper){
            $paperList[] = $paper['Paper']['id'];
        }

        $this->Paper->Solr = new Solr();
        $this->Paper->addToOrUpdateSolr($paperList);
            //posts
        App::import('model','Post');
        $this->Post = new Post();
        $this->Post->contain();
        $posts = $this->Post->find('all', array('conditions' => array('user_id' => $this->id),'fields' => array('id')));
        $postList = array();
        foreach($posts as $post){
            $postList[] = $post['Post']['id'];
        }

        $this->Post->Solr = new Solr();
        $this->Post->addToOrUpdateSolr($postList);



    }
/*
    function addRoute(){
        App::import('model','Route');
        $this->Route = new Route();
        $this->Route->create();

        $route_username = strtolower($this->data['User']['username']);
        $routeData['Route'] = array('source' => '/a/'.$route_username,
                           'target_controller' 	=> 'users',
                           'target_action'     	=> 'view',
                            'target_param'		=> $this->id,
                            'ref_id'            => Route::TYPE_USER.$this->id);

         if( $this->Route->save($routeData,false)){

             return true;
        }

        return false;
    }
    function deleteRoutes(){
        App::import('model','Route');
        $this->Route = new Route();
        $conditions = array('Route.ref_id' 	=> Route::TYPE_USER.$this->id);
        //cascade = false, callbackes = true
        $this->Route->contain();
        $this->Route->deleteAll($conditions, false, true);
    }*/
    /*
    function enableRoutes(){
        App::import('model','Route');
        $this->Route = new Route();
        $conditions = array('Route.ref_id' 	=> Route::TYPE_USER.$this->id);
        $this->Route->contain();
        $routes = $this->Route->find('all',array('conditions' => $conditions));
        foreach($routes as $route){
            $route['Route']['enabled'] = true;
            $this->Route->save($route);
        }
    }
    function disableRoutes(){
        App::import('model','Route');
        $this->Route = new Route();
        $conditions = array('Route.ref_id' 	=> Route::TYPE_USER.$this->id);
        $this->log('disableroutes function');
        $this->Route->contain();
        $routes = $this->Route->find('all',array('conditions' => $conditions));
       

        foreach($routes as $route){
            $route['Route']['enabled'] = false;
            $this->log($route);
            $this->Route->save($route);
        }
    } */


    /**
     * if a user writes for a paper / category is definded in content_papers
     * this method retuns all users references
     *
     * @param $user_id
     * @package $group_by_paper
     */
    function getAllUserContentReferences($user_id, $group_by_paper = true){
        App::import('model','ContentPaper');
        //App::import('model','Topic');
        $references = array();
        $conditions = array('conditions' => array('ContentPaper.user_id' => $user_id));

        $this->ContentPaper = new ContentPaper();
        $this->ContentPaper->contain('Paper', 'Paper.Route', 'Category', 'Topic');
        $references = $this->ContentPaper->find('all', $conditions);

        if(!$group_by_paper) return $references;

        $grouped_references = array();

        foreach($references as $ref){
            $paper_id = $ref['ContentPaper']['paper_id'];

            if(!isset($grouped_references[$paper_id]))
                $grouped_references[$paper_id]['Paper'] = $ref['Paper'];//save paper

            //now save all references to this paper


            //check if whole user is in paper
            if(empty($ref['Category']['id']) && empty($ref['Topic']['id'])){
                $grouped_references[$paper_id]['references']['whole_user_in_paper'] = true;
            }

            //check if user topic is in whole paper
            if(empty($ref['Category']['id']) && !empty($ref['Topic']['id'])){
                $grouped_references[$paper_id]['references']['user_topic_in_paper'][]['Topic'] = $ref['Topic'];
            }

            //check if whole user is in category
            if(!empty($ref['Category']['id']) && empty($ref['Topic']['id'])){
                $grouped_references[$paper_id]['references']['whole_user_in_category'][]['Category'] = $ref['Category'];
            }

            //check if user topic is in category
            if(!empty($ref['Category']['id']) && !empty($ref['Topic']['id'])){
                $grouped_references[$paper_id]['references']['user_topic_in_category'][] = array('Category' => $ref['Category'],
                                                        'Topic' => $ref['Topic']);
            }

            $grouped_references[$paper_id]['references'] = $grouped_references[$paper_id]['references'];

        }

        return $grouped_references;
    }

    function getWholeUserReferences($user_id){
        App::import('model','ContentPaper');
        //App::import('model','Topic');
        $wholeUserReferences = array();
        $conditions = array('conditions' => array('ContentPaper.topic_id' => null, 'ContentPaper.user_id' => $user_id));
        //$this->ContentPaper->recursive = 0;

        $this->ContentPaper = new ContentPaper();
        $this->ContentPaper->contain('Paper', 'Category');
        $wholeUserReferences = $this->ContentPaper->find('all', $conditions);
        return $wholeUserReferences;
    }

    function getUserTopicReferences($user_id){
        $topicReferences = array();

        //get all users topics
        $this->Topic = new Topic();

        $this->Topic->contain();
        $topics = $this->Topic->find('list', array('conditions' => array('Topic.user_id' => $user_id)));


        foreach($topics as $topid_id => $topic_name){
            $conditions = array('conditions' => array('ContentPaper.topic_id' => $topid_id));
            //$this->ContentPaper->recursive = 0;
            $this->log($conditions);
            $this->ContentPaper->contain('Paper', 'Category', 'Topic');
            $topicRef = $this->ContentPaper->find('all', $conditions);
            if(isset($topicRef[0]['ContentPaper']['id']) && !empty($topicRef[0]['ContentPaper']['id'])){
                $topicReferences[] = $topicRef[0];
            }

        }

        return $topicReferences;
    }

    function generateSolrData(){
        if(!isset($this->data['User']['id'])){
            if($this->id){
                $this->data['User']['id'] = $this->id;
            }

        }

        $solrFields = array();

        $solrFields['id'] = $this->data['User']['id'];
        $solrFields['user_username'] = $this->data['User']['username'];
        $solrFields['user_name'] = $this->data['User']['name'];
        $solrFields['type'] = Solr::TYPE_USER;
        $solrFields['user_id'] = $this->data['User']['id'];
        $solrFields['index_id'] =  Solr::TYPE_USER.'_'.$this->id;
        if(isset($this->data['User']['image'])){
            $solrFields['user_image'] = $this->data['User']['image'];
        }
        return $solrFields;

    }



    function afterDelete(){
        $this->deleteFromSolr();
    }

    /**
     * remove the user from solr index
     *
     */

    function deleteFromSolr(){

        App::import('model','Solr');
        $solr = new Solr();
        $solr->delete(Solr::TYPE_USER.'_'.$this->id);
        return true;
    }



    function getSettings($id = null){
        if($id == null){
            $id = $this->id;
        }
        if($id == null){
            return false;
        }
        App::import('model','Setting');
        $this->Setting = new Setting();
        
        return $this->Setting->get('user', $id,null,null);

     
    }

    function getUserForSidebar($user_id){
        $user = array();
        if(is_numeric($user_id)){
            // param is user id
            $user = $this->read(null, $user_id);
        }else{
            //param is username
            $user = $this->find('first',array('conditions' => array('LOWER(User.username)' => strtolower($user_id))));
        }
       //user found
        //read settings
        if(isset($user['User']['id'])){

            $user['Setting'] = $this->getSettings($user['User']['id']);

        }

        return $user;
    }

}
?>