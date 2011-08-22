<?php
/* Setting Test cases generated on: 2011-08-15 13:12:39 : 1313406759*/
App::import('Model', 'Setting');

class SettingTestCase extends CakeTestCase {
	var $fixtures = array('app.setting');

	function startTest() {
		$this->Setting =& ClassRegistry::init('Setting');
	}

	function endTest() {
		unset($this->Setting);
		ClassRegistry::flush();
	}

}
