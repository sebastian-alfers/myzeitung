<?php
class HelpelementsController extends AppController {

	var $name = 'Helpelements';

	function admin_index() {
		$this->Helpelement->recursive = 0;
		$this->set('helpelements', $this->paginate());
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid helpelement', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('helpelement', $this->Helpelement->read(null, $id));
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Helpelement->create();
			if ($this->Helpelement->save($this->data)) {
				$this->Session->setFlash(__('The helpelement has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The helpelement could not be saved. Please, try again.', true));
			}
		}
		$pages = $this->Helpelement->Page->find('list');
		$this->set(compact('pages'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid helpelement', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Helpelement->save($this->data)) {
				$this->Session->setFlash(__('The helpelement has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The helpelement could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Helpelement->read(null, $id);
		}
		$pages = $this->Helpelement->Page->find('list');
		$this->set(compact('pages'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for helpelement', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Helpelement->delete($id)) {
			$this->Session->setFlash(__('Helpelement deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Helpelement was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
