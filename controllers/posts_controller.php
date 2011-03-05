<?php
class PostsController extends AppController {

	var $name = 'Posts';
 	var $components = array('Auth', 'Session');
 	var $uses = array('Post','PostsUser', 'Route', 'Comment');
 	
 	
 	public function beforeFilter(){
 		parent::beforeFilter();
 		//declaration which actions can be accessed without being logged in
 		$this->Auth->allow('index','view','blog');
 	}
 	
 	

	function index() {
		$this->Post->recursive = 0;
		$this->set('posts', $this->paginate());
	}
	
	

	/**
	 * @author tim
	 * 
	 * reposting means to recommend a post of another user to your followers. 
	 * it will be shown on your own blog-page and be marked as reposted. (comparable to re-tweet)
	 * -> to do so: this function creates an entry in the table posts_users
	 * 		with the redirected post and the user who is recommending it and his topic_id in which he reposts it.
	 * Furthermore an entry in the "reposters" array of the Post is added, to check quickly if a
	 *  User already reposted a post (especially for better performance in views)	
	 *  
	 * @param int $post_id  -> reposted post
	 * @param int $topic_id -> topic of the _reposter_ in which he wants to repost the post (!this is not the topic in which the original author publicized it!)
	 * 
	 * 27.02.11 /tim - rewrote procedure; added topic_id into post_users; added check for existing posts 
	 * 
	 */
	
	function repost($post_id, $topic_id){
		if(isset($post_id) && isset($topic_id)){
			
			//check if the user already reposted the post -> just one post / user combination allowed (topic doesn't matter here)
			$postsUserData = array('repost' => true,
									'post_id' => $post_id,
								   	'user_id' => $this->Auth->user('id'));
			$repostEntries = $this->PostsUser->find('all',array('conditions' => $postsUserData));
			// if there are no reposts for this post/user combination yet
			if(!isset($repostEntries[0])){
				//reading post 
				$this->Post->contain();
				$this->data = $this->Post->read(null, $post_id);
				//valid post was found
				if($this->Post->id){
					$this->PostsUser->create();
					// adding the topic_id to the repost array
					$postsUserData = array('repost' => true,
											'post_id' => $post_id,
											'topic_id' =>  $topic_id,
										   	'user_id' => $this->Auth->user('id'));
					if($this->PostsUser->save($postsUserData)){
							//repost was saved
							$this->Session->setFlash(__('The Post has been reposted successfully.', true));
							// writing the reposter's user id into the reposters-array of the post, if not already in reposters array		
							if((empty($this->Post->reposters)) || (!in_array($this->Auth->user('id'),$this->Post->reposters))){

								$this->data['Post']['reposters'][] = $this->Auth->user('id');
								//increment count_reposts for the reposted post (by count reposters array)
								$this->data['Post']['count_reposts'] = count($this->data['Post']['reposters']);							
								$this->Post->save($this->data['Post']);
							}
						}	else {
					// repost couldn't be saved
					$this->Session->setFlash(__('The Post could not be reposted.', true));
					}
				}else {
					// post was not found
					$this->Session->setFlash(__('Invalid post', true));	
				}
			}else{
				// already reposted
				$this->Session->setFlash(__('Post has already been reposted.', true));
				$this->log('Post/Repost: User '.$this->Auth->user('id').' tried to repost Post'.$post_id.' which he had already reposted.');
			}
		}else {
			if(!isset($post_id)){
				// no post id
				$this->Session->setFlash(__('Invalid post id.', true));
			} elseif(!isset($post_id)){
				// no topic id
				$this->Session->setFlash(__('Invalid topic id.', true));
			}
		}
		$this->redirect($this->referer());
	}
	

		

	/**
	 * @author tim
	 * 
	 * deleting a repost: if a user wants to undo a repost this function will delete the repost from the posts_user table. additionally 
	 * the repost_counter will be decremented and the user will be deleted from the reposters array in the post.
	 * 
	 * @param $post_id - id of the post, for that the user wants to delete his repost
	 */
	function undoRepost($post_id){
		if(isset($post_id)){
			// just in case there are several reposts (PostsUser.repost => true) for the combination post/user - all will be deleted.
			$reposts =  $this->PostsUser->find('all',array('conditions' => array('PostsUser.repost' => true,'PostsUser.post_id' => $post_id, 'PostsUser.user_id' => $this->Auth->user('id'))));
			$delete_counter = 0;
			foreach($reposts as $repost){
				//deleting the repost from the PostsUser-table
				$this->PostsUser->delete($repost['PostsUser']['id']);
				$delete_counter += 1;
			}
			//writing log entry if there were more than one entries for this repost (shouldnt be possible)
			if($delete_counter > 1){
				$this->log('Post/undoRepost: User '.$this->Auth->user('id').' had more then 1 repost entry (posts_user table) for Post '.$post_id.'. (now deleted) This should not be possible.');
			}
			
			if($delete_counter >= 1){
				$this->Session->setFlash(__('Repost removed successfully.', true));	
				
				//reading related post to decrement the repost-counter and delete user id from the reposters array
				$this->Post->contain();
				$this->data = $this->Post->read(null,$post_id);

				//deleting user-id entry from reposters-array in post-model
				if(in_array($this->Auth->user('id'),$this->data['Post']['reposters'])){
					$pos = array_search($this->Auth->user('id'),$this->data['Post']['reposters']);
					unset($this->data['Post']['reposters'][$pos]);
				}
				
				$this->data['Post']['count_reposts'] = count($this->data['Post']['reposters']);
				$this->Post->save($this->data['Post']);
			} else {
				$this->Session->setFlash(__('Repost could not be removed.', true));
			}
		}
		else {
			// no repost $id
			$this->Session->setFlash(__('Invalid post-id', true));
		}
		
		$this->redirect($this->referer());
	}
	
	/**
	 * @author tim
	 * function to add a comment to a post. a comment always belongs to one post. a comment _can_ addtionally belong to another comment (reply).
	 * @param $post_id
	 * @param $comment_id / optional - just needed if comment replies to another comment
	 */
/*	function addComment($post_id, $comment_id = null){
		if($post_id){
				$this->Comment->create();
				$ok = true;
				//if the comment_id is not null -> check if related comment is a comment of the same post
				if($comment_id != null){
					$replied_comment = $this->Comment->read($comment_id);
					if($replied_comment['post_id'] == $post_id)
					{
						$this->data['Comment']['comment_id'] = $comment_id;
					} else {
						//comment_id belongs to a comment of another post
						$this->Session->setFlash(__('Replied comment does not belong to the same post.', true));		
						$ok = false;
					}
				}
				if($ok){
					$this->data['Comment']['post_id'] = $post_id;
				}

		} else {
			//no post id
			$this->Session->setFlash(__('Invalid post', true));			
		}
		
		$this->redirect($this->referer());
	}
*/
	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid post', true));
			$this->redirect(array('action' => 'index'));
		}
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