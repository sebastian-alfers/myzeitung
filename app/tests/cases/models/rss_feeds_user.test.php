<?php
/* RssFeedsUser Test cases generated on: 2011-11-10 15:16:27 : 1320934587*/
App::import('Model', 'RssFeedsUser');

class RssFeedsUserTestCase extends CakeTestCase {
	var $fixtures = array('app.rss_feeds_user', 'app.user', 'app.route', 'app.post', 'app.topic', 'app.content_paper', 'app.paper', 'app.category', 'app.subscription', 'app.category_paper_post', 'app.comment', 'app.post_user', 'app.setting', 'app.rss_feed');

	function startTest() {
		$this->RssFeedsUser =& ClassRegistry::init('RssFeedsUser');
	}

	function endTest() {
		unset($this->RssFeedsUser);
		ClassRegistry::flush();
	}

}
