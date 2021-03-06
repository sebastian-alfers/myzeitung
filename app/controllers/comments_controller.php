<?php
class CommentsController extends AppController {

	var $name = 'Comments';
    var $uses = array('Comment', 'Post', 'User');
	var $helpers = array('MzTime', 'Image');
    var $components = array('Email');
	
	public function beforeFilter(){
		parent::beforeFilter();
		//declaration which actions can be accessed without being logged in
		$this->Auth->allow('show');
	}


	function show($post_id = null) {
		$this->Comment->contain('User.username','User.id','User.image');
		//'threaded' gets also the replies (children) and children's children etc. (for tree behavior. not sure if for not-tree also)
		$comments = $this->Comment->find('threaded',array(
										'conditions' => array('post_id' => $post_id),
										'order'=>array('created DESC'), 
										'fields' => array('id','user_id','post_id','parent_id','text','created')));
		$this->set('comments',$comments);
		$this->set('post_id',$post_id);
	}



	/**
	 * add comment via ajax
	 *
	 */
	function ajxAdd(){
		
		$current_comment = array();

		if($this->_isValidRequest()){
			$this->log($this->data);
			$this->data['Comment']['user_id'] = $this->Session->read('Auth.User.id');

			$this->data['Comment']['post_id'] = $_POST['post_id'];

			if(isset($_POST['parent_id']) && !empty($_POST['parent_id'])){
				$this->data['Comment']['parent_id'] = $_POST['parent_id'];	
			}

			$this->data['Comment']['text'] = $_POST['text'];
			$this->Comment->create();
			if ($this->data = $this->Comment->save($this->data)) {
				
				//prepare data for ctp file comment_content.ctp
				$current_comment['User']['image'] = $this->Session->read('Auth.User.image');
				$current_comment['User']['id'] = $this->Session->read('Auth.User.id');
				$current_comment['User']['username'] = $this->Session->read('Auth.User.username');
                $current_comment['User']['name'] = $this->Session->read('Auth.User.name');
                $current_comment['Comment']['id'] = $this->Comment->id;
				$current_comment['Comment']['created'] = $this->data['Comment']['created'];
				$current_comment['Comment']['text'] = $this->data['Comment']['text'];
				$current_comment['Comment']['post_id']  = $this->data['Comment']['post_id'];
				$current_comment['Comment']['reply_id'] = $this->Comment->id;
                $current_comment['Comment']['user_id'] = $this->Session->read('Auth.User.id');
                $current_comment['Comment']['enabled'] = true;
                if($_POST['post_owner_id'] != $this->Session->read('Auth.User.id')){
                    $this->_sendCommentEmail($this->data);
                }

				
			} else {
				$this->log('can not save ajax comment for post '. $_POST['post_id'] .' with text: ' . $_POST['text']);
			}
		}
        $this->log($_POST);
        $this->set('post_owner', $_POST['post_owner_id']);
		$this->set('current_comment', $current_comment);

		$this->render('comment_content', 'ajax');//custom ctp, ajax for blank layout
	}
	
	/**
	 * vaildate request made by ajax call
	 * Enter description here ...
	 */
	private function _isValidRequest(){
		
		if(!isset($_POST['text']) || empty($_POST['text'])) return false;
		if(!isset($_POST['post_id']) || empty($_POST['post_id'])) return false;
		return true;
	}
	
