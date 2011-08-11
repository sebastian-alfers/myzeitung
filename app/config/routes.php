<?php
/**
 * Routes Configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
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
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/views/pages/home.ctp)...
 */
Router::connect('/', array('controller' => 'home', 'action' => 'index'));

/**
 * TEST ALTER
 */

//Router::connect('/a/:username/:title-:id', array('controller' => 'posts', 'action' => 'view'),array('pass' => array('id'),'title' => '[^-]+'));


/**
 * register action
 */

Router::connect('/register', array('controller' => 'users', 'action' => 'add'));

/**
 * login
 */

Router::connect('/login', array('controller' => 'users', 'action' => 'login'));

/**
 * logout
 */

Router::connect('/logout', array('controller' => 'users', 'action' => 'logout'));

/**
 * user account settings
 */

Router::connect('/settings', array('controller' => 'users', 'action' => 'accAboutMe'));
Router::connect('/settings/privacy', array('controller' => 'users', 'action' => 'accPrivacy'));
Router::connect('/settings/general', array('controller' => 'users', 'action' => 'accGeneral'));

/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
//Router::connect('/(.*).htm', array('controller' => 'pages', 'action' => 'display'));

/*
$menus = '';
$cache = ClassRegistry::init('cache');


$cache->delete('routes');
$menusModel = ClassRegistry::init('Route');
if($cache->read('routes')){
	$menus = $cache->read('routes');
}
else{
	$menus = $menusModel->find('all');
	$cache->write('routes', $menus);
}


foreach($menus as $menuitem){
	
	//check for deprecated url -> send 302 + redir to parent url
	if($menuitem['Route']['source'] == $_REQUEST['url'] && $menuitem['Route']['parent_id'] != 0){
		//load parent_route
		$parentRoute = $menusModel->findById($menuitem['Route']['parent_id']);
		header ('HTTP/1.1 301 Moved Permanently');
		header ('Location: '. $parentRoute['Route']['source']);			  		
	}	
	
	if($menuitem['Route']['parent_id'] != 0){
		//load parent_route
		$parentRoute = $menusModel->findById($menuitem['Route']['parent_id']);

  	    $menuitem['Route']['target_controller'] = $parentRoute['Route']['target_controller'];
  	    $menuitem['Route']['target_action'] = $parentRoute['Route']['target_action'];
  	    $menuitem['Route']['target_param'] = $parentRoute['Route']['target_param'];  	    
  	}	
	
    Router::connect('/' . $menuitem['Route']['source'],
			  array('controller' => $menuitem['Route']['target_controller'], 
			  		'action' 	 => $menuitem['Route']['target_action'] , 
			  		$menuitem['Route']['target_param'] ));
			  		


			  		
		//	  		echo '/'.$menuitem['Route']['source'] .' <br />';
}
//Router::connect('/', array('controller' => 'homepage', 'action' =>'index'));
 
 *
 */


Router::parseExtensions('json');