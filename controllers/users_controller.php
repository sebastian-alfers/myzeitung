<?php
class UsersController extends AppController {
	

	
	

	var $name = 'Users';
	var $components = array('ContentPaperHelper');
	var $uses = array('User','Group', 'Topic', 'Route', 'ContentPaper');


	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('add','login','logout', 'view', 'index');

	}


     function login()  
     {  
     
     }  
   
     function logout()  
     {  
       
         $this->redirect($this->Auth->logout());  
     }  
 
	function index() {
		$this->User->recursive = 0;
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

	     /*writing all settings for the paginate function. 
		  important here is, that only the user's posts are subject for pagination.*/
		    $this->paginate = array(
	        'Post' => array(
		    //setting up the join. this conditions describe which posts are gonna be shown
	            'joins' => array(
	                array(
	                    'table' => 'posts_users',
	                    'alias' => 'PostsUser',
	                    'type' => 'INNER',
	                    'conditions' => array(
	                        'PostsUser.post_id = Post.id',
	                		'PostsUser.user_id' => $user_id
	                    ),
	                    
	                    
	                   
	                ),
	                
	            ),
	            //limit of records per page
	            'limit' => 10,
	            //order
	            'order' => 'PostsUser.created DESC',
	        	//contain array: limit the (related) data and models being loaded per post
	            'contain' => array('User.id','User.username'),
	         )
	    );
	    if($topic_id != null){
	    	//adding the topic to the conditions array for the pagination - join
	    	$this->paginate['Post']['joins'][0]['conditions']['PostsUser.topic_id'] = $topic_id;
	    }		
	   
			//unbinding irrelevant relations for the query
			$this->User->contain('Topic.id', 'Topic.name');
			$this->set('user', $this->User->read(null, $user_id));
			$this->set('posts', $this->paginate($this->User->Post));
	}


						
	function add() {
		if (!empty($this->data)) {
			$this->data['User']['group_id'] = 1;
			$this->User->create();

			if ($this->User->save($this->data)) {

				//after adding user -> add new topic
		//		$newUserId = $this->User->id;
			//	$topicData = array('name' => 'first_automatic_topic', 'user_id' => $newUserId);
				//$this->Topic->create();
				//$this->Topic->save($topicData);

				//@todo move it to a better place -> to user model
				//afer adding user -> add new route
				$route = new Route();
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

				$this->Session->setFlash(__('The user has been saved', true));
				$this->redirect(array('action' => 'index'));
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
				$this->Session->setFlash(__('The user has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
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
		if ($this->User->delete($id)) {
			$this->Session->setFlash(__('User deleted', true));
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
			$this->redirect(array('action' => 'index'));
		}
		$user_id = $this->params['pass'][0];

		//now all references to whole user
		$wholeUserReferences = $this->User->getWholeUserReferences($user_id);
		$this->set('wholeUserReferences', $wholeUserReferences);


		//now all references to all topics
		$topicReferences = $this->User->getUserTopicReferences($user_id);

		$this->set('topicReferences', $topicReferences);

	}
}
?>