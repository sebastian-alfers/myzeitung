<?php
class TopicsController extends AppController {

	var $name = 'Topics';

	function index() {
		$this->Topic->recursive = 0;
		$this->set('topics', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid topic', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('topic', $this->Topic->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Topic->create();
			if ($this->Topic->save($this->data)) {
				$this->Session->setFlash(__('The topic has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The topic could not be saved. Please, try again.', true));
			}
		}
		$users = $this->Topic->User->find('list');
		$this->set(compact('users'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid topic', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Topic->save($this->data)) {
				$this->Session->setFlash(__('The topic has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The topic could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Topic->read(null, $id);
		}
		$users = $this->Topic->User->find('list');
		$this->set(compact('users'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for topic', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Topic->delete($id)) {
			$this->Session->setFlash(__('Topic deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Topic was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>