<?php

Warning: date(): It is not safe to rely on the system's timezone settings. You are *required* to use the date.timezone setting or the date_default_timezone_set() function. In case you used any of those methods and you are still getting this warning, you most likely misspelled the timezone identifier. We selected 'Europe/Berlin' for 'CET/1.0/no DST' instead in /Applications/MAMP/htdocs/cakephp/cake/console/templates/default/classes/test.ctp on line 22
/* Topics Test cases generated on: 2011-02-04 22:11:30 : 1296853890*/
App::import('Controller', 'Topics');

class TestTopicsController extends TopicsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class TopicsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.topic', 'app.user', 'app.post', 'app.posts_user');

	function startTest() {
		$this->Topics =& new TestTopicsController();
		$this->Topics->constructClasses();
	}

	function endTest() {
		unset($this->Topics);
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

}
?>