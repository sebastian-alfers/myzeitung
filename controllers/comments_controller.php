<?php
class CommentsController extends AppController {

	var $name = 'Comments';
	
	 	public function beforeFilter(){
 		parent::beforeFilter();
 		//declaration which actions can be accessed without being logged in
 		$this->Auth->allow('show');
 	}
 	

	function show($post_id = null) {
		$this->Comment->contain('User.username','User.id');
		//'threaded' gets also the replies (children) and children's children etc. (for tree behavior. not sure if for not-tree also)
		$comments = $this->Comment->find('threaded',array(
										'conditions' => array('post_id' => $post_id),
										'order'=>array('created DESC'), 
										'fields' => array('id','user_id','post_id','parent_id','text','created')));
		$this->set('comments',$comments);
		$this->set('post_id',$post_id);
	}



	function add($post_id = null, $parent_id = null) {
		if (!empty($this->data)) {
			$this->Comment->create();	
			if ($this->Comment->save($this->data)) {
				$this->Session->setFlash(__('The comment has been saved', true));
				$this->redirect(array('controller' => 'comments', 'action' => 'show', $this->data['Comment']['post_id']));
			} else {
				$this->Session->setFlash(__('The comment could not be saved. Please, try again.', true));
			}
		}
		$this->set('post_id', $post_id);
		$this->set('parent_id', $parent_id);
		$this->set('user_id', $this->Auth->user('id'));
	}

}
?>