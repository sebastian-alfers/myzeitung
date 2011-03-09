<?php
class CommentsController extends AppController {

	var $name = 'Comments';
	
	 	public function beforeFilter(){
 		parent::beforeFilter();
 		//declaration which actions can be accessed without being logged in
 		$this->Auth->allow('show');
 	}
 	

	function show($post_id = null) {
		$this->Comment->contain('User.username', 'User.firstname', 'User.name', 'User.id');
		//'threaded' gets also the replies (children) and children's children etc.
		$comments = $this->Comment->find('threaded',array('conditions' => array('post_id' => $post_id),'order'=>array('created DESC')));
		$this->set('comments',$comments);
		$this->set('post_id',$post_id);
	}



	function add($post_id = null, $parent_id = null) {
		if (!empty($this->data)) {
			$this->Comment->create();	
			if ($this->Comment->save($this->data)) {
				$this->Session->setFlash(__('The comment has been saved', true));
				$this->redirect(array('controller' => 'comments', 'action' => 'show', $this->data['Comment']['post_id']));
			} else {
				$this->Session->setFlash(__('The comment could not be saved. Please, try again.', true));
			}
		}
		$this->set('post_id', $post_id);
		$this->set('parent_id', $parent_id);
		$this->set('user_id', $this->Auth->user('id'));
	}
/*
	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid comment', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('comment', $this->Comment->read(null, $id));
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
		$this->set(compact('users', 'posts'));
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
	*/
}
?>