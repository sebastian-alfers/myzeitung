<?php
/* Subscription Test cases generated on: 2011-02-12 17:48:51 : 1297529331*/
App::import('Model', 'Subscription');

class SubscriptionTestCase extends CakeTestCase {
	var $fixtures = array('app.subscription', 'app.paper', 'app.route', 'app.category', 'app.user', 'app.group', 'app.topic', 'app.post', 'app.posts_user');

	function startTest() {
		$this->Subscription =& ClassRegistry::init('Subscription');
	}

	function endTest() {
		unset($this->Subscription);
		ClassRegistry::flush();
	}

}
?>