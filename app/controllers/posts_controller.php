<?php
class PostsController extends AppController {

	const NO_TOPIC_ID = 'null';
	const ALLOW_COMMENTS_DEFAULT = 'default';
	const ALLOW_COMMENTS_TRUE = 'true';
	const ALLOW_COMMENTS_FALSE = 'false';

	var $name = 'Posts';

	var $components = array('JqImgcrop', 'Upload');
	var $helpers = array('Cropimage', 'Javascript', 'Cksource', 'Time', 'Image');




	var $uses = array('Post','PostUser', 'Route', 'Comment', 'UrlContentExtract');


	public function beforeFilter(){
		parent::beforeFilter();
		//declaration which actions can be accessed without being logged in
		$this->Auth->allow('index','view');
	}



	function index() {
		$this->paginate = array(
	        'Post' => array(
		//limit of records per page
	            'limit' => 9,
		//order
	            'order' => 'Post.created DESC',
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
			if($this->Post->read(null, $post_id)){
				if($this->Post->repost($this->Auth->user('id'), $topic_id)){
					$this->Session->setFlash(__('Post successfully reposted.', true), 'default', array('class' => 'success'));
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
					$this->Session->setFlash(__('Repost deleted successfully.', true), 'default', array('class' => 'success'));
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
		$this->Comment->contain('User.username','User.id','User.image');
		//'threaded' gets also the replies (children) and children's children etc. (for tree behavior. not sure if for not-tree also)
		$comments = $this->Comment->find('threaded',array(
										'conditions' => array('post_id' => $id),
										'order'=>array('created DESC'), 
										'fields' => array('id','user_id','post_id','parent_id','text','created')));


		$this->Post->contain('User.username','User.name', 'User.id', 'Topic.name', 'Topic.id');

		$post = $this->Post->read(null, $id);

		$this->User->contain('Topic.id', 'Topic.name', 'Topic.post_count', 'Paper.id' , 'Paper.title', 'Paper.image');
		$user = $this->User->read(array('id','name','username','created','image' , 'allow_messages', 'allow_comments','description','posts_user_count','post_count','comment_count', 'content_paper_count', 'subscription_count', 'paper_count'), $post['Post']['user_id']);
		$this->set('post', $post);
		$this->set('user', $user);
		$this->set('comments',$comments);

	}

	function add() {

		$error = false;

		$user_id = $this->Auth->User('id');
		if (!empty($this->data)) {
				
			//debug($this->data);die();
			if(isset($this->data['Post']['topic_id']) && $this->data['Post']['topic_id'] == self::NO_TOPIC_ID){
				unset($this->data['Post']['topic_id']);
			}

			$this->data["Post"]["user_id"] = $user_id;

			$content = $this->data["Post"]["content"];
			$this->Post->create();

			if($this->Upload->hasImagesInHashFolder($this->data['Post']['hash'])){
				// if images in folder, the solr_add is executed when the correct image array is saved
			//	$this->Post->updateSolr = false;
				$temp_data = $this->data;
				unset($temp_data['Post']['id']);
			}

			$this->processLinks();
			//add to solr if no pictures must be saved
			if(!($this->Upload->hasImagesInHashFolder($this->data['Post']['hash']))){
				$this->Post->updateSolr = true;
			}
			if ($this->Post->save($this->data)) {
					
				//copy images after post has been saved to add new post-id to img path
				if($this->Upload->hasImagesInHashFolder($this->data['Post']['hash'])){
					$this->images = $this->Upload->copyImagesFromHash($this->data['Post']['hash'], $this->Post->id, null, $this->data['Post']['images'], 'post');
					if(is_array($this->images)){
						$hash = $this->data['Post']['hash'];
						//$this->data = array();
						$this->data = $temp_data;
						$this->data['Post']['image'] = $this->images;
						$this->data["Post"]["user_id"] = $user_id;
						$this->data['Post']['hash'] = $hash;
						$this->data['Post']['content'] = $content;
						// writing the path of the first picture to a class variable because the array will be serialized before reaching the updateSolr method

						$this->Post->solr_preview_image = $this->images[0]['path'];
						$this->Post->updateSolr = true;
						if ($this->Post->save($this->data)) {

							//remove tmp hash folder
							$this->Upload->removeTmpHashFolder($this->data['Post']['hash']);
								
						}
						else{
							$this->Session->setFlash(__('Not able to copy images for post', true));
						}
					}
					else{
						$this->Session->setFlash(__('Not able to copy images for post', true));
					}
				}

				$this->Session->setFlash(__('The post has been saved', true), 'default', array('class' => 'success'));
				$this->redirect(array('controller' => 'users',  'action' => 'view', $user_id));
			} else {
				$this->Session->setFlash(__('The post could not be saved. Please, try again.', true));
				$error = true;
			}
		}

		//for 'list' is no contain() needed. just selects the displayfield of the specific model.
		$topics = $this->Post->Topic->find('list', array('conditions' => array('Topic.user_id' => $user_id)));
		$topics[self::NO_TOPIC_ID] = __('No Topic', true);
		$this->data['Post']['topic_id'] = self::NO_TOPIC_ID;

		$allow_comments[self::ALLOW_COMMENTS_DEFAULT] = __('default value',true);
		$allow_comments[self::ALLOW_COMMENTS_TRUE] = __('Yes',true);
		$allow_comments[self::ALLOW_COMMENTS_FALSE] = __('No',true);

		if($error){
			$this->set('hash', $this->data['Post']['hash']);

			if(isset($this->data['Post']['images']) && !empty($this->data['Post']['images'])){
				$tmp_images = explode(',', $this->data['Post']['images']);
				$return_imgs = array();

				$webroot = $this->Upload->getWebrootUrl();
				$path_to_tmp_folder = $webroot.$this->Upload->getPathToTmpHashFolder($this->data['Post']['hash']);
				foreach ($tmp_images as $img){

					$return_imgs[] = array('path' => 'tmp'.DS.$this->data['Post']['hash'].DS.$img, 'name' => $img);
				}
				if(count($return_imgs) > 0){
					$this->set('images', $return_imgs);
				}

			}
			//get images from hash folder is available
			//			if($this->_hasImagesInHashFolder()){
			//				$imagesFromHash = $this->_getImagesFromHash();
			//				foreach($imagesFromHash as &$img){
			//					$img = 'tmp'.DS.$this->data['Post']['hash'].DS.$img;
			//				}
			//				debug($this->data);
			//				debug($imagesFromHash);
			//				$this->set('images', $imagesFromHash);
			//			}
		}
		else{
			$this->set('hash', $this->Upload->getHash());
		}
		$this->set(compact('topics'));

		$this->set('allow_comments', $allow_comments);
		$this->set('user_id',$user_id);
		$this->set('content_class', 'create-article');//for css in main layout file


		//same template for add and edit
		$this->render('add_edit');

	}

	function edit($id = null) {

		$user_id = $this->Session->read('Auth.User.id');

		if($user_id == null || empty($user_id)){
			$this->Session->setFlash(__('No permission', true));
			$this->redirect(array('action' => 'index'));
		}
		//check, if the user owns the post
		$this->Post->contain();
		$post = $this->Post->read(array('user_id', 'created', 'image', 'topic_id'), $id);
		$owner_id = $post['Post']['user_id'];
		$created = $post['Post']['created'];
		$old_images = $post['Post']['image'];


		if($owner_id != $user_id){
			$this->Session->setFlash(__('No permission', true));
			$this->redirect(array('action' => 'index'));
		}

		//jepp, he is the owner!


		if($this->data['Post']['topic_id'] == self::NO_TOPIC_ID){
			//if no topic -> remote value to make NULL in db
			$this->data['Post']['topic_id'] = NULL;
		}

		$user_id = $this->Auth->User('id');
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid post', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			//save new sorted images

			if($this->Upload->hasImagesInHashFolder($this->data['Post']['hash'])){

				$this->images = $this->Upload->copyImagesFromHash($this->data['Post']['hash'], $id, $created, $this->data['Post']['images'], 'post');
				if(is_array($this->images)){
				}
			}
				
			if(!empty($this->data['Post']['images'])){
				$tranf_images = $this->_transformImages($this->data['Post']['images'], $id, $created);

				$this->data['Post']['image'] = $tranf_images;
			}
			else{
				//noe images
				$this->data['Post']['image'] = '';
			}
				
			//process links
			$this->processLinks();

			if($post['Post']['topic_id'] != $this->data['Post']['topic_id']){
				$this->Post->topicChanged = true;
			}
	
			$this->Post->updateSolr = true;
			$this->Post->solr_preview_image = $this->images[0]['path'];
			if ($this->Post->save($this->data)) {
				$this->Upload->removeTmpHashFolder($this->data['Post']['hash']);
				$this->Session->setFlash(__('The post has been saved', true), 'default', array('class' => 'success'));
				$this->redirect(array('controller' => 'users',  'action' => 'view', $user_id));
			} else {
				$this->Session->setFlash(__('The post could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->Post->contain();
			$this->data = $this->Post->read(null, $id);
			if(empty($this->data['Post']['topic_id']))$this->data['Post']['topic_id'] = 'null';
		}

		$topics = $this->Post->Topic->find('list', array('conditions' => array('Topic.user_id' => $user_id)));
		$topics[self::NO_TOPIC_ID] = __('No Topic', true);

		$allow_comments[self::ALLOW_COMMENTS_DEFAULT] = __('default value',true);
		$allow_comments[self::ALLOW_COMMENTS_TRUE] = __('Yes',true);
		$allow_comments[self::ALLOW_COMMENTS_FALSE] = __('No',true);

		//set images
		if(isset($this->data['Post']['image']) && !empty($this->data['Post']['image'])){
			//check, if there are already images
			if(isset($image) && !empty($image)){
				foreach($image as $img){

				}
			}


			$return_imgs = array();

			$webroot = $this->Upload->getWebrootUrl();
				
				
			//$path_to_tmp_folder = $webroot.$this->Upload->getPathToTmpHashFolder($this->data['Post']['hash']);
			foreach ($this->data['Post']['image'] as $img){
				$return_imgs[] = array('path' => $img['path'], 'name' => $img['file_name']);
			}
			if(count($return_imgs) > 0){
				$this->set('images', $return_imgs);
			}
		}//end images
		
		if(isset($this->data['Post']['links']) && !empty($this->data['Post']['links'])){
			$this->set('links', unserialize($this->data['Post']['links']));			
		}		
		
		$this->set('allow_comments', $allow_comments);
		$this->set(compact('topics', 'users'));

		$this->set('hash', $this->Upload->getHash());
		$this->set('user_id',$user_id);

		$this->set('content_class', 'create-article');//for css in main layout file

		//same template for add and edit
		$this->render('add_edit');
	}



	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for post', true));
			$this->redirect(array('action'=>'index'));
		}
		// second param = cascade -> delete associated records from hasmany , hasone relations
		if ($this->Post->delete($id, true)) {
			$this->Session->setFlash(__('Post deleted', true), 'default', array('class' => 'success'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Post was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}

	/**
	 * action to be called from multiple file upload for add / edit
	 */

	function ajxImageProcess(){
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
			//******************************************

			//remove whitespace etc from img name
			$file['name'] = $this->Upload->transformFileName($file['name']);

			//$this->log('****** ' . $file['name']);

			$uploaded = $this->JqImgcrop->uploadImage($file, $imgPath, '');

			$ret = '{"name":"'.$file['name'].'","path":"' . $imgPath.$file['name'] . '","type":"'.$file['type'].'","size":"'.$file['size'].'"}';
			//$this->log($ret);
			$this->set('files', $ret);
		}

		$this->render('ajxImageProcess', 'ajax');//custom ctp, ajax for blank layout
	}





	private function _getImagesFromHash($hash = ''){
		if($hash == '') $hash = $this->data['Post']['hash'];

		$webroot = $this->Upload->getWebrootUrl();
		$path_to_tmp_folder = $webroot.$this->_getPathToTmpHashFolder($hash);

		$imgs = array();

		//found folder
		if (is_dir($path_to_tmp_folder) && $handle = opendir($path_to_tmp_folder)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {

					$imgs[] = $file;
				}
			}
		}
		return $imgs;
	}

	/**
	 * get a valid url
	 *
	 */
	function url_content_extract(){
		if(isset($_POST['url'])){

			$url = $_POST['url'];
			$content = $this->UrlContentExtract->getContent($url);
			$this->set('content', $content);
		}
		else{
			echo '';
		}

		$this->render('ajx_url_content_extract', 'ajax');//custom ctp, ajax for blank layout
	}

	/**
	 * json method go validate an get preview image
	 * and video from url if valid
	 *
	 */
	function getVideoPreview(){

	}

	/**
	 * removes an image from file system
	 */
	function ajxRemoveImage(){
		$post_id = $_POST['id'];
		$img_path = $_POST['path'];
		$full_path = $this->Upload->getWebrootUrl().$img_path;

		//remove file
		if(file_exists($full_path)){
			if(unlink($full_path)){
				//$msg .= __('image has been removed', true);
			}
			else{
				$this->log('can not remove file: '. $full_path);
			}
		}
		else{
			$this->log('file does not exist: '. $full_path);
		}

		if($post_id && !empty($post_id)){
			echo "delte post " . $post_id;
			//			//update post if id is passed
			//			$this->Post->contain();
			//			$post = $this->Post->read(null, $post_id);
			//			if($post['Post']['user_id'] == $this->Session->read('Auth.User.id')){
			//				//remove file from db
			//				foreach($post['Post']['image'] as $key => $img_details){
			//					if($img_details['path'] ==$img_path){
			//						unset($post['Post']['image'][$key]);
			//					}
			//				}
			//				foreach($post['Post']['image'] as $key => $value){
			//					echo $key;
			//				}
			//				$post['Post']['image'] = array_values($post['Post']['image']);
			//				$this->Post->save($post);
			//			}
			//			else{
			//				$msg = __('No permission', true);
			//			}
		}
		$this->set('content', '');
		$this->render('ajx_url_content_extract', 'ajax');//empty ctp, ajax for blank layout
	}

	private function _transformImages($imgs, $post_id, $timestamp){
		$new_imgs = array();
		$imgs = explode(',', $imgs);

		$year = date('Y', strtotime($timestamp));
		$month = date('m', strtotime($timestamp));

		$rel_path = $year.DS.$month.DS.md5($post_id).DS;// starting from webroot/img/* folder

		$root = $this->Upload->getWebrootUrl().'img'.DS;
			

		foreach($imgs as $img){
			$path = 'post'.DS.$rel_path.$img;
			$new_imgs[] = array('path' => $path, 'file_name' => $img, 'size' => getimagesize($root.$path));
		}

		return $new_imgs;
	}

	/**
	 * get links from form and prepare them
	 */

	private function processLinks(){
		$links = $this->data['Post']['links'];
		if(isset($links) && !empty($links) && !is_array($links)){
			$this->data['Post']['links'] = serialize(array_filter(explode(',', $links)));
		}
	}
}
?>