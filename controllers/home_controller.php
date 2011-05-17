<?php
class HomeController extends AppController {



	var $name = 'Home';
	var $components = array('ContentPaperHelper', 'RequestHandler', 'JqImgcrop', 'Upload');
	var $uses = array('Paper', 'User', 'Post');
	var $helpers = array('Time', 'Image', 'Js' => array('Jquery'));


	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('index');

	}

	public function index(){
		
		//loading papers
		$this->Paper->contain();
		$paper_options = array('limit' => 9, 'order' => array('created DESC'));
		$this->set('papers', $this->Paper->find('all', $paper_options));
		//loading users
		$this->User->contain();
		$user_options = array('limit' => 9, 'order' => array('created DESC'));
		$this->set('users', $this->User->find('all', $user_options));
		//loading users
		$this->Post->contain('User.id', 'User.username', 'User.name');
		$post_options = array('limit' => 6, 'order' => array('created DESC'), 'fields' => array('id', ));
		$this->set('posts', $this->Post->find('all', $post_options));
	
		
	}
	
}