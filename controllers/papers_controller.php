<?php
class PapersController extends AppController {

	var $name = 'Papers';
	var $components = array('Auth', 'Session');
	var $uses = array('Paper', 'Route');


	public function beforeFilter(){
		parent::beforeFilter();
		//declaration which actions can be accessed without being logged in
		$this->Auth->allow('index');
	}

	function index() {
		$this->Paper->recursive = 0;
		$this->set('papers', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid paper', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('test', 'asdf');
		$this->set('paper', $this->Paper->read(null, $id));

	}

	function add() {
		if (!empty($this->data)) {
			//adding a route

			$this->Paper->create();
			$this->data['Paper']['owner_id'] = $this->Auth->User("id");
			if ($this->Paper->save($this->data)) {
				$routeData = array('Route' => array(
									'source' => $this->data['Paper']['title'],
									'ref_id' => $this->Paper->id,
									'target_controller' => 'papers',
									'target_action' => 'view',
									'target_param' => $this->Paper->id
				));

				$route = $this->Route->save($routeData);
				if(!empty($route)){
					$this->Session->setFlash(__('The paper has been saved', true));
					$this->redirect(array('action' => 'index'));						
				}
				else{
					$this->Session->setFlash(__('Paper saved, error wile saving the paper route', true));
				} 
			} else {
				$this->Session->setFlash(__('The paper could not be saved. Please, try again.', true));
			}

				


		}
		//$routes = $this->Paper->Route->find('list');
		//$this->set(compact('routes'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid paper', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Paper->save($this->data)) {
				$this->Session->setFlash(__('The paper has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The paper could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Paper->read(null, $id);
		}
		$routes = $this->Paper->Route->find('list');
		$this->set(compact('routes'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for paper', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Paper->delete($id)) {
			$this->Session->setFlash(__('Paper deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Paper was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
	
	/**
	 * add category for given paper
	 */
	function addcategory(){
		
	}
}
?>