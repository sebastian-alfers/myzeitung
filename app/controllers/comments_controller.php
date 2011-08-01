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



	function add($post_id = null, $parent_id = null) {
		if (!empty($this->data)) {
			$this->Comment->create();
			if ($this->Comment->save($this->data)) {
				$this->Session->setFlash(__('The comment has been saved', true), 'default', array('class' => 'success'));
				$this->redirect(array('controller' => 'comments', 'action' => 'show', $this->data['Comment']['post_id']));
			} else {
				$this->Session->setFlash(__('The comment could not be saved. Please, try again.', true));
			}
		}
		$this->set('post_id', $post_id);
		$this->set('parent_id', $parent_id);
		$this->set('user_id', $this->Auth->user('id'));
	}

	/**
	 * add comment via ajax
	 *
	 */
	function ajxAdd(){
		
		$current_comment = array();

		if($this->_isValidRequest()){
			
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
                $current_comment['Comment']['id'] = $this->Comment->id;
				$current_comment['Comment']['created'] = $this->data['Comment']['created'];
				$current_comment['Comment']['text'] = $this->data['Comment']['text'];
				$current_comment['Comment']['post_id']  = $this->data['Comment']['post_id'];
				$current_comment['Comment']['reply_id'] = $this->Comment->id;
                $current_comment['Comment']['user_id'] = $this->Session->read('Auth.User.id');

                $this->_sendCommentEmail($this->data);

				
			} else {
				$this->log('can not save ajax comment for post '. $_POST['post_id'] .' with text: ' . $_POST['text']);
			}
		}

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
		
		
		if ($this->Comment->delete($id, true)) {
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
      $owner = array();
      $commentator = array();
      $post = array();

      $this->Post->contain();
      $post = $this->Post->read(array('id', 'title', 'user_id'), $comment['Comment']['post_id']);
      $this->User->contain();
      $owner = $this->User->read(array('id', 'username', 'name', 'email'), $post['Post']['user_id']);
      $this->User->contain();
      $commentator = $this->User->read(array('id', 'username', 'name'), $comment['Comment']['user_id']);

      $this->set('owner', $owner);
      $this->set('commentator', $commentator);
      $this->set('comment', $comment);

      $this->_sendMail($owner['User']['email'], __('New comment on your post:', true).' '.$post['Post']['title'],'new_comment');


     }
	
}
?>