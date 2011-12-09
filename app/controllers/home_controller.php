<?php
class HomeController extends AppController {


	var $name = 'Home';
	var $components = array('Security' ,'ContentPaperHelper', 'RequestHandler', 'JqImgcrop', 'Upload');
	var $uses = array('Paper', 'User', 'CategoryPaperPost');
	var $helpers = array('MzTime', 'Image', 'Js' => array('Jquery'), 'Cache');

    //callback-param is important!
    var $cacheAction = array(
        #'index'  => array('callbacks' => true, 'duration' => '+1 month')
    );


	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('index');
	}

	public function index(){
		if($this->Session->read('Auth.User.id')){
			$this->redirect(array('controller' => 'users', 'action' => 'view', 'username' => strtolower($this->Session->read('Auth.User.username'))));
		}
		
		//loading papers
        $papers = Cache::read('home_papers');
        if($papers === false){
            $this->Paper->contain('Route');
            $paper_options = array('conditions' => array('Paper.enabled' => true, 'Paper.visible_home' => true), 'limit' => 9, 'order' => 'rand()', 'fields' => array('id', 'image', 'title', 'description'));
            $papers = $this->Paper->find('all', $paper_options);
            Cache::write('home_papers', $papers);
        }
        $this->set('papers', $papers);


		//loading users
        $users = Cache::read('home_users');
        if($users === false){
            $this->User->contain();
            $user_options = array('conditions' => array('User.enabled' => true, 'User.visible_home' => true),'limit' => 12, 'order' => 'rand()', 'fields' => array('id', 'image', 'username', 'name', 'description'));
            $users = $this->User->find('all', $user_options);



            Cache::write('home_users', $users);
        }
        $this->set('users', $users);

		//loading posts
        $posts = Cache::read('home_posts');
        if($posts === false){
            $this->CategoryPaperPost->contain('Post.Route', 'Post.User', 'Paper');
            $post_options = array('conditions' => array('Post.enabled' => true, 'Paper.visible_home' => true),'limit' => 6, 'order' => array('Post.created DESC'), 'group' => array('CategoryPaperPost.post_id'));
            $posts = $this->CategoryPaperPost->find('all', $post_options);
            Cache::write('home_posts', $posts);
        }
		$this->set('posts', $posts);
		
		$this->layout = 'home';

	}
	
}