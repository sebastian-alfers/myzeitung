<?php

#require "libs/Simplepie/simplepie.inc";

class RssController extends AppController {

	var $name = 'Rssimport';

	var $uses = array();

    var $cache;

      function __construct() {
        parent::__construct();
        $this->cache = CACHE . 'rss' . DS;
      }

	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('import', 'test', 'robot_landing');
	}

    function import(){
        debug($this->cache);
    //make the cache dir if it doesn't exist
    if (!file_exists($this->cache)) {
        if (!mkdir($this->cache, 0755, true)) {
            $this->log('can not create directory for post: ' . $this->cache);
            return false;
        }
    }

//setup SimplePie
    $feed = new SimplePie();
    $feed->set_feed_url('http://www.facebook.com/feeds/page.php?id=271834416189258&format=rss20');


    $feed->set_cache_location($this->cache);

    //retrieve the feed
    $feed->init();

    //get the feed items
    $items = $feed->get_items();

    //return
    if ($items) {
      foreach ($feed->get_items() as $item){
          echo ($item->get_title()) . ' ' . $item->get_permalink();
          #http://simplepie.org/wiki/setup/sample_page
      }
    } else {
      return false;
    }
    }

    function test(){
        ClassRegistry::init('Robot.RobotTask')->schedule(
            array('action' => 'robot_landing'),
            array('user_id' => $this->User->id )
        );

        ClassRegistry::init('Robot.RobotTask')->schedule(
            array('action' => 'robot_landing'),
            array('user_id' => $this->User->id )
        );

        ClassRegistry::init('Robot.RobotTask')->schedule(
            array('action' => 'robot_landing'),
            array('user_id' => $this->User->id )
        );
    }

    function robot_landing(){

        $this->log('robot_landing');

    }

}