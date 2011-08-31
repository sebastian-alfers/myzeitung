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
        $topics=$this->Topic->find('list', array('conditions' => array('Topic.user_id' => $user_id)));
        $topics2['null'] = __('No Topic', true);
        $topics = $topics2 + $topics;

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


	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Topic-id', true));
			$this->redirect($this->referer());
		}
        $this->Topic->contain();
        $topic =  $this->Topic->read(array('id','user_id'), $id);
        $this->log($topic);
        if($topic['Topic']['user_id'] == $this->Session->read('Auth.User.id')){
            // second param = cascade -> delete associated records from hasmany , hasone relations
            if ($this->Topic->delete($id, true)) {
                $this->Session->setFlash(__('Topic deleted', true), 'default', array('class' => 'success'));
                $this->redirect(array('controller' => 'users',  'action' => 'view', 'username' => strtolower($this->Session->read('Auth.User.username'))));
            }
            $this->Session->setFlash(__('Topic was not deleted', true));
            $this->redirect($this->referer());
        } else {
            $this->Session->setFlash(__('The Topic does not belong to you.', true));
            $this->redirect($this->referer());
        }
	}
	


	function view_topic_name($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid topic', true));
			$this->redirect(array('action' => 'index'));
		}
        $this->Topic->contain('User');
		$topic = $this->Topic->read(null, $id);

        if($topic['Topic']['id'] && ($topic['User']['id'] == $this->Session->read('Auth.User.id'))){
            $this->set(JsonResponse::RESPONSE, $this->JsonResponse->success(array('topic_name' => $topic['Topic']['name'])));
        }
        else{
            $this->set(JsonResponse::RESPONSE, $this->JsonResponse->failure());
        }
        $this->render('view_topic');
	}

    /*
	function add() {
		if (!empty($this->data)) {
			$this->Topic->create();
			if ($this->Topic->save($this->data)) {
				$this->Session->setFlash(__('The topic has been saved', true));
				$this->redirect($this->referer());
			} else {
				$this->Session->setFlash(__('The topic could not be saved. Please, try again.', true));
			}
		}
		$this->set('user_id',$this->Auth->user('id'));
	}*/

	function edit($id = null) {


		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid topic', true));
			$this->redirect(array('action' => 'index'));
		}

		if (!empty($this->data)) {
            //read topic to check permission
            $this->Topic->contain();
            $topic =  $this->Topic->read(array('id','user_id'), $this->data['Topic']['id']);

            if($topic['Topic']['user_id'] != $this->Session->read('Auth.User.id')){
				$this->Session->setFlash(__('No Permission', true));
				$this->redirect($this->referer());
            }
            elseif($this->Topic->save($this->data)) {
				$this->Session->setFlash(__('The topic has been saved', true), 'default', array('class' => 'success'));
				$this->redirect($this->referer());
			} else {
				$this->Session->setFlash(__('The topic could not be saved. Please, try again.', true));
				$this->redirect($this->referer());
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Topic->read(null, $id);
		}
		$this->set('user_id',$this->Auth->user('id'));
	}


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