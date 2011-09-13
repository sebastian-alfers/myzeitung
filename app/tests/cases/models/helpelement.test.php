<?php
/* Helpelement Test cases generated on: 2011-09-13 13:52:01 : 1315914721*/
App::import('Model', 'Helpelement');

class HelpelementTestCase extends CakeTestCase {
	var $fixtures = array('app.helpelement', 'app.page');

	function startTest() {
		$this->Helpelement =& ClassRegistry::init('Helpelement');
	}

	function endTest() {
		unset($this->Helpelement);
		ClassRegistry::flush();
	}

}
