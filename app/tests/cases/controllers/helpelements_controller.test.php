<?php
/* Helpelements Test cases generated on: 2011-09-13 13:53:09 : 1315914789*/
App::import('Controller', 'Helpelements');

class TestHelpelementsController extends HelpelementsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class HelpelementsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.helpelement', 'app.page', 'app.user', 'app.route', 'app.post', 'app.topic', 'app.content_paper', 'app.paper', 'app.category', 'app.subscription', 'app.category_paper_post', 'app.comment', 'app.post_user', 'app.setting', 'app.conversation_user', 'app.conversation', 'app.conversation_message');

	function startTest() {
		$this->Helpelements =& new TestHelpelementsController();
		$this->Helpelements->constructClasses();
	}

	function endTest() {
		unset($this->Helpelements);
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
