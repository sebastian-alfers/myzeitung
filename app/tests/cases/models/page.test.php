<?php
/* Page Test cases generated on: 2011-09-13 13:52:36 : 1315914756*/
App::import('Model', 'Page');

class PageTestCase extends CakeTestCase {
	var $fixtures = array('app.page', 'app.helpelement');

	function startTest() {
		$this->Page =& ClassRegistry::init('Page');
	}

	function endTest() {
		unset($this->Page);
		ClassRegistry::flush();
	}

}
