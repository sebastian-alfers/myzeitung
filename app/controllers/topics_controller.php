<?php
class TopicsController extends AppController {

	var $name = 'Topics';
	var $components = array('Auth');
	
	var $autoRender=false;
	
	/**
	 * gets a new name for topcip and returns id
	 * 
	 * @param String $topic name
	 */
	function ajax_add($topic_name){
		
		if (!empty($topic_name)) {
			$this->data['Topic']['name'] = $topic_name;
			$this->data['Topic']['user_id'] = $this->Session->read('Auth.User.id');
			$this->Topic->create();
			error_log(json_encode($this->data));
			if ($this->Topic->save($this->data)) {
                //update topic_count cache in session
                $this->Session->write("Auth.User.topic_count", $this->Session->read("Auth.User.topic_count") + 1);

				
				echo $this->Topic->id;
			} else {
				
			}
		}
		$this->render('ajax_add', 'ajax');//custom ctp, ajax for blank layout		 
	}		
	

//
//	function view($id = null) {
//		if (!$id) {
//			$this->Session->setFlash(__('Invalid topic', true));
//			$this->redirect(array('action' => 'index'));
//		}
//		$this->set('topic', $this->Topic->read(null, $id));
//	}
//
//	function add() {
//		if (!empty($this->data)) {
//			$this->Topic->create();
//			if ($this->Topic->save($this->data)) {
//				$this->Session->setFlash(__('The topic has been saved', true));
//				$this->redirect($this->referer());
//			} else {
//				$this->Session->setFlash(__('The topic could not be saved. Please, try again.', true));
//			}
//		}
//		$this->set('user_id',$this->Auth->user('id'));
//	}
//
//	function edit($id = null) {
//		if (!$id && empty($this->data)) {
//			$this->Session->setFlash(__('Invalid topic', true));
//			$this->redirect(array('action' => 'index'));
//		}
//		if (!empty($this->data)) {
//			if ($this->Topic->save($this->data)) {
//				$this->Session->setFlash(__('The topic has been saved', true));
//				$this->redirect(array('action' => 'index'));
//			} else {
//				$this->Session->setFlash(__('The topic could not be saved. Please, try again.', true));
//			}
//		}
//		if (empty($this->data)) {
//			$this->data = $this->Topic->read(null, $id);
//		}
//		$this->set('user_id',$this->Auth->user('id'));
//	}
//
//	function delete($id = null) {
//		if (!$id) {
//			$this->Session->setFlash(__('Invalid id for topic', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		if ($this->Topic->delete($id, true)) {
//			$this->Session->setFlash(__('Topic deleted', true));
//			$this->redirect(array('action'=>'index'));
//		}
//		$this->Session->setFlash(__('Topic was not deleted', true));
//		$this->redirect(array('action' => 'index'));
//	}
	

}
?>