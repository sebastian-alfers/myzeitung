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
	//		  Furthermore an entry in the "reposters" array of the Post is added, to check quickly if 
	//        User already reposted a post (especially for better performance in views)
	// # params
	// * $id -> post id (of the post that the user wants to repost)
	
	
	function repost($id){
		if(isset($id)){
			$reposted = false;
			$postsUserData = array('post_id' => $id,
								   'user_id' => $this->Auth->user('id'));
			$repostEntries = $this->PostsUser->find('all',array('conditions' => $postsUserData));
			// if there are no reposts for this post/user combination yet
			if(!isset($repostEntries[0])){
				$this->PostsUser->create();
				// repost could be saved
				if($this->PostsUser->save($postsUserData)){
					$this->Session->setFlash(__('The Post has been reposted successfully.', true));
					$reposted = true;
				}
				else {
					// repost couldn't be saved
					$this->Session->setFlash(__('The Post could not be reposted.', true));
				}
			}
			$this->Post->contain();
			$this->data = $this->Post->read(null, $id);
			if($reposted){
				//increment count_reposts for the reposted post 
				$this->data['Post']['count_reposts'] +=1;
			}
			// writing the reposter's user id into the reposters-array of the post
			if(!in_array($this->Auth->user('id'),$this->data['Post']['reposters'])){
				//counting entries
				$count = count($this->data['Post']['reposters']);
				//adding new entry after last position (= $count)
				$this->data['Post']['reposters'][$count] = $this->Auth->user('id');
			}
	 		$this->Post->save($this->data['Post']);
		}else {
			// no post $id
			$this->Session->setFlash(__('Invalid post', true));
		}
		$this->redirect($this->referer());
	}
	

		
	//function to delete a repost
	//user has to be logged in to remove reposts. (user can only have reposts if logged in...)
	// # params
	// * $id - post id (of the reposted post)
	function undoRepost($id){
		if(isset($id)){
			$deleted = false;
			// just in case there are several reposts for the combination post/user - all will be deleted.
			$reposts =  $this->PostsUser->find('all',array('conditions' => array('PostsUser.post_id' => $id, 'PostsUser.user_id' => $this->Auth->user('id'))));
			foreach($reposts as $repost){
				//deleting the repost from the PostsUser-table
				$this->PostsUser->delete($repost['PostsUser']['id']);
				$deleted = true;
			}
			//reading related post to decrement the repost-counter
			$this->Post->contain();
			$post = $this->Post->read(null,$id);
			$post['Post']['count_reposts'] -= 1;
			//deleting user-id entry from reposters-array in post-model
			if(in_array($this->Auth->user('id'),$post['Post']['reposters'])){
				$pos = array_search($this->Auth->user('id'),$post['Post']['reposters']);
				unset($post['Post']['reposters'][$pos]);
			}
			$this->Post->save($post);
		}
		else {
			// no repost $id
			$this->Session->setFlash(__('Invalid post-id', true));
		}
		if($deleted){
			$this->Session->setFlash(__('Repost removed successfully.'));	
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
				
				debug(get_class($this->PostsUser));
				debug($this->PostsUser);die();
				
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