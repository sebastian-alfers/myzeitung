<?php

require "libs/Social/FacebookOAuth/src/base_facebook.php";
require "libs/Social/FacebookOAuth/src/facebook.php";
require "libs/Social/FacebookOAuth/config.php";

class PostsController extends AppController {

	const NO_TOPIC_ID = 'null';
	const ALLOW_COMMENTS_DEFAULT = 'default';
	const ALLOW_COMMENTS_TRUE = 'true';
	const ALLOW_COMMENTS_FALSE = 'false';

	var $name = 'Posts';

	var $components = array('JqImgcrop', 'Upload', 'Settings', 'Tweet');
	var $helpers = array('Cropimage', 'Javascript', 'Cksource', 'MzTime', 'Image', 'Reposter', 'MzText');




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
		    	'fields' => array(),
                'conditions' => array('Post.enabled' => true),
		//contain array: limit the (related) data and models being loaded per post
	            'contain' => array('Route', 'User.username','User.id', 'User.name', 'User.image'),
		)
		);

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
	function repost($post_id = null, $topic_id = null){
        $this->log($this->data);
        if(isset($this->data['Posts']['post_id'])){
            $post_id = $this->data['Posts']['post_id'];
         }
         if(isset($this->data['Posts']['topic_id']) && $this->data['Posts']['topic_id'] != 'null'){
            $topic_id = $this->data['Posts']['topic_id'];
         }

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
			// no post $id
			$this->Session->setFlash(__('No post id', true));
		}
		$this->redirect($this->referer());
	}


	/**
	 * @author tim
<?php echo $this->Html->link('<span>+</span>'.__('Subscribe', true), array('controller' => 'users',  'action' => 'subscribe', $user['User']['id']), array('escape' => false, 'class' => 'btn', ));?>	 * calling undoRepost in the model - explanation there
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
			// no post $id
			$this->Session->setFlash(__('No post id', true));
		}
		$this->redirect($this->referer());
	}


	/**
	 * @author Tim
	 * function for preparing date to view a specific post.
	 * @param $id
	 */
	function view($id = null) {
        $this->log($id);
		if (!$id) {
			$this->Session->setFlash(__('Invalid post', true));
			$this->redirect($this->referer());
		}
        $this->Post->contain('User.username','User.name', 'User.id', 'Topic.name', 'Topic.id');
		$post = $this->Post->read(null, $id);
        if(!isset($post['Post']['id']) || empty($post['Post']['id'])){
			$this->Session->setFlash(__('Invalid post', true));
			$this->redirect($this->referer());
        }
        if($post['Post']['enabled'] == false){
            $this->Session->setFlash(__('This post has been blocked temporarily due to infringement.', true));
			$this->redirect($this->referer());
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
		$this->Comment->contain('User.username','User.id','User.image', 'User.name');
		//'threaded' gets also the replies (children) and children's children etc. (for tree behavior. not sure if for not-tree also)
		$comments = $this->Comment->find('threaded',array(
										'conditions' => array('Comment.post_id' => $id,
                                                                'Comment.enabled' => true),
										'order'=>array('created DESC'),
										'fields' => array('id','user_id','post_id','parent_id','text','created')));


		$this->Post->contain('User.username','User.name', 'User.id', 'Topic.name', 'Topic.id');

		$post = $this->Post->read(null, $id);

		$this->User->contain('Topic.id', 'Topic.name', 'Topic.post_count', 'Paper.id' , 'Paper.title', 'Paper.image');
		$user = $this->User->read(array('id','name','username','created','image' , 'allow_messages', 'allow_comments','description','repost_count','post_count','comment_count', 'content_paper_count', 'subscription_count', 'paper_count'), $post['Post']['user_id']);
		//$this->log('vor dem set');die();
        $this->set('post', $post);
		$this->set('user', $user);
		$this->set('comments',$comments);
        

	}

	function add() {
		$error = false;

		$user_id = $this->Auth->User('id');
		if (!empty($this->data)) {

			//save new sorted images
            $images = '';
            $this->data['Post']['media'] = $this->_processMediaData(json_decode($this->data['Post']['media']));



            foreach($this->data['Post']['media'] as $item){
                $images .= $item['img_name'] . ',';
            }



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
            // temp. necessary until there is a dropdown for allow comments in post add edit view
            if(isset($this->data['Post']['allow_comments']) && !in_array($this->data['Post']['allow_comments'], array(self::ALLOW_COMMENTS_DEFAULT, self::ALLOW_COMMENTS_FALSE, self::ALLOW_COMMENTS_TRUE))){
                $this->data['Post']['allow_comments'] = self::ALLOW_COMMENTS_DEFAULT;
             }
          //  $this->log('direkt vor dem saven');
          //  $this->log($this->data);
			if ($this->Post->save($this->data)) {
                $post_url = 'http://myzeitung.de/posts/view/' . $this->Post->id;
                $social_msg = __('Checkout my latest article as myZeitung: ', true);
                $social_msg .= $post_url;

                if($this->Tweet->useTwitter()){
                    $this->Tweet->newPost($social_msg);
                }

                $this->data['User']['use_fb'] = false;
                $facebook = new Facebook(array(
                    'appId'  => FB_APP_ID,
                    'secret' => FB_APP_SECRET,
                    'cookie' => true
                ));
                // Get User ID
                $user = $facebook->getUser();

                // Login or logout url will be needed depending on current user state.
                if ($user) {
                    $request['message'] = $social_msg;
                    $request['name'] = $this->data['Post']['title'];
                    $request['link'] = $post_url;
                    //$request['description'] = "Test FB api";
                    try{
                      $response = $facebook->api('/me/feed',"POST",$request);

                  } catch (FacebookApiException $e) {
                      $this->log($e);
                  }

                }

                $this->Post->contain();
                $created_date = $this->Post->read('created', $this->Post->id);

				//copy images after post has been saved to add new post-id to img path
				if($this->Upload->hasImagesInHashFolder($this->data['Post']['hash'])){

					$this->images = $this->Upload->copyImagesFromHash($this->data['Post']['hash'], $this->Post->id, null, $images, 'post');

					if(is_array($this->images)){
						$hash = $this->data['Post']['hash'];
						//$this->data = array();
						$this->data = $temp_data;
						$this->data['Post']['image'] = $this->images;
                        $this->log($this->data['Post']['image']);
						$this->data["Post"]["user_id"] = $user_id;
						$this->data['Post']['hash'] = $hash;
						$this->data['Post']['content'] = $content;


                        if($images != ''){
                            $tranf_images = $this->_transformImages($images, $this->Post->id, $created_date['Post']['created']);

                            //extract video data if available
                            foreach($tranf_images as &$item){
                                $file_name =  $item['file_name'];

                                foreach($this->data['Post']['media'] as $data){
                                    if($file_name == $data['img_name'] && $data['item_type'] == 'video'){
                                        $item['item_type'] = $data['item_type'];
                                        unset($data['item_type']);
                                        $item['video'] = $data;

                                    }
                                }

                            }
                            $this->data['Post']['image'] = $tranf_images;
                        }
                        else{
                            //noe images
                            $this->data['Post']['image'] = '';
                        }


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
				$this->redirect(array('controller' => 'users',  'action' => 'view', 'username' => strtolower($this->Auth->User('username'))));
			} else {
                $validation_errors= $this->Post->invalidFields();

                foreach($validation_errors as $validation_error){
                    $flash = $validation_error;
                }
                if(empty($flash)){
                    $this->Session->setFlash(__('The post could not be saved. Please, try again.', true));
                } else {
                    $this->Session->setFlash($flash);
                }
				$error = true;
			}
		}

		//for 'list' is no contain() needed. just selects the displayfield of the specific model.
		$topics = array();
        $topics=$this->Post->Topic->find('list', array('conditions' => array('Topic.user_id' => $user_id)));
        $topics2[self::NO_TOPIC_ID] = __('No Topic', true);
        $topics = $topics2 + $topics;
        //BUG: array merge results in an array beginning with index 0,1,2 and not the topic ids!
        // see: http://php.net/manual/de/function.array-merge.php
        
        //$topics = array_merge($topics, $this->Post->Topic->find('list', array('conditions' => array('Topic.user_id' => $user_id))));
        //$this->log('TOPICS');
        //$this->log($topics);
        //$this->log($this->Post->Topic->find('list', array('conditions' => array('Topic.user_id' => $user_id))));
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

        if(isset($this->data['Post']['links']) && !empty($this->data['Post']['links'])){
            $this->set('links', unserialize($this->data['Post']['links']));
        }


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
			$this->redirect($this->referer());
		}
		//check, if the user owns the post
		$this->Post->contain();
		$post = $this->Post->read(array('user_id', 'created', 'image', 'topic_id'), $id);
		$owner_id = $post['Post']['user_id'];
		$created = $post['Post']['created'];
		$old_images = $post['Post']['image'];


		if($owner_id != $user_id){
			$this->Session->setFlash(__('The Post does not belong to you.', true));
			$this->redirect($this->referer());
		}

		//jepp, he is the owner!


		if($this->data['Post']['topic_id'] == self::NO_TOPIC_ID){
			//if no topic -> remote value to make NULL in db
			$this->data['Post']['topic_id'] = NULL;
		}

		$user_id = $this->Auth->User('id');
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid post', true));
			$this->redirect($this->referer());
		}
		if (!empty($this->data)) {
			//save new sorted images
            $images = '';
            $this->data['Post']['media'] = $this->_processMediaData(json_decode($this->data['Post']['media']));

            foreach($this->data['Post']['media'] as $item){
                $images .= $item['img_name'] . ',';
            }

			if($this->Upload->hasImagesInHashFolder($this->data['Post']['hash'])){
                //process data format of media


				$this->images = $this->Upload->copyImagesFromHash($this->data['Post']['hash'], $id, $created, $images, 'post');
				if(is_array($this->images)){
				}
			}
				
			if($images != ''){
				$tranf_images = $this->_transformImages($images, $id, $created);

                //extract video data if available
                foreach($tranf_images as &$item){
                    $file_name =  $item['file_name'];

                    foreach($this->data['Post']['media'] as $data){
                        if($file_name == $data['img_name'] && $data['item_type'] == 'video'){
                            $item['item_type'] = $data['item_type'];
                            unset($data['item_type']);
                            $item['video'] = $data;

                        }
                    }

                }

				$this->data['Post']['image'] = $tranf_images;
			}
			else{
				//noe images
				$this->data['Post']['image'] = '';
			}
				
			//process links
			$this->processLinks();

            $this->log('var 1= '.$post['Post']['topic_id']);
            $this->log('var 2= '.$this->data['Post']['topic_id']);
			if($post['Post']['topic_id'] != $this->data['Post']['topic_id']){
				$this->Post->topicChanged = true;
                $this->log('topicchanged true controller');
			}
            // temp. necessary until there is a dropdown for allow comments in post add edit view
            if(isset($this->data['Post']['allow_comments']) && !in_array($this->data['Post']['allow_comments'], array(self::ALLOW_COMMENTS_DEFAULT, self::ALLOW_COMMENTS_FALSE, self::ALLOW_COMMENTS_TRUE))){
                $this->data['Post']['allow_comments'] = self::ALLOW_COMMENTS_DEFAULT;
             }



			$this->Post->updateSolr = true;

			if ($this->Post->save($this->data)) {
				$this->Upload->removeTmpHashFolder($this->data['Post']['hash']);
				$this->Session->setFlash(__('The post has been saved', true), 'default', array('class' => 'success'));
				$this->redirect(array('controller' => 'users',  'action' => 'view', 'username' => strtolower($this->Session->read('Auth.User.username'))));
			} else {



                if(empty($this->data['Post']['topic_id'])){
                    //needs to be done to select "no topic" on error
                    $this->data['Post']['topic_id'] = self::NO_TOPIC_ID;
                }

                $errors = $this->Post->invalidFields();
                $flashMessage = __('The post could not be saved. Please, try again.', true);
                if(is_array($errors)){
                    // using first error as flashs
                    foreach($errors as $error){
                        $flashMessage = $error;
                        break;
                    }
                }
				$this->Session->setFlash($flashMessage);
			}
		}
		if (empty($this->data)) {
			$this->Post->contain();
			$this->data = $this->Post->read(null, $id);

            if(!empty($this->data['Post']['media'])){
                $this->data['Post']['image'] = unserialize($this->data['Post']['image']);
            }

			if(empty($this->data['Post']['topic_id']))$this->data['Post']['topic_id'] = 'null';
		}
        
		$topics = array();
        $topics=$this->Post->Topic->find('list', array('conditions' => array('Topic.user_id' => $user_id)));
        $topics2[self::NO_TOPIC_ID] = __('No Topic', true);
        $topics = $topics2 + $topics;

		$allow_comments[self::ALLOW_COMMENTS_DEFAULT] = __('use privacy settings',true);
		$allow_comments[self::ALLOW_COMMENTS_TRUE] = __('Yes',true);
		$allow_comments[self::ALLOW_COMMENTS_FALSE] = __('No',true);

		//set images
		if(isset($this->data['Post']['image']) && !empty($this->data['Post']['image'])){
			$return_imgs = array();


            if(!is_array($this->data['Post']['image'])){
                $this->data['Post']['image'] = unserialize($this->data['Post']['image']);
            }
			foreach ($this->data['Post']['image'] as $img){

                $tmp = array('path' => $img['path'], 'name' => $img['file_name']);
                if(isset($img['video'])) $tmp['video'] = $img['video'];
                if(!isset($img['item_type']) || $img['item_type'] == ''){
                    $tmp['item_type'] = 'image';
                }
                else{
                    $tmp['item_type'] = $img['item_type'];
                }

                $return_imgs[] = $tmp;
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
			$this->redirect($this->referer());
		}
        $this->Post->contain();
        $post =  $this->Post->read(array('id','user_id'), $id);
        if($post['Post']['user_id'] == $this->Session->read('Auth.User.id')){
            // second param = cascade -> delete associated records from hasmany , hasone relations
            if ($this->Post->delete($id, true)) {
                $this->Session->setFlash(__('Post deleted', true), 'default', array('class' => 'success'));
                $this->redirect(array('controller' => 'users',  'action' => 'view',  'username' => strtolower($this->Session->read('Auth.User.username'))));
                
            $this->redirect($this->referer());
            }
            $this->Session->setFlash(__('Post was not deleted', true));
            $this->redirect($this->referer());
        } else {
            $this->Session->setFlash(__('The Post does not belong to you.', true));
       
            $this->redirect($this->referer());
        }
    }

	/**
	 * action to be called from multiple file upload for add / edit
	 */

	function ajxImageProcess(){
        $this->log('hier');
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
				$this->log('Can not remove file: '. $full_path);
			}
		}
		else{
			$this->log('File does not exist: '. $full_path);
		}

		if($post_id && !empty($post_id)){
			echo "delete post " . $post_id;
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

    /**
     * transform images to be saved
     *
     * @param  $imgs string - comma-seperated list of images
     * @param  $post_id int
     * @param  $timestamp
     * @return array
     */
	private function _transformImages($imgs, $post_id, $timestamp){
		$new_imgs = array();

        //remove empty element of array

		$imgs = explode(',', $imgs);
        $imgs = array_filter($imgs);

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


    function admin_index() {
        $this->paginate = array('contain' => array('Route', 'User.id', 'User.username'));
		$this->set('posts', $this->paginate());
	}
    function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for post', true));
			$this->redirect($this->referer());
		}
        $this->Post->contain();
        $post =  $this->Post->read(array('id','user_id'), $id);

        // second param = cascade -> delete associated records from hasmany , hasone relations
        if ($this->Post->delete($id, true)) {
            $this->Session->setFlash(__('Post deleted', true), 'default', array('class' => 'success'));
          //  $this->redirect(array('controller' => 'users',  'action' => 'view',  $this->Session->read('Auth.User.id')));
        $this->redirect($this->referer());
        }
        $this->Session->setFlash(__('Post was not deleted', true));
        $this->redirect($this->referer());

    }
    function admin_disable($post_id){
        $this->Post->contain();
        $post = $this->Post->read(null, $post_id);
        if(isset($post['Post']['id']) && !empty($post['Post']['id'])){
            if($post['Post']['enabled'] == false){
                $this->Session->setFlash('This post is already disabled');
                $this->redirect($this->referer());
            }else{
                if($this->Post->disable()){
                    $this->Session->setFlash('Post has been disabled successfully','default', array('class' => 'success'));
                    $this->redirect($this->referer());
            $this->redirect($this->referer());
                }else{
                    $this->Session->setFlash('This Post could not be disabled. Please try again.');
                    $this->redirect($this->referer());
                }
            }
        }else{
            $this->Session->setFlash('Invalid post');
            $this->redirect($this->referer());

        }
    }
    function admin_enable($post_id){
        $this->Post->contain('User');
        $post = $this->Post->read(null, $post_id);
        if(isset($post['Post']['id']) && !empty($post['Post']['id'])){
            if($post['Post']['enabled'] == true){
                $this->Session->setFlash('This post is already enabled');
                $this->redirect($this->referer());
            }else{
                 if($post['User']['enabled'] == false){
                    $this->Session->setFlash('The User that created the post is disabled. You cannot enable this post.');
                    $this->redirect($this->referer());
                }
                if($this->Post->enable()){
                    $this->Session->setFlash('Post has been enabled successfully','default', array('class' => 'success'));
                    $this->redirect($this->referer());
            $this->redirect($this->referer());
                }else{
                    $this->Session->setFlash('This Post could not be enabled. Please try again.');
                    $this->redirect($this->referer());
                }
            }
        }else{
            $this->Session->setFlash('Invalid post');
            $this->redirect($this->referer());

        }
    }

    /**
     * transforms the json_encoded string to more handy array
     *
     * @param  $array
     * @return array
     */
    private function _processMediaData($array){

        for($i = 0; $i < count($array); $i++){
            $tmp = array();
            foreach($array[$i] as $attribute){
               $tmp[$attribute[0]] = $attribute[1];
            }
            $array[$i] = $tmp;
        }

        return $array;

    }
}
?>