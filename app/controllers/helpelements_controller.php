<?php
class HelpelementsController extends AppController {

	var $name = 'Helpelements';

    var $uses = array('Helpelement', 'Helppage');



	function admin_add($helppage_id = null) {
		if (!empty($this->data)) {
            $this->Helppage->contain();
            $data = $this->Helppage->read(null, $this->data['Helpelement']['page_id']);
            $this->data['Helppage'] = $data['Helppage'];

			$this->Helpelement->create();
			if ($this->Helpelement->save($this->data)) {
				$this->Session->setFlash(__('The helpelement has been saved', true));
				$this->redirect(array('controller' => 'helppages', 'action' => 'index'));
			} else {
				$this->Session->setFlash(__('The helpelement could not be saved. Please, try again.', true));
			}
		}

        if($helppage_id == null){
            $this->Session->setFlash(__('No help page', true));
            $this->redirect($this->referer());
        }

	    //load helppage
        $this->Helppage->contain();
        $data = $this->Helppage->read(null, $helppage_id);

        if(!$data['Helppage']['id']){
            $this->Session->setFlash(__('Wrong help page', true));
            $this->redirect($this->referer());
        }
        $this->set('page_id', $helppage_id);
        $this->set('helppage', $data);

	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid helpelement', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {

            $this->Helppage->contain();
            $data = $this->Helppage->read(null, $this->data['Helpelement']['page_id']);
            $this->data['Helppage'] = $data['Helppage'];

			if ($this->Helpelement->save($this->data)) {
				$this->Session->setFlash(__('The helpelement has been saved', true));
				$this->redirect(array('controller' => 'helppages', 'action' => 'index'));
			} else {
				$this->Session->setFlash(__('The helpelement could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Helpelement->read(null, $id);
		}
	    //load helppage
        $this->Helppage->contain();
        $data = $this->Helppage->read(null, $this->data['Helppage']['id']);


        if(!$data['Helppage']['id']){
            $this->Session->setFlash(__('Wrong help page', true));
            $this->redirect($this->referer());
        }
        $this->set('page_id', $this->data['Helppage']['id']);
        $this->set('helppage', $data);
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for helpelement', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Helpelement->delete($id)) {
			$this->Session->setFlash(__('Helpelement deleted', true));
			$this->redirect(array('controller' => 'helppages', 'action'=>'index'));
		}
		$this->Session->setFlash(__('Helpelement was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
