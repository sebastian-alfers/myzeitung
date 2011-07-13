<?php
/* Users Test cases generated on: 2011-07-13 12:59:27 : 1310554767*/
App::import('Controller', 'Users');

class TestUsersController extends UsersController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class UsersControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.user', 'app.group', 'app.route', 'app.post', 'app.topic', 'app.content_paper', 'app.paper', 'app.category', 'app.subscription', 'app.category_paper_post', 'app.comment', 'app.post_user', 'app.conversation_user', 'app.conversation', 'app.conversation_message');

	function startTest() {
		$this->Users =& new TestUsersController();
		$this->Users->constructClasses();
	}

	function endTest() {
		unset($this->Users);
		ClassRegistry::flush();
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

}
