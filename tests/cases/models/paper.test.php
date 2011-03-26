<?php
/* Paper Test cases generated on: 2011-03-23 20:45:11 : 1300909511*/
App::import('model', 'Paper');



class PaperTestCase extends CakeTestCase {
	var $fixtures = array('app.paper', 'app.user', 'app.group', 'app.route', 'app.topic', 'app.post', 'app.comment', 'app.posts_user', 'app.category', 'app.subscription', 'app.category_paper_post');
	
	//var $import = array('model' => 'User', 'records' => true);
	
	
	function startTest() {
		$this->Paper =& ClassRegistry::init('Paper');
	}

	function endTest() {
		unset($this->Paper);
		ClassRegistry::flush();
	}

	function testGetContentReference() {
		
	}

	function testGetTopicReferencesToOnlyThisPaper() {
		
	}

	function testGetTopicReferencesToOnlyThisCategory() {
		
	}

	/**
	 * testing a subscription
	 * 1) create user
	 * 2) get paper
	 * 3) subscribe paper to user
	 * 4) double subscribe to user
	 * 5) remove paper
	 * 6) remove subscription
	 */
	function testSubscribe() {
		// create
		App::import('model', 'User');
		$user = new User();
		$this->assertTrue($user->save($this->_getUserData()));
		$user_id = $user->id;
		
		//get paper
		$this->assertTrue($paper_data = $this->Paper->read(null, 1));
		$paper_id = $paper_data['Paper']['id'];
		
		//subscribe to paper
		$this->assertTrue($this->Paper->subscribe($paper_id, $user_id));
		
		//double subscribe to paper
		$this->assertFalse($this->Paper->subscribe($paper_id, $user_id));
		
		//remove user
		$this->assertTrue($user->delete($user_id));
		
		//unsubscribe
		$this->assertTrue($this->Paper->unSubscribe($paper_id, $user_id));
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