<?php

Warning: date(): It is not safe to rely on the system's timezone settings. You are *required* to use the date.timezone setting or the date_default_timezone_set() function. In case you used any of those methods and you are still getting this warning, you most likely misspelled the timezone identifier. We selected 'Europe/Berlin' for 'CET/1.0/no DST' instead in /Applications/MAMP/htdocs/cakephp/cake/console/templates/default/classes/test.ctp on line 22
/* Topic Test cases generated on: 2011-02-04 22:04:28 : 1296853468*/
App::import('Model', 'Topic');

class TopicTestCase extends CakeTestCase {
	var $fixtures = array('app.topic', 'app.user', 'app.post', 'app.posts_user');

	function startTest() {
		$this->Topic =& ClassRegistry::init('Topic');
	}

	function endTest() {
		unset($this->Topic);
		ClassRegistry::flush();
	}

}
?>