<?php
class HelppagesController extends AppController {

	var $name = 'Helppages';

    var $controllers = array('posts' => 'posts',
                             'papers' => 'papers',
                             'users' => 'users',
                             'conversations' => 'conversations');

    var $actions = array('add' => 'add',
                         'edit' => 'edit',
                         'index' => 'index',
                         'view' => 'view',
                         'viewSubscriptions/own' => 'viewSubscriptions/own',
                         'viewSubscriptions/subscriptions' => 'viewSubscriptions/subscriptions',
                         'accAboutMe' => 'accAboutMe',
                         'accPrivacy' => 'accPrivacy',
                         'accSocial' => 'accSocial');

    function beforeRender(){
        parent::beforeRender();

        $this->set('controllers', $this->controllers);
        $this->set('actions', $this->actions);
    }

	function admin_index() {

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
            $this->Helppage->contain('Helpelement');
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

		if ($this->Helppage->delete($id, true)) {
			$this->Session->setFlash(__('Helppage deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Helppage was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
