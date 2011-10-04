<?php
class HomeController extends AppController {


	var $name = 'Home';
	var $components = array('ContentPaperHelper', 'RequestHandler', 'JqImgcrop', 'Upload');
	var $uses = array('Paper', 'User', 'CategoryPaperPost');
	var $helpers = array('MzTime', 'Image', 'Js' => array('Jquery'), 'Cache');

    //callback-param is important!
    var $cacheAction = array(
        'index'  => array('callbacks' => true, 'duration' => '+1 month')
    );


	public function beforeFilter(){

		parent::beforeFilter();



		$this->Auth->allow('index');

	}

	public function index(){
		if($this->Session->read('Auth.User.id')){
			$this->redirect(array('controller' => 'users', 'action' => 'viewSubscriptions', 'username' => strtolower($this->Session->read('Auth.User.username')),'own_paper' => Paper::FILTER_OWN));
		}
		
		//loading papers
		$this->Paper->contain('Route');
		$paper_options = array('conditions' => array('Paper.enabled' => true, 'Paper.visible_home' => true), 'limit' => 9, 'order' => array('created DESC'), 'fields' => array('id', 'image', 'title', 'description'));
		$this->set('papers', $this->Paper->find('all', $paper_options));
		//loading users
		$this->User->contain();
		$user_options = array('conditions' => array('User.enabled' => true, 'User.visible_home' => true),'limit' => 12, 'order' => array('created DESC'), 'fields' => array('id', 'image', 'username', 'name', 'description'));
		$this->set('users', $this->User->find('all', $user_options));
		//loading users
		//$this->Post->contain('Route', 'User.id', 'User.username', 'User.name', 'User.image', 'Paper');
        $this->CategoryPaperPost->contain('Post.Route', 'Post.User', 'Paper');



		//$post_options = array('conditions' => array('Post.enabled' => true, 'Post.Paper.visible_home' => true),'limit' => 7, 'order' => array('Post.created DESC'), 'fields' => array('id','title'));
        $post_options = array('conditions' => array('Post.enabled' => true, 'Paper.visible_home' => true),'limit' => 7, 'order' => array('Post.created DESC'), 'group' => array('CategoryPaperPost.post_id'));

                                                                                    //$count = $this->CategoryPaperPost->find('count', array('conditions' => $conditions, 'fields' => 'distinct CategoryPaperPost.post_id'));
        //debug($this->CategoryPaperPost->find('all', $post_options));
        //die();
		$this->set('posts', $this->CategoryPaperPost->find('all', $post_options));
		
		$this->layout = 'home';
	}
	
}