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
		$error = false;

		$user_id = $this->Auth->User('id');
		if (!empty($this->data)) {
			//debug($this->data);
			if(isset($this->data['Post']['topic_id']) && $this->data['Post']['topic_id'] == self::NO_TOPIC_ID){
				unset($this->data['Post']['topic_id']);
			}

			$this->data["Post"]["user_id"] = $user_id;
			//$this->data['Post']['image_details'] = $this->data['Post']['image'];
			//$this->data['Post']['image'] = $this->data['Post']['image_details'];
			//debug($this->data);
			$this->Post->create();

			if ($this->Post->save($this->data)) {
				//path for image

				if($this->_hasImagesInHashFolder()){
					if($this->_copyPostImages()){

					}
					else{
						$this->Session->setFlash(__('not able to copy images for post', true));
					}
				}

				if($this->images && count($this->images)){
					$this->data['Post']['image'] = serialize($this->images);
				}



				$this->Session->setFlash(__('The post has been saved', true));
				$this->redirect(array('controller' => 'users',  'action' => 'view', $user_id));
			} else {
				debug($this->data);
				$this->Session->setFlash(__('The post could not be saved. Please, try again.', true));
				$error = true;
			}
		}

		//for 'list' is no contain() needed. just selects the displayfield of the specific model.
		$topics = $this->Post->Topic->find('list', array('conditions' => array('Topic.user_id' => $user_id)));
		$topics['null'] = __('No Topic', true);

		if($error){
			$this->set('hash', $this->data['Post']['hash']);

			//get images from hash folder is available
			if($this->_hasImagesInHashFolder()){
				$imagesFromHash = $this->_getImagesFromHash();
				foreach($imagesFromHash as &$img){
					$img = 'tmp'.DS.$this->data['Post']['hash'].DS.$img;
				}
				debug($imagesFromHash);
				$this->set('images', $imagesFromHash);
			}
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
		//debug($this->data);die();
		unset($this->data['Post']['image']);


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
			if(empty($this->data['Post']['topic_id']))$this->data['Post']['topic_id'] = 'null';
		}
		$topics = $this->Post->Topic->find('list', array('conditions' => array('Topic.user_id' => $user_id)));
		$topics['null'] = __('No Topic', true);

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

	private function _copyPostImages(){
		$this->images = array();
		//get tmp hash folder for images
		$hash = $this->data['Post']['hash'];

		$webroot = $this->_getWebrootUrl();
		$path_to_tmp_folder = $webroot.$this->_getPathToTmpHashFolder();
		//debug($path_to_tmp_folder);

		//debug($post_img_folder);die();

		if(is_dir($path_to_tmp_folder)){

			$post_img_folder = $webroot.'img'.DS.'post'.DS.$this->Post->id.DS;
			//create folder for new post
			if(is_dir($post_img_folder)){
				//this should NOT be possible
				$this->log('Error. Folder for new post already exists. path: ' . $post_img_folder);
				return false;
			}
			else{
				if (!mkdir($post_img_folder, 0700, true)) {
					$this->log('can not create directory for post: ' . $post_img_folder);
					return false;
				}
			}
			//found folder
			if ($handle = opendir($path_to_tmp_folder)) {
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != "..") {
						$this->log('from: ' . $path_to_tmp_folder.$file . ' -> '  . $post_img_folder.$file);
						if (copy($path_to_tmp_folder.$file , $post_img_folder.$file)) {
							unlink($path_to_tmp_folder.$file);
							$this->images[] = $file;
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

	private function _hasImagesInHashFolder(){
		$hash = $this->data['Post']['hash'];

		$webroot = ROOT.DS.APP_DIR.DS.WEBROOT_DIR.DS; //until webroot/
		$path_to_tmp_folder = $webroot.'img'.DS.'tmp'.DS.$hash.DS;

		return is_dir($path_to_tmp_folder);
	}

	private function _getWebrootUrl(){
		return  ROOT.DS.APP_DIR.DS.WEBROOT_DIR.DS; //until webroot/
	}

	private function _getPathToTmpHashFolder($hash = ''){
		if($hash == '') $hash = $this->data['Post']['hash'];

		return 'img'.DS.'tmp'.DS.$hash.DS;

	}

	private function _getImagesFromHash($hash = ''){
		if($hash == '') $hash = $this->data['Post']['hash'];

		$webroot = $this->_getWebrootUrl();
		$path_to_tmp_folder = $webroot.$this->_getPathToTmpHashFolder();
		
		$imgs = array();
		
		//found folder
		if ($handle = opendir($path_to_tmp_folder)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					
					$imgs[] = $file;
				}
			}
		}
		return $imgs;
	}
}
?>