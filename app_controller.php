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
	//load debug toolbar in plugins/debug_toolbar/
	var $components = array('DebugKit.Toolbar', 'Auth', 'Session');
	
	public function beforeFilter(){
		if(isset($this->Auth)) {
			// this makes the Auth-component use the isAuthorized()-method below
			$this->Auth->authorize = 'controller';
			// only enabled users are able to log in
			$this->Auth->userScope = array('User.enabled' => 1);
		}
		echo "go hier";
		//set globl function for auth?true:false
		$this->set('isLoggedIn', $this->Session->check('Auth.User.id'));
	}
	
	
	function isAuthorized() {
		// defining the authorized usergroups for every action of every controller 
		// 1 = admin 2=scherge
		$allowedActions = array(
		'users' => array(
			'index' => array(1),
			'add' => array(1,2),
			'edit' => array(1),
			'view' => array(1),
			'delete' => array(1)
			),
		'topics' => array(
			'index' => array(1),
			'add' => array(1),
			'edit' => array(1),
			'view' => array(1,2),
			'delete' => array(1)
			),
		'posts' => array(
			'index' => array(1),
			'add' => array(1),
			'edit' => array(1),
			'view' => array(1,2),
			'delete' => array(1)
			),	
		);
		
		// check if the specific controller and action is set in the allowedAction array and if the group of the specific user is allowed to use it
			if(isset($allowedActions[low($this->name)])) {
			$controllerActions = $allowedActions[low($this->name)];
			if(isset($controllerActions[$this->action]) && 
			in_array($this->Auth->user('group_id'), $controllerActions[$this->action])) {
				return true;
			}
		}
		return false;
	}
}
