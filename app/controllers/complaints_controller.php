<?php
class ComplaintsController extends AppController {

	var $name = 'Complaints';

    var $uses = array('Complaint', 'JsonResponse');

    const DATE_FORMAT = 'Y-m-d H:i:s';

	var $helpers = array('Form', 'Time');

	public function beforeFilter(){
		parent::beforeFilter();
		//declaration which actions can be accessed without being logged in
		$this->Auth->allow('add');
	}

	function add() {
        $ajx = true;

		if (!empty($this->data)) {

            $type = $this->data['Complaint']['type'];
            $id = $this->data['Complaint']['type_id'];
            $this->data['Complaint'][$type.'_id'] = $id;

            $this->data['Complaint']['comments'] = serialize(array(array('date' => date(self::DATE_FORMAT), 'comment' => $this->data['Complaint']['comments'])));

			$this->Complaint->create();
			if ($this->Complaint->save($this->data)) {

                $this->Session->setFlash(__('Thank you for you help! Your complaint will be processed.', true), 'default', array('class' => 'success'));
                $this->redirect($this->referer());

			} else {

			}
		}
        $user_id = null;
        if($this->Auth->user('id')){
            $user_id = $this->Auth->user('id');
            $user_name = $this->Auth->user('username');
            $user_email = $this->Auth->user('email');

            if($this->Auth->user('name')){
                $user_name .= " (".$this->Auth->user('name').")";
            }

            $this->set(compact('user_name', 'user_email'));
        }

		$reasons = $this->Complaint->Reason->find('list');
		$this->set(compact('reasons', 'user_id'));

        if(isset($this->params['form']['type'])){
            $this->set('type', $this->params['form']['type']);
        }
        if(isset($this->params['form']['id'])){
            $this->set('id', $this->params['form']['id']);
        }


        if($ajx)
            $this->render('add', 'ajax');
	}

	function admin_index() {
		$this->Complaint->recursive = 0;
		$this->set('complaints', $this->paginate());
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid complaint', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('complaint', $this->Complaint->read(null, $id));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid complaint', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
            $new_comment = $this->data['Complaint']['new_comment'];
            //load old comments
            if(isset($this->data['Complaint']['id'])){
                 $this->Complaint->contain();
                 $data = $this->Complaint->read('comments', $this->data['Complaint']['id']);
                 $old_comments = unserialize($data['Complaint']['comments']);

                 $old_comments[] = array('comment_author' => $this->Session->read('Auth.User.username'), 'date' => date(self::DATE_FORMAT), 'comment' => $new_comment);

                 $this->data['Complaint']['comments'] = serialize($old_comments);
             }
			if ($this->Complaint->save($this->data)) {
				$this->Session->setFlash(__('The complaint has been saved', true), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The complaint could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Complaint->read(null, $id);
		}
		$papers = $this->Complaint->Paper->find('list');
		$posts = $this->Complaint->Post->find('list');
		$comments = $this->Complaint->Comment->find('list');
		$users = $this->Complaint->User->find('list');
		$reasons = $this->Complaint->Reason->find('list');
		$reporters = $this->Complaint->Reporter->find('list');
		$complaintstatuses = $this->Complaint->Complaintstatus->find('list');
		$this->set(compact('papers', 'posts', 'comments', 'users', 'reasons', 'reporters', 'complaintstatuses'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for complaint', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Complaint->delete($id)) {
			$this->Session->setFlash(__('Complaint deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Complaint was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
