<?php
class PostsController extends AppController {

	const NO_TOPIC_ID = 'null';

	var $name = 'Posts';

	var $components = array('JqImgcrop');
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
			if($this->Post->read(null, $post_id)){
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
		$this->Comment->contain('User.username','User.id','User.image');
		//'threaded' gets also the replies (children) and children's children etc. (for tree behavior. not sure if for not-tree also)
		$comments = $this->Comment->find('threaded',array(
										'conditions' => array('post_id' => $id),
										'order'=>array('created DESC'), 
										'fields' => array('id','user_id','post_id','parent_id','text','created')));


		$this->Post->contain('User.username','User.name', 'User.id', 'Topic.name', 'Topic.id');

		$post = $this->Post->read(null, $id);
		$user = $this->User->read(null, $post['Post']['user_id']);
		$this->set('post', $post);
		$this->set('user', $user);
		$this->set('comments',$comments);

	}

	function add() {

		$error = false;

		$user_id = $this->Auth->User('id');
		if (!empty($this->data)) {

			//debug($this->data);
			if(isset($this->data['Post']['topic_id']) && $this->data['Post']['topic_id'] == self::NO_TOPIC_ID){
				unset($this->data['Post']['topic_id']);
			}

			$this->data["Post"]["user_id"] = $user_id;

			$content = $this->data["Post"]["content"];
			$this->Post->create();

			if ($this->Post->save($this->data)) {

				//copy images after post has been saved to add new post-id to img path
				if($this->_hasImagesInHashFolder()){
					if($this->_copyPostImages()){
						$hash = $this->data['Post']['hash'];
						$this->data = array();
						$this->data['Post']['image'] = $this->images;
						$this->data["Post"]["user_id"] = $user_id;
						$this->data['Post']['hash'] = $hash;
						$this->data['Post']['content'] = $content;

						$this->Post->add_solr = false;
						if ($this->Post->save($this->data)) {
							//remove tmp hash folder
							$this->_removeTmpHashFolder();

							if(!rmdir($path_to_tmp_folder)){
								$this->log('Not able to remove tmp hash folder: ' . $path_to_tmp_folder);
							}
						}
						else{
							$this->Session->setFlash(__('Not able to copy images for post', true));
						}
					}
					else{
						$this->Session->setFlash(__('Not able to copy images for post', true));
					}
				}

				$this->Session->setFlash(__('The post has been saved', true));
				$this->redirect(array('controller' => 'users',  'action' => 'view', $user_id));
			} else {
				$this->Session->setFlash(__('The post could not be saved. Please, try again.', true));
				$error = true;
			}
		}

		//for 'list' is no contain() needed. just selects the displayfield of the specific model.
		$topics = $this->Post->Topic->find('list', array('conditions' => array('Topic.user_id' => $user_id)));
		$topics['null'] = __('No Topic', true);

		if($error){
			$this->set('hash', $this->data['Post']['hash']);

			if(isset($this->data['Post']['images']) && !empty($this->data['Post']['images'])){
				$tmp_images = explode(',', $this->data['Post']['images']);
				$return_imgs = array();

				$webroot = $this->_getWebrootUrl();
				$path_to_tmp_folder = $webroot.$this->_getPathToTmpHashFolder();
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
			$this->set('hash', $this->_getHash());
		}

		$this->set(compact('topics'));
		$this->set('user_id',$user_id);


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
		$post = $this->Post->read(array('user_id', 'created', 'image'), $id);
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
			unset($this->data['Post']['topic_id']);
		}

		$user_id = $this->Auth->User('id');
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid post', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			//save new sortet images


			if($this->_hasImagesInHashFolder()){
				if($this->_copyPostImages($id, $created)){
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
			
				

			if ($this->Post->save($this->data)) {
				$this->_removeTmpHashFolder();
				$this->Session->setFlash(__('The post has been saved', true));
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
		$topics['null'] = __('No Topic', true);

		//set images
		if(isset($this->data['Post']['image']) && !empty($this->data['Post']['image'])){
			//check, if there are already images
			if(isset($image) && !empty($image)){
				foreach($image as $img){

				}
			}


			$return_imgs = array();

			$webroot = $this->_getWebrootUrl();
			$path_to_tmp_folder = $webroot.$this->_getPathToTmpHashFolder();
			foreach ($this->data['Post']['image'] as $img){
				$return_imgs[] = array('path' => $img['path'], 'name' => $img['file_name']);
			}
			if(count($return_imgs) > 0){
				$this->set('images', $return_imgs);
			}

		}

		$this->set(compact('topics', 'users'));

		$this->set('hash', $this->_getHash());
		$this->set('user_id',$user_id);
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
			$this->Session->setFlash(__('Post deleted', true));
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

			//$this->log($img);

			//$this->log('****** ' . $file['name']);
			//string to filename
			//@todo refactor in external file
			$file['name'] = preg_replace('/^\W+|\W+$/', '', $file['name']); // remove all non-alphanumeric chars at begin & end of string
			$file['name'] = preg_replace('/\s+/', '_', $file['name']); // compress internal whitespace and replace with _
			$file['name'] = strtolower(preg_replace('/\W-/', '', $file['name'])); // remove all non-alphanumeric chars except _ and -


			//$this->log('****** ' . $file['name']);

			$uploaded = $this->JqImgcrop->uploadImage($file, $imgPath, '');

			$ret = '{"name":"'.$file['name'].'","path":"' . $imgPath.$file['name'] . '","type":"'.$file['type'].'","size":"'.$file['size'].'"}';
			//$this->log($ret);
			$this->set('files', $ret);
		}

		$this->render('ajxImageProcess', 'ajax');//custom ctp, ajax for blank layout
	}


	private function _getHash(){
		return md5(microtime().$this->Session->read('sessID'));
	}

	/**
	 * copy images
	 *
	 * @param int id of post
	 * @param $timestamp date the post is created
	 */
	private function _copyPostImages($post_id = null, $timestamp = null){
		$this->images = array();
		//get tmp hash folder for images
		$hash = $this->data['Post']['hash'];

		$webroot = $this->_getWebrootUrl();
		$path_to_tmp_folder = $webroot.$this->_getPathToTmpHashFolder();

		if(is_dir($path_to_tmp_folder)){

			if($timestamp == null){
				$year = date('Y');
				$month = date('m');
			}
			else{
				$year = date('Y', strtotime($timestamp));
				$month = date('m', strtotime($timestamp));
			}

			if($post_id == null){
				$post_id = $this->Post->id;
			}

			//for new posts, use current timesempt, for edit posts user the posts creation date for path
			$new_rel_path = $year.DS.$month.DS.$post_id.DS;// starting from webroot/img/* folder
			$post_img_folder = $webroot.'img'.DS.'posts'.DS.$new_rel_path;
			//create folder for new post
			if(!is_dir($post_img_folder)){
				if (!mkdir($post_img_folder, 0700, true)) {
					$this->log('can not create directory for post: ' . $post_img_folder);
					return false;
				}
			}

			//found folder

			if ($handle = opendir($path_to_tmp_folder)) {
				$images = $this->data['Post']['images'];
				$images= explode(",", $images);
					
				foreach($images as $file){

					$tmp_path = $path_to_tmp_folder.$file;  //root/path/to/hash/file.jpg
					$new_full_path = $post_img_folder.$file; //root/path/to/new/file.jpg

					
					if(!is_dir($tmp_path) && file_exists($tmp_path)){
						$size = getimagesize($tmp_path);

						if (copy($tmp_path , $new_full_path)) {
							unlink($tmp_path);
							$this->images[] = array('path' => 'posts'.DS.$new_rel_path.$file, 'file_name' => $file, 'size' => $size);
						}
					}


				}
			}
			else{
				$this->log('can not open directory for copy images: ' . $path_to_tmp_folder);
				return false;
			}

		}
		else{
			$this->log('given path is no directory:' . $path_to_tmp_folder);
			return false;
		}

		return true;
	}

	private function _hasImagesInHashFolder($hash = ''){
		if($hash == '') $hash = $this->data['Post']['hash'];

		$webroot = ROOT.DS.APP_DIR.DS.WEBROOT_DIR.DS; //until webroot/
		$path_to_tmp_folder = $webroot.'img'.DS.'tmp'.DS.$hash.DS;

		return is_dir($path_to_tmp_folder);
	}

	private function _getWebrootUrl(){
		return  ROOT.DS.APP_DIR.DS.WEBROOT_DIR.DS; //until webroot/
	}

	private function _getPathToTmpHashFolder($hash = ''){
		if($hash == '' && isset($this->data['Post']['hash'])) $hash = $this->data['Post']['hash'];

		if(!$hash || empty($hash)) $hash = $this->_getHash();

		return 'img'.DS.'tmp'.DS.$hash.DS;

	}

	private function _getImagesFromHash($hash = ''){
		if($hash == '') $hash = $this->data['Post']['hash'];

		$webroot = $this->_getWebrootUrl();
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
	 * removes the tmp folder
	 */
	private function _removeTmpHashFolder(){
		$webroot = $this->_getWebrootUrl();
		$path_to_tmp_folder = $webroot.$this->_getPathToTmpHashFolder();
		//remove files in folder


		if (is_dir($path_to_tmp_folder) && $handle = opendir($path_to_tmp_folder)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					$tmp_path = $path_to_tmp_folder.$file;  //root/path/to/hash/file.jpg
					unlink($tmp_path);
				}
			}
		}
		else{
			$this->log('can not open directory for remove images: ' . $path_to_tmp_folder);
			return false;
		}
	}

	/**
	 * removes an image from file system
	 */
	function ajxRemoveImage(){
		$post_id = $_POST['id'];
		$img_path = $_POST['path'];
		$full_path = $this->_getWebrootUrl().$img_path;

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

		$rel_path = $year.DS.$month.DS.$post_id.DS;// starting from webroot/img/* folder

		$root = $this->_getWebrootUrl().'img'.DS;
			

		foreach($imgs as $img){
			$path = 'posts'.DS.$rel_path.$img;
			$new_imgs[] = array('path' => $path, 'file_name' => $img, 'size' => getimagesize($root.$path));
		}

		return $new_imgs;
	}
}
?>