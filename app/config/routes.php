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


//array with all premium paper urls


//
// LOADING PREMIUM ROUTES OF PREMIUM PAPERS (from cache or db)
//
$premiumPapers = Cache::read('premium_papers');

if($premiumPapers === false){
    App::import('Model', 'Route');
    $this->Route = new Route();
    $this->Route->contain();
    $routes = $this->Route->find('all', array('conditions' => array('premium' => true)));
    if(!empty($routes)){
        foreach($routes as $route){
            $premiumPapers[] = $route['Route']['source'];
        }
        Cache::write('premium_papers', $premiumPapers);

    }
}

$prefix ='';
$isPremiumPaper = false;
if(isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI'])){
    $prefix = substr($_SERVER['REQUEST_URI'],0,3);

    //extract potential premium url vom request to check with array later
    $posSlash = strpos($_SERVER['REQUEST_URI'], '/', 1);
    if(!empty($posSlash)){
        $premiumSubstr = substr($_SERVER['REQUEST_URI'],0,$posSlash);
    }else{
        $premiumSubstr = $_SERVER['REQUEST_URI'];
    }


    //check if extracted substring matches premium array
    if(is_array($premiumPapers) && in_array($premiumSubstr,$premiumPapers)){
        $isPremiumPaper = true;
    }
}




