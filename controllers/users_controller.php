<?php
class UsersController extends AppController {

	var $name = 'Users';

	var $uses = array('User','Group', 'Topic');

	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('add','login','logout');
		//can be overridden, e.g. from view()
		$this->set('isMyProfile', 0);		
	}


	public function login(){
	}

	function logout(){
		$this->redirect($this->Auth->logout());
	}


	function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());

	}

	function view($id = null) {
		//contian if view user == logged in user (Auth)
		$isMyProfile = 0;
		
		if (!$id) {
			//no param from url -> get from Auth
			$id = $this->Auth->User("id");
				
			if($id){
				//view user == Auth->User
				$isMyProfile = 1;
			}
			else{
				$this->Session->setFlash(__('Invalid user', true));
				$this->redirect(array('action' => 'index'));
			}
		}

		$this->set('isMyProfile', $isMyProfile);
		$this->set('user', $this->User->read(null, $id));
	}
	
	
	
	
	function add() {
		if (!empty($this->data)) {
			$this->data['User']['group_id'] = 1;
			$this->User->create();
				
			if ($this->User->save($this->data)) {

				//after adding user -> add new topic
				$newUserId = $this->User->id;
				$topicData = array('name' => 'test_topi', 'user_id' => $newUserId);

				$this->Topic->save($topicData);

				$this->Session->setFlash(__('The user has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
			}
		}
		echo 'gruppe:'.pr($this->Auth->user('group_id'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__('The user has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->User->read(null, $id);
		}
		$groups = $this->Group->find('list');
		$this->set('groups',$groups);
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for user', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->User->delete($id)) {
			$this->Session->setFlash(__('User deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('User was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>