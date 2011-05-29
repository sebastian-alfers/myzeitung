<?php
/* Papers Test cases generated on: 2011-03-23 19:25:38 : 1300904738*/
App::import('Controller', 'Papers');

class TestPapersController extends PapersController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class PapersControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.paper', 'app.user', 'app.group', 'app.route', 'app.topic', 'app.post', 'app.comment', 'app.posts_user', 'app.category', 'app.subscription', 'app.category_paper_post');

	private $_user = null;
	
	function startTest() {
		$this->Papers =& new TestPapersController();
		$this->Papers->constructClasses();
		
		//create user
		App::import('model', 'User');
		$user = new User();
		$this->assertTrue($user->save($this->_getUserData()));
		$this->_user = $user;
	}

	function endTest() {
		debug('end test');
		//remove user
		$this->assertTrue($this->_user->delete());				
		
		unset($this->Papers);
		ClassRegistry::flush();
	}

	/**
	 * - createing a user
	 * - get a paper
	 * - associate whole user (all posts) to paper
	 * - find user in association of that post
	 * - associate whole user again to paper
	 * - create posts
	 * - find posts in paper
	 * - delete post
	 * - find posts in paper
	 * 
	 */
	function testAssociateWholeUserToPaper(){		
		$user_id = $this->_user->id;
		
		//get paper
		App::import('model', 'Paper');
		$paper = new Paper();
		$this->assertTrue($paper_data = $paper->read(null, 1));
		$paper_id = $paper_data['Paper']['id'];		
		
		
		//associate whole user to paper
		$this->assertTrue($this->Papers->newContentForPaper($paper_id, null, $user_id, null));

		//find user in associations of the post
		debug($paper->getTopicReferencesToOnlyThisPaper(2));

		
		
		//$this->assertTrue(true);
	}
	
	function testAdminIndex() {

	}

	function testAdminView() {

	}

	function testAdminAdd() {

	}

	function testAdminEdit() {

	}

	function testAdminDelete() {

	}
	
	private function _getUserData(){
		return array(
			'firstname' => 'dummy_user',
			'name' => 'user_dummy',
			'email' => 'dummy@user.de',
			'username' => 'dummy_user_name',
			'password' => '123456'
		);
	}	

}
?>