<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.app
 */
class AppController extends Controller {
	public function beforeFilter(){
		if(isset($this->Auth)) {
			// this makes Auth use the isAuthorized() method below
			$this->Auth->authorize = 'controller';
			// only enabled users are able to log in
			$this->Auth->userScope = array('User.enabled' => 1);
		}
	}
	
	
	function isAuthorized() {
		// defining the authorized usergroups for every action of every controller 
		$allowedActions = array(
		'users' => array(
			'index' => array('admin'),
			'add' => array('admin','scherge'),
			'edit' => array('admin'),
			'view' => array('admin'),
			'delete' => array('admin')
			
			),
		'topics' => array(
			'index' => array('admin'),
			'add' => array('admin'),
			'edit' => array('admin'),
			'view' => array('admin','scherge'),
			'delete' => array('admin')
			),
		'posts' => array(
			'index' => array('admin'),
			'add' => array('admin'),
			'edit' => array('admin'),
			'view' => array('admin', 'scherge'),
			'delete' => array('admin')
			),	
		);
		
		// check if the specific controller and action is set in the allowedAction array and if the group of the specific user is allowed to use it
		
		if(isset($allowedActions[low($this->name)])) {
			$controllerActions = $allowedActions[low($this->name)];
			if(isset($controllerActions[$this->action]) && 
			in_array($this->Auth->user('group'), $controllerActions[$this->action])) {
				return true;
			}
		}
		return false;
	}
}
