<?php

require APPLIBS . "Simplepie/simplepie.inc";

class RssController extends AppController
{

    var $name = 'Rss';


    var $uses = array('RssFeed', 'RssItem', 'RssFeedsItem', 'RssItemContent', 'Post');

    var $components = array('Rss');


    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('import', 'scheduleAllFeedsForCrawling', 'testModels');
    }

    /**
     * for robot script
     *
     * @return void
     */
    function feedCrawl()
    {
        if (isset($this->params['robot']['feed_id']) && !empty($this->params['robot']['feed_id'])) {
            $id = $this->params['robot']['feed_id'];
            if ($this->_import($id)) {

            }
            else {
                // error ???????????
            }
        }
        else {
            // error ???????????
        }

    }

    function admin_feedCrawl()
    {

    }

    function import()
    {
        $this->_import(5);
    }

    /**
     * read feed for id and import feeds is enabled
     *
     * @param null $feed_id
     * @return bool
     */
    private function _import($feed_id = null)
    {
        if (!is_int($feed_id) || $feed_id == null) {
            // error ???????????
            return false;
        }
        $this->RssFeed->contain('RssFeedsUser');
        $feed = $this->RssFeed->read(array('id', 'url', 'enabled'), $feed_id);

        if ((boolean)$feed['RssFeed']['enabled']) {

            $items = $this->Rss->get($feed);
            foreach ($items as $hash => $values) {

                $item_id = $this->_processRssItem($feed_id, $hash, $values);

                if($item_id !== false){

                    //now, we check if each consument of the feed has already published the item

                    //prepare data for new post - user_id later
                    $new_post = array();
                    $new_post['rss_item_id'] = $item_id;
                    $new_post['title'] = $values['title'];
                    $new_post['content'] = $values['content'];
                    $new_post['created'] = $values['date'];
                    $new_post['modified'] = $values['date'];

                    foreach($feed['RssFeedsUser'] as $user){

                        if(isset($user['id'])){
                            $this->Post->contain();
                            $post = $this->Post->find('first', array('fields' => array('id'), 'conditions' => array('Post.rss_item_id' => $item_id, 'Post.user_id' => $user['id'])));

                            if(!isset($post['Post']['id'])){
                                $new_post['user_id'] = $user['id'];
                                $this->Post->create();
                                $this->Post->updateSolr = true;

                                if($this->Post->save($new_post)){

                                }
                                else{
                                    //error saving post
                                }
                            }
                            else{
                                //error loading post
                            }
                        }
                        else{
                            // error reading user
                        }

                    }

                }
                else{

                }


            }
        }
        else {
            // not enabled
        }


    }


    /**
     * create rss item if not created
     * associate item to feed it not associated
     *
     * @param  $feed_id
     * @param  $hash
     * @param  $values
     * @return false | $feed_id
     */
    private function _processRssItem($feed_id, $hash, $values)
    {
        $this->RssItem->contain();
        $data = $this->RssItem->find('first', array('conditions' => array('hash' => $hash)));
        $item_id = '';
        if (!isset($data['RssItem']['id'])) {
            // this item has not been imported before
            $item_id = $this->_createNewRssItem($hash, $values);

            if ($item_id) {
                //now we have am item with all contents saved
                // -> creating feed item relation
                $this->RssFeedsItem->create();

                if (!$this->RssFeedsItem->save(array('RssFeedsItem' => array('feed_id' => $feed_id, 'item_id' => $item_id)))) {

                    //errow while associating the feed_item to the feed
                }

            }
            else {
                //error reating new rss item
            }


        } else {
            // this item was already existent
            //a item can belong to multiple feeds

            $item_id = $data['RssItem']['id'];

            //check for active relation for current feed
            $this->RssFeedsItem->contain();
            if (!$this->RssFeedsItem->find('first', array('conditions' => array('feed_id' => $feed_id, 'item_id' => $item_id)))) {
                //no relation found
                $this->RssFeedsItem->create();
                if (!$this->RssFeedsItem->save(array('RssFeedsItem' => array('feed_id' => $feed_id, 'item_id' => $item_id)))) {
                    //errow while associating the feed_item to the feed
                }
            }
        }
        if($item_id == '') return false;

        return $item_id;
    }

    /**
     *
     * @return false (if there was a problem | $item_id
     */
    private function _createNewRssItem($hash, $values)
    {
        $data = array();
        $data['RssItem']['hash'] = $hash;
        $this->RssItem->create();
        if ($this->RssItem->save($data)) {
            //save values for each item
            $rss_item_id = $this->RssItem->id;
            $this->RssItemContent->contain();
            foreach ($values as $key => $value) {
                $data = array('RssItemContent' => array('item_id' => $rss_item_id, 'key' => $key, 'value' => $value));
                $this->RssItemContent->create();
                if (!$this->RssItemContent->save($data)) {
                    //error saving content
                }
            }
        }
        else {
            // error saving rss item
            return false;
        }
        return $rss_item_id;

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
    function scheduleAllFeedsForCrawling()
    {
        $this->RssFeed->contain();
        $feeds = $this->RssFeed->find('all', array('fields' => array('id'), 'conditions' => array('enabled' => 1)));


        //schedulding a crawl action for each active feed
        foreach ($feeds as $feed) {
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


    function scheduleSingleFeedForCrawling($feed_id)
    {

        ClassRegistry::init('Robot.RobotTask')->schedule(
            '/rss/feedCrawl',
            array('feed_id' => $feed_id)
        );
    }

    function testModels()
    {
        $this->RssFeed->recursive = 1;
        // $this->RssFeed->contain('RssFeedsItem.id','RssItem.hash','RssItem.created');
        debug($this->RssFeed->find('all'));

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

