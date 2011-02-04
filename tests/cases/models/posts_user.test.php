<?php

Warning: date(): It is not safe to rely on the system's timezone settings. You are *required* to use the date.timezone setting or the date_default_timezone_set() function. In case you used any of those methods and you are still getting this warning, you most likely misspelled the timezone identifier. We selected 'Europe/Berlin' for 'CET/1.0/no DST' instead in /Applications/MAMP/htdocs/cakephp/cake/console/templates/default/classes/test.ctp on line 22
/* PostsUser Test cases generated on: 2011-02-04 22:16:36 : 1296854196*/
App::import('Model', 'PostsUser');

class PostsUserTestCase extends CakeTestCase {
	var $fixtures = array('app.posts_user', 'app.user', 'app.post', 'app.topic');

	function startTest() {
		$this->PostsUser =& ClassRegistry::init('PostsUser');
	}

	function endTest() {
		unset($this->PostsUser);
		ClassRegistry::flush();
	}

}
?>