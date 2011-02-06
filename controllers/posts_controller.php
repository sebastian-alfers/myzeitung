<?php
class PostsController extends AppController {

	var $name = 'Posts';
 	var $components = array('Auth', 'Session');
 	
 	
 	
 	public function beforeFilter(){
 		parent::beforeFilter();
 		//declaration which actions can be accessed without being logged in
 		$this->Auth->allow('index','view');
 	}

	function index() {
		$this->Post->recursive = 0;
		$this->set('posts', $this->paginate());
	}

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
				$this->Session->setFlash(__('The post has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The post could not be saved. Please, try again.', true));

			}
		}
		$user_id = $this->Auth->user('id');
		$topics = $this->Post->Topic->find('list');
		
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