// use DB-Routes just if route matches a premium_route or is a standard paper or article (prefixes)
if(in_array($prefix,array('/p/','/a/')) || $isPremiumPaper){

    
    $temp_url = $_SERVER['REQUEST_URI'];
    $temp_url_extra = '';
    //filter pagination params to find the correct routing in cache or database

    if($isPremiumPaper){
        // '/premium_paper/page:2/sort:desc ...' '/premium_paper'
        $temp_url = $premiumSubstr;
        if(substr_count($_SERVER['REQUEST_URI'], '/') > 1){
            $pos = strpos($_SERVER['REQUEST_URI'], '/', 2);
            //save extra parameters in case we have to 301 redirect -> so we can forward the params
            $temp_url_extra = substr($_SERVER['REQUEST_URI'], $pos);
        }
    }else{
        // '/p/username/paper_title/page:2/sort:asc ....' ---> '/p/username/paper_title'
        if(substr_count($_SERVER['REQUEST_URI'], '/') > 3){
            $pos = strpos($_SERVER['REQUEST_URI'], '/', 4);
            $pos = strpos($_SERVER['REQUEST_URI'], '/', $pos+1);
            $temp_url = substr($_SERVER['REQUEST_URI'], 0, $pos);
            //save extra parameters in case we have to 301 redirect -> so we can forward the params
            $temp_url_extra = substr($_SERVER['REQUEST_URI'], $pos);

        }
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
   // debug($route);
    if($route['Route']['parent_id'] != null){

            header ('HTTP/1.1 301 Moved Permanently');
            header ('Location: '.$route['ParentRoute']['source'].$temp_url_extra);
    }
    if($isPremiumPaper){
        Router::connect($temp_url.'/:category_id/feed',array('controller' => 'papers' , 'action' => 'feed','url' => array('ext' => 'rss'), $route['Route']['target_param']),array('category_id' => '[0-9]+') );
        Router::connect($temp_url.'/:category_id/*',array('controller' => 'papers' , 'action' => 'view',$route['Route']['target_param']),array('category_id' => '[0-9]+') );
        Router::connect($temp_url.'/feed',array('controller' => 'papers' , 'action' => 'feed','url' => array('ext' => 'rss'), $route['Route']['target_param']),array() );
        Router::connect($temp_url.'/*',array('controller' => 'papers' , 'action' => 'view',$route['Route']['target_param']),array() );
    }else{
        if(!empty($route)){
            //Router::connect($_SERVER['REQUEST_URI'].'/*',array('controller' => $route['Route']['target_controller'] , 'action' => $route['Route']['target_action'], $route['Route']['target_param']));
            if($route['Route']['ref_type'] == Route::TYPE_PAPER){
                Router::connect('/p/:username/:slug/:category_id/feed',array('controller' => 'papers' , 'action' => 'feed','url' => array('ext' => 'rss'), $route['Route']['target_param']),array('username' => '[a-z0-9]+[^/]', 'slug' => '[^/]+', 'category_id' => '[0-9]+') );
                Router::connect('/p/:username/:slug/:category_id/*',array('controller' => 'papers' , 'action' => 'view',$route['Route']['target_param']),array('username' => '[a-z0-9]+[^/]', 'slug' => '[^/]+', 'category_id' => '[0-9]+') );
                Router::connect('/p/:username/:slug/feed',array('controller' => 'papers' , 'action' => 'feed','url' => array('ext' => 'rss'), $route['Route']['target_param']),array('username' => '[a-z0-9]+[^/]', 'slug' => '[^/]+') );
                Router::connect('/p/:username/:slug/*',array('controller' => 'papers' , 'action' => 'view',$route['Route']['target_param']),array('username' => '[a-z0-9]+[^/]', 'slug' => '[^/]+') );
            }
            if($route['Route']['ref_type'] == Route::TYPE_POST){
                //if at some point we use a pagination system WITHIN an article , we need to add '/*' at the end of the url
                Router::connect('/a/:username/:slug/*',array('controller' => 'posts' , 'action' => 'view',$route['Route']['target_param']),array('username' => '[a-z0-9]+[^/]', 'slug' => '[^/]+') );
            }
         }
    }


}



/*
 * ---------------------------
 * STANDARD ROUTES
 * ---------------------------
 */


Router::connect('/sitemap', array('controller' => 'sitemaps', 'action' => 'index', 'url' => array('ext' => 'xml')));
//Router::connect('/sitemap/:action/*', array('controller' => 'sitemaps'));

Router::connect('/', array('controller' => 'home', 'action' => 'index'));

/**
 * user profile etc
 */

Router::connect('/u/:username/papers/*', array('controller' => 'users','action' =>'viewSubscriptions','own_paper' => 'own'),array('pass' => array('username','own_paper'), 'username' => '[a-z0-9]+[^/]'));
Router::connect('/u/:username/subscriptions/*', array('controller' => 'users','action' =>'viewSubscriptions' , 'own_paper' => 'subscriptions'),array('pass' => array('username', 'own_paper'), 'username' => '[a-z0-9]+[^/]'));
Router::connect('/u/:username/feed/*', array('controller' => 'users','action' =>'feed', 'url' => array('ext' => 'rss')),array( 'pass' => array('username'), 'username' => '[a-z0-9]+[^/]'));
Router::connect('/u/:username/:topic_id/*', array('controller' => 'users', 'action' => 'view'),array('pass' => array('username','topic_id'),'username' => '[a-z0-9]+[^/]','topic_id' => '[0-9]+'));
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
 * forgot password
 */

Router::connect('/forogt_password', array('controller' => 'users', 'action' => 'forgotPassword'));


/**
 * user account settings
 */

Router::connect('/settings', array('controller' => 'users', 'action' => 'accAboutMe'));
Router::connect('/settings/privacy', array('controller' => 'users', 'action' => 'accPrivacy'));
Router::connect('/settings/general', array('controller' => 'users', 'action' => 'accGeneral'));
Router::connect('/settings/social-media', array('controller' => 'users', 'action' => 'accSocial'));
Router::connect('/settings/invitations', array('controller' => 'users', 'action' => 'accInvitations'));

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
Router::connect('/paper/delete/*', array('controller' => 'papers', 'action' => 'delete'));
Router::connect('/paper/saveImage/*', array('controller' => 'papers', 'action' => 'saveImage'));
Router::connect('/paper/deleteImage/*', array('controller' => 'papers', 'action' => 'deleteImage'));
Router::connect('/paper/references/*', array('controller' => 'papers', 'action' => 'references'));

Router::connect('/article/add', array('controller' => 'posts', 'action' => 'add'));
Router::connect('/article/edit/*', array('controller' => 'posts', 'action' => 'edit'));

/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
//Router::connect('/(.*).htm', array('controller' => 'pages', 'action' => 'display'));


//landing pages
Router::connect('/c/beispiel-bundestag/*', array('controller' => 'landing', 'action' => 'bundestag'));
Router::connect('/c/beispiel-tier-umwelt/*', array('controller' => 'landing', 'action' => 'tierUmwelt'));
Router::connect('/c/beispiel-veggie/*', array('controller' => 'landing', 'action' => 'veggie'));
Router::connect('/c/beispiel-architekten/*', array('controller' => 'landing', 'action' => 'architektur'));






Router::parseExtensions('json','rss','xml');
