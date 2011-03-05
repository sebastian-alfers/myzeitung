<?php
class CommentsController extends AppController {

	var $name = 'Comments';
	var $components = array('Auth', 'Session');
	var $uses = array ('Comment','Post');
 	
	public function beforeFilter(){
 		parent::beforeFilter();
 		//declaration which actions can be accessed without being logged in
 		$this->Auth->allow('index','view','blog');
 	} 	

	function index($post_id = null) {
		$this->Comment->contain('User.id','User.username','User.firstname','User.name','Comment');
		$comments = $this->Comment->find('all', array('conditions' => array('Comment.post_id' => $post_id, 'Comment.comment_id' => null)));
		debug($comments); die();
		$this->set('comments', $this->Comment->find('all', array('conditions' => array('Comment.post_id' => $post_id, 'Comment.comment_id' => null))));

	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid comment', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('comment', $this->Comment->read(null, $id));
	}

	function add($post_id = null, $comment_id = null) {
		if (!empty($this->data)) {
			$this->Comment->create();
			if ($this->Comment->save($this->data)) {	
				$this->Session->setFlash(__('The comment has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The comment could not be saved. Please, try again.', true));
			}
		}
		$this->set('user_id', $this->Auth->User("id"));
		$this->set('post_id', $post_id);
		$this->set('comment_id', $comment_id);
		
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid comment', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Comment->save($this->data)) {
				$this->Session->setFlash(__('The comment has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The comment could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Comment->read(null, $id);
		}
		$users = $this->Comment->User->find('list');
		$posts = $this->Comment->Post->find('list');
		$comments = $this->Comment->Comment->find('list');
		$this->set(compact('users', 'posts', 'comments'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for comment', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Comment->delete($id)) {
			$this->Session->setFlash(__('Comment deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Comment was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>