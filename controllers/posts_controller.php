<?php
class PostsController extends AppController {

	const NO_TOPIC_ID = 'null';

	var $name = 'Posts';

	var $components = array('JqImgcrop');
	var $helpers = array('Cropimage', 'Javascript', 'Cksource', 'Time', 'Image');


	var $uses = array('Post','PostUser', 'Route', 'Comment');


	public function beforeFilter(){
		parent::beforeFilter();
		//declaration which actions can be accessed without being logged in
		$this->Auth->allow('index','view');
	}



	function index() {
		$this->paginate = array(
	        'Post' => array(
		//limit of records per page
	            'limit' => 10,
		//order
	            'order' => 'created DESC',
		//fields - custom field sum...
		    	'fields' => array(						
		),
		//contain array: limit the (related) data and models being loaded per post
	            'contain' => array('User.username','User.id'),
		)
		);
		//$this->Post->useCustom = false;
		//$this->Post->useCustom = false;

		$this->set('posts', $this->paginate());
	}



	/**
	 * @author tim
	 *
	 * explanation in model function
	 *
	 * @param int $post_id  -> reposted post
	 * @param int $topic_id -> (optional) topic of the _reposter_ in which he wants to repost the post (!this is not the topic in which the original author publicized it!)
	 *
	 * 29.03.11 /tim - moved most of the logic to the model

	 */
	function repost($post_id, $topic_id = null){
		if(isset($post_id)){

			$this->Post->contain();
			debug('repost action read');
			if($this->Post->read(null, $post_id)){
				debug('repost action AFTER read');
				if($this->Post->repost($this->Auth->user('id'), $topic_id)){
					$this->Session->setFlash(__('Post successfully reposted.', true));
				} else {
					$this->Session->setFlash(__('Post could not be reposted', true));
				}
			} else {
				$this->Session->setFlash(__('Invalid post id', true));
			}
		}
		else {
			// no paper $id
			$this->Session->setFlash(__('No paper id', true));
		}
		$this->redirect($this->referer());
	}


	/**
	 * @author tim
	 * calling undoRepost in the model - explanation there
	 *
	 * @param $post_id - id of the post, for that the user wants to delete his repost
	 */
	function undoRepost($post_id){
		if(isset($post_id)){

			$this->Post->contain();
			if($this->Post->read(null, $post_id)){

				if($this->Post->undoRepost($this->Auth->user('id'))){
					$this->Session->setFlash(__('Repost successfully deleted.', true));
				} else {
					$this->Session->setFlash(__('Repost could not be deleted', true));
				}
			} else {
				$this->Session->setFlash(__('Invalid post id', true));
			}
		}
		else {
			// no paper $id
			$this->Session->setFlash(__('No paper id', true));
		}
		$this->redirect($this->referer());
	}


	/**
	 * @author Tim
	 * function for preparing date to view a specific post.
	 * @param $id
	 */
	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid post', true));
			$this->redirect(array('action' => 'index'));
		}
		// incrementing post's view_counter

		// check if the user already read this post during this session
		//read_posts exists in the session?

		if($this->Session->check('read_posts')){
			$read_posts = $this->Session->read('read_posts');
			//read_posts is an array?
			if(is_array($read_posts)){
				if(!in_array($id,$read_posts)){
					//user has not read the post in this session -> increment
					$read_posts[] = $id;
					$this->Session->write('read_posts', $read_posts);
					$this->Post->doIncrement($id);
				}
			} else {
				//no read-posts array
				//user has not read the post in this session -> increment
				$this->Session->write('read_posts',array($id));
				$this->Post->doIncrement($id);
			}
		}else {
			//user has not read the post in this session -> increment
			$this->Session->write('read_posts',array($id));
			$this->Post->doIncrement($id);
		}

		$this->Post->contain('User.username','User.name','User.firstname', 'User.id', 'Topic.name', 'Topic.id');
		$this->set('post', $this->Post->read(null, $id));

	}

	function add() {
		$user_id = $this->Auth->User('id');
		if (!empty($this->data)) {

			if(isset($this->data['Post']['topic_id']) && $this->data['Post']['topic_id'] == self::NO_TOPIC_ID){
				unset($this->data['Post']['topic_id']);
			}
				
				

				
			$this->data["Post"]["user_id"] = $user_id;
			$this->data['Post']['image_details'] = $this->data['Post']['image'];
			$this->data['Post']['image'] = $this->data['Post']['image_details']['name'];
			$this->Post->create();
			if ($this->Post->save($this->data)) {
				//path for image
				$img = $this->data['Post']['image_details']['name'];

				$first = strtolower(substr($img,0,1));
				$second = strtolower(substr($img,1,1));
				$imgPath = 'img/post/'.$first.DS.$second;
				if($this->data['Post']['image_details']['name']){
					$uploaded = $this->JqImgcrop->uploadImage($this->data['Post']['image_details'], $imgPath, '');
				}

				//now add new url key for post
				/*		$route = new Route();
				$route->create();

				if( $route->save(array('source' => $this->data['Post']['title'] ,
				'target_controller' 	=> 'posts',
				'target_action'     	=> 'view',
				'target_param'		=> $this->Post->id)))
				{

					
				}*/

				$PostUserData = array('user_id' => $user_id,
									   'post_id' => $this->Post->id);
				if(isset($this->data['Post']['topic_id']) && $this->data['Post']['topic_id'] != self::NO_TOPIC_ID){
					$PostUserData['topic_id'] = $this->data['Post']['topic_id'];
				}

				$this->PostUser->create();
				$this->PostUser->save($PostUserData);

				//$this->set('uploaded',$uploaded);


				$this->Session->setFlash(__('The post has been saved', true));
				$this->redirect(array('controller' => 'users',  'action' => 'view', $user_id));
			} else {
				$this->Session->setFlash(__('The post could not be saved. Please, try again.', true));

			}
		}

		//for 'list' is no contain() needed. just selects the displayfield of the specific model.
		$topics = $this->Post->Topic->find('list', array('conditions' => array('Topic.user_id' => $user_id)));
		$topics[self::NO_TOPIC_ID] = __('No Topic', true);


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
			$this->Post->contain();
			$this->data = $this->Post->read(null, $id);
		}
		$topics = $this->Post->Topic->find('list');
		$this->set(compact('topics', 'users'));
	}



	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for post', true));
			$this->redirect(array('action'=>'index'));
		}
		// second param = cascade -> delete associated records from hasmany , hasone relations
		if ($this->Post->delete($id, true)) {
			$this->Session->setFlash(__('Post deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Post was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}


}
?>