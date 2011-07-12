<?php
/* Reason Test cases generated on: 2011-07-12 10:43:41 : 1310460221*/
App::import('Model', 'Reason');

class ReasonTestCase extends CakeTestCase {
	var $fixtures = array('app.reason', 'app.complaint', 'app.paper', 'app.user', 'app.group', 'app.route', 'app.post', 'app.topic', 'app.content_paper', 'app.category', 'app.category_paper_post', 'app.post_user', 'app.comment', 'app.subscription', 'app.complaintstatus');

	function startTest() {
		$this->Reason =& ClassRegistry::init('Reason');
	}

	function endTest() {
		unset($this->Reason);
		ClassRegistry::flush();
	}

}
