<?php

require APPLIBS . "Simplepie/simplepie.inc";

class RssController extends AppController
{

    var $name = 'Rss';


    var $uses = array('RssFeed','RssItem');

    var $components = array('Rss');



    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('import', 'scheduleAllFeedsForCrawling', 'testModels');
    }

    function import()
    {
        $url = array(array('RssFeed' => array('id' => 1, 'url' => 'http://rss.golem.de/rss.php?feed=ATOM1.0')),
                     array('RssFeed' => array('id' => 2, 'url' => 'http://rss.golem.de/rss.php?tp=av&feed=ATOM1.0')));


        debug($this->Rss->get($url));


    }

    function test()
    {


        ClassRegistry::init('Robot.RobotTask')->schedule(
            array('action' => 'robotlanding'),
            array('user_id' => $this->User->id)
        );

        ClassRegistry::init('Robot.RobotTask')->schedule(
            array('action' => 'robotlanding'),
            array('user_id' => $this->User->id)
        );

        ClassRegistry::init('Robot.RobotTask')->schedule(
            array('action' => 'robotlanding'),
            array('user_id' => $this->User->id)
        );
    }

    function robotlanding()
    {

        $this->log('robotlanding');

    }


    /*

    */
    function scheduleAllFeedsForCrawling(){

        $this->RssFeed->contain();
        $feeds = $this->RssFeed->find('all',array('fields' => array('id'),'conditions' => array('enabled' => 1)));

        //schedulding a crawl action for each active feed
        foreach($feeds as $feed){
           $this->scheduleSingleFeedForCrawling($feed['RssFeed']['id']);
        }

        //rescheduling itself for next crawling
        ClassRegistry::init('Robot.RobotTask')->schedule(
            '/rss/scheduleAllFeedsForCrawling',
            array(),
            strtotime("+10 Minutes")
        );
        $this->log('inter');
    }

    function scheduleSingleFeedForCrawling($feed_id){

            ClassRegistry::init('Robot.RobotTask')->schedule(
                                     '/rss/feedCrawl',
            array('feed_id' => $feed_id)
            );

    }


}

   /*
    * $start = explode(" ",microtime());
    'http://www.facebook.com/feeds/page.php?id=271834416189258&format=rss20',
    'http://www.herthabsc.de/index.php?id=1809&type=100',
    'http://www.spiegel.de/schlagzeilen/tops/index.rss',
    'http://www.spiegel.de/schlagzeilen/eilmeldungen/index.rss',
    'http://www.spiegel.de/video/index.rss',
    'http://www.spiegel.de/home/seite2/index.rss',
    'http://www.facebook.com/feeds/page.php?id=141973839152649&format=rss20',
    'http://www.facebook.com/feeds/page.php?id=6815841748&format=rss20',
    'http://golem.de.dynamic.feedsportal.com/pf/578069/http://rss.golem.de/rss.php?feed=RSS0.91',
    'http://golem.de.dynamic.feedsportal.com/pf/578069/http://rss.golem.de/rss.php?tp=foto&feed=RSS2.0',
    'http://rss.golem.de/rss.php?tp=games&feed=ATOM1.0',
    'http://rss.golem.de/rss.php?tp=handy&feed=OPML',
    'http://www.heise.de/newsticker/heise-atom.xml',
    'http://www.heise.de/newsticker/heise.rdf',
    'http://www.heise.de/newsticker/heise-top-atom.xml',
    'http://www.heise.de/autos/rss/news-atom.xml',
    'http://www.heise.de/autos/rss/news.rdf',
    'http://www.heise.de/ct-tv/rss/news-atom.xml',
    'http://www.heise.de/ct-tv/rss/vorschau/news-atom.xml'

            /*
            $this->log('************************************');
            $this->log('saved posts: ' . $saved_posts);
            $this->log('not saved posts: ' . $not_saved_posts);
            $this->log('deleted posts: ' . $deleted_posts);
            $this->log('not deleted posts: ' . $not_deleted_posts);

            $end = explode(" ",microtime());
            $s = (($end[1] + $end[0]) - ($start[1] + $start[0]));
            $this->log('script executed in '.$s.' seconds');

            $this->log('************************************');
            $this->log('');


                /*
                $post = new Post();

                $data = array('Post' => array(
                    'title' => $item->get_title(),
                    'content' => $item->get_permalink(),
                    'user_id' => 239,
                ));

                if($post->save($data)){
                    $saved_posts++;
                    if($post->delete($post->id)){
                        $deleted_posts ++;
                    }

                }
                else{
                    //$this->log(' > ' . json_encode($post->invalidFields()));
                    //$this->log('   ' . json_encode($data));
                    //$this->log(' < ');
                    $not_deleted_posts++;
                    $not_saved_posts++;
                }
                */

