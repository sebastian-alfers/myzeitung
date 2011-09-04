<?php
class HomeController extends AppController {



	var $name = 'Home';
	var $components = array('ContentPaperHelper', 'RequestHandler', 'JqImgcrop', 'Upload');
	var $uses = array('Paper', 'User', 'Post');
	var $helpers = array('MzTime', 'Image', 'Js' => array('Jquery'));


	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('index');

	}

	public function index(){
		
		if($this->Session->read('Auth.User.id')){
			$this->redirect(array('controller' => 'users', 'action' => 'view', 'username' => strtolower($this->Session->read('Auth.User.username'))));
		}
		
		
		//loading papers
		$this->Paper->contain('Route');
		$paper_options = array('conditions' => array('Paper.enabled' => true), 'limit' => 9, 'order' => array('created DESC'), 'fields' => array('id', 'image', 'title', 'description'));
		$this->set('papers', $this->Paper->find('all', $paper_options));
		//loading users
		$this->User->contain();
		$user_options = array('conditions' => array('User.enabled' => true),'limit' => 12, 'order' => array('created DESC'), 'fields' => array('id', 'image', 'username', 'name', 'description'));
		$this->set('users', $this->User->find('all', $user_options));
		//loading users
		$this->Post->contain('Route', 'User.id', 'User.username', 'User.name', 'User.image');
		$post_options = array('conditions' => array('Post.enabled' => true),'limit' => 7, 'order' => array('Post.created DESC'), 'fields' => array('id','title'));
		$this->set('posts', $this->Post->find('all', $post_options));
		
		$this->layout = 'home';
	}
	
}