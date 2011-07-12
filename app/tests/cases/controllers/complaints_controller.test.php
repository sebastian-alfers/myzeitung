<?php
/* Complaints Test cases generated on: 2011-07-12 10:46:20 : 1310460380*/
App::import('Controller', 'Complaints');

class TestComplaintsController extends ComplaintsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class ComplaintsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.complaint', 'app.paper', 'app.user', 'app.group', 'app.route', 'app.post', 'app.topic', 'app.content_paper', 'app.category', 'app.category_paper_post', 'app.post_user', 'app.comment', 'app.subscription', 'app.reason', 'app.complaintstatus', 'app.conversation_user', 'app.conversation', 'app.conversation_message');

	function startTest() {
		$this->Complaints =& new TestComplaintsController();
		$this->Complaints->constructClasses();
	}

	function endTest() {
		unset($this->Complaints);
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
