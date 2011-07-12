<?php
/* Complaintstatus Test cases generated on: 2011-07-12 10:43:00 : 1310460180*/
App::import('Model', 'Complaintstatus');

class ComplaintstatusTestCase extends CakeTestCase {
	var $fixtures = array('app.complaintstatus', 'app.complaint', 'app.paper', 'app.user', 'app.group', 'app.route', 'app.post', 'app.topic', 'app.content_paper', 'app.category', 'app.category_paper_post', 'app.post_user', 'app.comment', 'app.subscription', 'app.reason');

	function startTest() {
		$this->Complaintstatus =& ClassRegistry::init('Complaintstatus');
	}

	function endTest() {
		unset($this->Complaintstatus);
		ClassRegistry::flush();
	}

}
