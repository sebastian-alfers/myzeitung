<?php
class PostsController extends AppController {

	var $name = 'Posts';
 	var $components = array('Auth', 'Session');
 	var $uses = array ('Post','PostsUser', 'Route');
 	
 	
 	public function beforeFilter(){
 		parent::beforeFilter();
 		//declaration which actions can be accessed without being logged in
 		$this->Auth->allow('index','view','blog');
 	}
 	
 	

	function index() {
		$this->Post->recursive = 0;
		$this->set('posts', $this->paginate());
	}
	
	

	//repost: reposting means to recommend a post of another user to your followers. 
	//		  it will be shown on your own blog-page and be marked as reposted. (comparable to re-tweet)
	//        -> to do so: this function creates an entry in the table posts_users
	//			 with the redirected post and the user who is recommending it.
	function repost($id){
		if(isset($id)){
		$postsUserData = array('post_id' => $id,
							   'user_id' => $this->Auth->user('id'));
		$this->PostsUser->create();
		// repost could be saved
			if($this->PostsUser->save($postsUserData)){
				//increment count_reposts for the reposted post / important not to load related objects => contain();
				$this->Post->contain();
				$this->data = $this->Post->read(null, $id);
				$this->data['Post']['count_reposts'] +=1;
	 			$this->Post->save($this->data['Post']);
				$this->Session->setFlash(__('The Post has been reposted successfully.', true));
			}
			else {
				// repost couldn't be saved
				$this->Session->setFlash(__('The Post could not be reposted.', true));
			}
		}
		else {
			// no post $id
			$this->Session->setFlash(__('Invalid post', true));
		}
		$this->redirect($this->referer());
	}
	
	
	
	//function to delete a repost
	function undoRepost($id){
		if(isset($id)){
			$repost =  $this->PostsUser->read(null, $id);
			//if repost belongs to user
			if($this->Auth->user('id') == $repost['PostsUser']['user_id']){
				//reading related post to decrement the repost-counter
				$this->Post->contain();
				$post = $this->Post->read(null, $repost['PostsUser']['post_id']);
				$post['Post']['count_reposts'] -= 1;
				$this->Post->save($post);
				//deleting the repost from the PostsUser-table
				$this->PostsUser->delete($id);
				$this->Session->setFlash(__('Repost removed successfully.'));
			}
			else{
				$this->Session->setFlash(__("Repost doesn't belong to you."));
			}
		}
		else {
			// no repost $id
			$this->Session->setFlash(__('Invalid repost-id', true));
		}
		$this->redirect($this->referer());	
	}
	
	

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid post', true));
			$this->redirect(array('action' => 'index'));
		}
		debug($this->Post->read(null, $id));
		$this->set('post', $this->Post->read(null, $id));
		
	}

	
	function add() {
		
		if (!empty($this->data)) {
			$id = $this->Auth->User("id");
			$this->data["Post"]["user_id"] = (int)$id;
			
			$this->Post->create();
			if ($this->Post->save($this->data)) {
				//now add new url key for post
				$route = new Route();
				$route->create();

				if( $route->save(array('source' => $this->data['Post']['title'] ,
				   'target_controller' 	=> 'posts',
				   'target_action'     	=> 'view',
				   'target_param'		=> $this->Post->id)))
				{
					
				}
				
				
				$postsUserData = array('user_id' => $id,
									   'post_id' => $this->Post->id);
				$this->PostsUser->create();
				$this->PostsUser->save($postsUserData);
				
				
				
				$this->Session->setFlash(__('The post has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The post could not be saved. Please, try again.', true));

			}
		}
		$user_id = $this->Auth->user('id');
		$topics = $this->Post->Topic->find('list', array('conditions' => array('Topic.user_id' => $user_id)));
		
		
		$this->set(compact('topics'));
		$this->set('user_id',$user_id);
		

	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid post', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Post->save($this->data)) {
				$this->Session->setFlash(__('The post has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The post could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Post->read(null, $id);
		}
		$users = $this->Post->User->find('list');
		$topics = $this->Post->Topic->find('list');
		$users = $this->Post->User->find('list');
		$this->set(compact('users', 'topics', 'users'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for post', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Post->delete($id)) {
			$this->Session->setFlash(__('Post deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Post was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}

}
?>