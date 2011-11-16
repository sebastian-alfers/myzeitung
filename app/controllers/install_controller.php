<?php
/**
 * based on the install foler
 * - this folder contians namespaces (folder)
 * - each folder contains files containing (sql)commands to be run for that version
 * - a install file is named like:
 * -- [namespace]_[version].php
 *
 * e.g.
 * namespces:
 * - paper, user
 *
 * install files
 * - paper/paper_0.1.0.php
 * - paper/paper_0.1.1.php
 * - user/user_0.1.0.php
 * - user/user_0.1.1.php
 *
 *  versioning:
 *  - major version updates (e.g. from 0.2.7 to 1.0) should only be used
 *    for major changes
 *
 * Enter description here ...
 * @author sebastianalfers
 *
 */
class InstallController extends AppController {

    var $name = 'Install';

    var $components = array('Installer');

    var $uses = array();

	/**
	 * construct
	 */
	public function __construct(){
		parent::__construct();

	}
	/**
	 * performs install. does not place mysql commands if dryRun = true
	 * Enter description here ...
	 */
	function index(){
        $this->Installer->index();
	}


}
