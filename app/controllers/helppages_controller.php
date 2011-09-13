<?php
class HelppagesController extends AppController {

	var $name = 'Helppages';

	function admin_index() {
		$this->Helppage->recursive = 0;
		$this->set('helppages', $this->paginate());
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid helppage', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('helppage', $this->Helppage->read(null, $id));
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Helppage->create();
			if ($this->Helppage->save($this->data)) {
				$this->Session->setFlash(__('The helppage has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The helppage could not be saved. Please, try again.', true));
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid helppage', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Helppage->save($this->data)) {
				$this->Session->setFlash(__('The helppage has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The helppage could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Helppage->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for helppage', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Helppage->delete($id)) {
			$this->Session->setFlash(__('Helppage deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Helppage was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
