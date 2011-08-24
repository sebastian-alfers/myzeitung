<?php
/* Settings Test cases generated on: 2011-08-24 10:10:54 : 1314173454*/
App::import('Controller', 'Settings');

class TestSettingsController extends SettingsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class SettingsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.setting', 'app.user', 'app.route', 'app.post', 'app.topic', 'app.content_paper', 'app.paper', 'app.category', 'app.subscription', 'app.category_paper_post', 'app.comment', 'app.post_user', 'app.conversation_user', 'app.conversation', 'app.conversation_message');

	function startTest() {
		$this->Settings =& new TestSettingsController();
		$this->Settings->constructClasses();
	}

	function endTest() {
		unset($this->Settings);
		ClassRegistry::flush();
	}

	function testIndex() {

	}

	function testView() {

	}

	function testAdd() {

	}

	function testEdit() {

	}

	function testDelete() {

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
