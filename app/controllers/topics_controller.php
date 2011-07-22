<?php
class TopicsController extends AppController {

	var $name = 'Topics';
	var $components = array('Auth');

    var $helpers = array('Form');

    var $uses = array('Topic', 'JsonResponse');
	
	var $autoRender=false;
	
	/**
     * json method
     *
	 * gets a new name for topcip and returns id
	 * 
	 * @param String $topic name
	 */
	function ajax_add(){
		if (!empty($this->params['form']['topic_name'])) {
			$this->data['Topic']['name'] = $this->params['form']['topic_name'];

			$this->data['Topic']['user_id'] = $this->Session->read('Auth.User.id');
			$this->Topic->create();

			if ($this->Topic->save($this->data)) {
                //update topic_count cache in session
                $this->Session->write("Auth.User.topic_count", $this->Session->read("Auth.User.topic_count") + 1);

				//response for ajax request ->

                $this->set(JsonResponse::RESPONSE, $this->JsonResponse->success($this->Topic->id));
			} else {

                $this->log('error while saving topic ');
                $this->set(JsonResponse::RESPONSE, $this->JsonResponse->failure());
			}
		}
        else{
            $this->log('topics / ajax_add : wrong params');
            $this->set(JsonResponse::RESPONSE, $this->JsonResponse->failure());
        }


		$this->render('add');//custom ctp, ajax for blank layout
	}

    /**
     * json controller
     * return topics of logged in user
     *
     * @return void
     */
    function getTopics(){



        $user_id = $this->Session->read('Auth.User.id');

        $topics = array();
        App::import('controller', 'PostsController');
        $topics['null'] = __('No Topic', true);
        $topics = array_merge($topics, $this->Topic->find('list', array('conditions' => array('Topic.user_id' => $user_id))));

        if(count($topics) > 0){
            if(isset($this->params['form']['post_id']) && !empty($this->params['form']['post_id'])){

                $this->set('post_id', $this->params['form']['post_id']);
            }
            $this->set(JsonResponse::RESPONSE, $this->JsonResponse->success($topics));
        }
        else{
            $this->set(JsonResponse::RESPONSE, $this->JsonResponse->failure());
        }

        $this->render('view');

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