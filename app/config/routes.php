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



$prefix = substr($_SERVER['REQUEST_URI'],0,3);
if(in_array($prefix,array('/p/','/a/'))){
    $temp_url = $_SERVER['REQUEST_URI'];
    $temp_url_extra = '';
    //filter pagination params to find the correct routing in cache or database
    // '/p/username/paper_title/page:2/sort:asc ....' ---> '/p/username/paper_title'
    if(substr_count($_SERVER['REQUEST_URI'], '/') > 3){
        $pos = strpos($_SERVER['REQUEST_URI'], '/', 4);
        $pos = strpos($_SERVER['REQUEST_URI'], '/', $pos+1);
        $temp_url = substr($_SERVER['REQUEST_URI'], 0, $pos);
        //save extra parameters in case we have to 301 redirect -> so we can forward the params
        $temp_url_extra = substr($_SERVER['REQUEST_URI'], $pos);
       
    }

    $route = Cache::read('url_'.$temp_url);

    App::import('Model', 'Route');
    $this->Route = new Route();
    if(empty($route)) {

        $this->Route->contain('ParentRoute');
        $route = $this->Route->find('first', array(
                'conditions' => array('Route.source' => $temp_url)));
        if(!empty($route['Route']['source'])){
            Cache::write('url_'.$temp_url, $route);
        }
    }
    
    //redirect to current location and add potential extra (pagination params)
    if($route['Route']['parent_id'] != null){
            header ('HTTP/1.1 301 Moved Permanently');
            header ('Location: '.$route['ParentRoute']['source'].$temp_url_extra);
    }

    if(!empty($route)){
        //Router::connect($_SERVER['REQUEST_URI'].'/*',array('controller' => $route['Route']['target_controller'] , 'action' => $route['Route']['target_action'], $route['Route']['target_param']));
        if($route['Route']['ref_type'] == Route::TYPE_PAPER){
            Router::connect('/p/:username/:slug/:category_id/*',array('controller' => 'papers' , 'action' => 'view',$route['Route']['target_param']),array('username' => '[a-z0-9]+[^/]', 'slug' => '[^/]+', 'category_id' => '[0-9]+') );
            Router::connect('/p/:username/:slug/*',array('controller' => 'papers' , 'action' => 'view',$route['Route']['target_param']),array('username' => '[a-z0-9]+[^/]', 'slug' => '[^/]+') );
        }
        if($route['Route']['ref_type'] == Route::TYPE_POST){
            //if at some point we use a pagination system WITHIN an article , we need to add '/*' at the end of the url
            Router::connect('/a/:username/:slug/*',array('controller' => 'posts' , 'action' => 'view',$route['Route']['target_param']),array('username' => '[a-z0-9]+[^/]', 'slug' => '[^/]+') );
        }
    }

}


Router::connect('/', array('controller' => 'home', 'action' => 'index'));

/**
 * user profile etc
 */

Router::connect('/u/:username/papers/*', array('controller' => 'users','action' =>'viewSubscriptions'),array('pass' => array('username'), 'username' => '[a-z0-9]+[^/]'));
Router::connect('/u/:username/:id', array('controller' => 'users', 'action' => 'view'),array('pass' => array('username','id'),'username' => '[a-z0-9]+[^/]','id' => '[0-9]+'));
Router::connect('/u/:username/*', array('controller' => 'users', 'action' => 'view'),array('pass' => array('username'), 'username' => '[a-z0-9]+[^/]'));

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
Router::connect('/settings/social-media', array('controller' => 'users', 'action' => 'accSocial'));


/**
 * index actions
 */

Router::connect('/authors/*', array('controller' => 'users', 'action' => 'index'));
Router::connect('/articles/*', array('controller' => 'posts', 'action' => 'index'));
Router::connect('/papers/*', array('controller' => 'papers', 'action' => 'index'));
Router::connect('/paper/add', array('controller' => 'papers', 'action' => 'add'));
Router::connect('/paper/subscribe/*', array('controller' => 'papers', 'action' => 'subscribe'));
Router::connect('/paper/unsubscribe/*', array('controller' => 'papers', 'action' => 'unsubscribe'));
Router::connect('/paper/edit/*', array('controller' => 'papers', 'action' => 'edit'));
Router::connect('/paper/saveImage/*', array('controller' => 'papers', 'action' => 'saveImage'));
Router::connect('/paper/references/*', array('controller' => 'papers', 'action' => 'references'));
Router::connect('/article/add', array('controller' => 'papers', 'action' => 'add'));
Router::connect('/article/edit/*', array('controller' => 'papers', 'action' => 'edit'));

/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
//Router::connect('/(.*).htm', array('controller' => 'pages', 'action' => 'display'));







Router::parseExtensions('json');