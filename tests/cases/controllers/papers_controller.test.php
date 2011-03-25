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

	function startTest() {
		$this->Papers =& new TestPapersController();
		$this->Papers->constructClasses();
	}

	function endTest() {
		unset($this->Papers);
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
?>