	/**
	 * renders the template to add a comment
	 */
	function ajxGetForm(){
		
		$level = $_POST['level'];

		$this->set('class', $level);
		$this->render('form', 'ajax');//custom ctp, ajax for blank layout
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid comment id', true));
			$this->redirect($this->referer());
		}
		//check, if user is allwed to delte this comment
		$logged_in_user_id = $this->Session->read('Auth.User.id');
        $this->Comment->contain('Post.user_id');
		$comment_to_delete = $this->Comment->read(null, $id);
		$this->log($comment_to_delete); 
		if($logged_in_user_id != $comment_to_delete['Comment']['user_id'] && $logged_in_user_id != $comment_to_delete['Post']['user_id']){
			//not allowed
			$this->Session->setFlash(__('You are not allowed to delete this comment', true));
			$this->redirect($this->referer());			
		}
		
		
		if ($this->Comment->delete($id)) {
			$this->Session->setFlash(__('Comment deleted', true), 'default', array('class' => 'success'));
			$this->redirect($this->referer());
		}
		$this->Session->setFlash(__('Comment has not been deleted', true));
		$this->redirect($this->referer());
	}

        /**
        * send an email to the owner of a post
        */
    protected function _sendCommentEmail($comment) {
        $post = array();

        $this->Post->contain('Route');
        $post = $this->Post->read(array('id', 'title', 'user_id'), $comment['Comment']['post_id']);
        $userSettings = $this->User->getSettings($post['Post']['user_id']);

        if($userSettings['user']['email']['new_comment']['value'] == true){
             $tempLocale = $this->Session->read('Config.language');

              $this->Session->write('Config.language', $userSettings['user']['default']['locale']['value']);
                $owner = array();
                $commentator = array();
                $this->User->contain();
              $owner = $this->User->read(array('id', 'username', 'name', 'email'), $post['Post']['user_id']);
              $this->User->contain();
              $commentator = $this->User->read(array('id', 'username', 'name'), $comment['Comment']['user_id']);

              $this->set('recipient', $owner);
              $this->set('commentator', $commentator);
              $this->set('comment', $comment);
              $this->set('post', $post);

              App::Import('Helper','MzText');
              $this->MzText = new MzTextHelper();
              $commentator_name = $this->MzText->generateDisplayName($commentator['User'], true);


              $this->_sendMail($owner['User']['email'], sprintf(__('%s wrote a new comment to your post: %s', true), $commentator_name, $post['Post']['title']),'new_comment');

                  $this->Session->write('Config.language', $tempLocale);
          }

     }
    function admin_index() {
        $this->paginate = array('contain' => array('User.id', 'User.username', 'Post', 'Post.Route'));
		$this->set('comments', $this->paginate());
	}

    function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for comment', true));
			$this->redirect($this->referer());
		}
        // second param = cascade -> delete associated records from hasmany , hasone relations
        if ($this->Comment->delete($id, true)) {
            $this->Session->setFlash(__('Comment deleted', true), 'default', array('class' => 'success'));
          //  $this->redirect(array('controller' => 'users',  'action' => 'view',  $this->Session->read('Auth.User.id')));
        $this->redirect($this->referer());
        }
        $this->Session->setFlash(__('Comment was not deleted', true));
        $this->redirect($this->referer());

    }
    function admin_disable($comment_id){
        $this->Comment->contain();
        $comment = $this->Comment->read(null, $comment_id);
        if(isset($comment['Comment']['id']) && !empty($comment['Comment']['id'])){
            if($comment['Comment']['enabled'] == false){
                $this->Session->setFlash('This comment is already disabled');
                $this->redirect($this->referer());
            }else{
                if($this->Comment->disable()){
                    $this->Session->setFlash('Comment has been disabled successfully','default', array('class' => 'success'));
                    $this->redirect($this->referer());
                }else{
                    $this->Session->setFlash('This comment could not be disabled. Please try again.');
                    $this->redirect($this->referer());
                }
            }
        }else{
            $this->Session->setFlash('Invalid comment');
            $this->redirect($this->referer());

        }
    }
    function admin_enable($comment_id){
        $this->Comment->contain('User');
        $comment = $this->Comment->read(null, $comment_id);
        if(isset($comment['Comment']['id']) && !empty($comment['Comment']['id'])){
            if($comment['Comment']['enabled'] == true){
                $this->Session->setFlash('This comment is already enabled');
                $this->redirect($this->referer());
            }else{
                if($comment['User']['enabled'] == false){
                    $this->Session->setFlash('The User that created the comment is disabled. You cannot enable this comment.');
                    $this->redirect($this->referer());
                }
                if($this->Comment->enable()){
                    $this->Session->setFlash('Comment has been enabled successfully','default', array('class' => 'success'));
                    $this->redirect($this->referer());

                }else{
                    $this->Session->setFlash('This Comment could not be enabled. Please try again.');
                    $this->redirect($this->referer());
                }
            }
        }else{
            $this->Session->setFlash('Invalid Comment');
            $this->redirect($this->referer());

        }
    }
	
}
?>