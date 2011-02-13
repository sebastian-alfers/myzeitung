<?php
class UsersController extends AppController {

	var $name = 'Users';


	var $uses = array('User','Group', 'Topic', 'Route');


	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('add','login','logout', 'view');
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
		if($id == $this->Auth->User("id")){
			$isMyProfile = 1;
		}


		$this->set('isMyProfile', $isMyProfile);
		//unbinding irrelevant relations for the query 
		//$this->User->unbindModel(array('belongsTo' => array('Group'))); 
		$this->User->contain('Post');
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
				$this->Topic->create();
				$this->Topic->save($topicData);

				//@todo move it to a better place -> to user model
				//afer adding user -> add new route
				$route = new Route();
				$route->create();

				if( $route->save(array('source' => $this->data['User']['username'] ,
							   'target_controller' 	=> 'users',
							   'target_action'     	=> 'view',
							   'target_param'		=> $this->User->id)))
				{
					if($route->id){
						$this->data['User']['route_id'] = $route->id;
						$this->User->save($this->data);
						$this->redirect('/'.$this->data['User']['username']);
					}
					else{
						$this->Session->setFlash(__('The user has been saved', true));
						$this->redirect(array('action' => 'add'));
					}
				}
				else{
					$this->Session->setFlash(__('Please choose a valid url key', true));
					$this->redirect(array('action' => 'add'));
				}

				$this->Session->setFlash(__('The user has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
			}
		}
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