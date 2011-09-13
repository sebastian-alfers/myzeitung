<?php
/* Helppages Test cases generated on: 2011-09-13 13:58:48 : 1315915128*/
App::import('Controller', 'Helppages');

class TestHelppagesController extends HelppagesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class HelppagesControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.helppage', 'app.helpelement', 'app.user', 'app.route', 'app.post', 'app.topic', 'app.content_paper', 'app.paper', 'app.category', 'app.subscription', 'app.category_paper_post', 'app.comment', 'app.post_user', 'app.setting', 'app.conversation_user', 'app.conversation', 'app.conversation_message');

	function startTest() {
		$this->Helppages =& new TestHelppagesController();
		$this->Helppages->constructClasses();
	}

	function endTest() {
		unset($this->Helppages);
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
