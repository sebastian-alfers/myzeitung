<?php

require APPLIBS . "Simplepie/simplepie.inc";

class RssController extends AppController
{
    /**
     * counter for log
     */
    var $_posts_created = 0;
    var $_posts_not_created = 0;
    var $_rss_feeds_items_created = 0;
    var $_rss_feeds_items_not_created = 0;
    var $_rss_items_created = 0;
    var $_rss_items_not_created = 0;
    var $_rss_items_contents_created = 0;
    var $_rss_items_contents_not_created = 0;
    var $_category_paper_posts_created = 0;

    var $_log = array();


    var $name = 'Rss';


    var $uses = array('RssFeed', 'RssItem', 'RssFeedsItem', 'RssItemContent', 'Post', 'RssImportLog', 'CategoryPaperPosts');

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

        if($this->_import(5)){
            if($this->_import(6)){
               // $this->_import(5);
            }
        }



    }

    /**
     * main function to import and process one rss feed
     * - get rss (xml) from hosts
     * - get items for feed
     * - save item (check if one item is in several feeds)
     * - save attributes for feed
     * - create posts for all users publishing the feed
     * - create and save logs
     *
     *
     * read feed for id and import feeds is enabled
     *
     * @param null $feed_id
     * @return bool
     */
    private function _import($feed_id = null)
    {
        $this->_clearCounter();

        $start = explode(" ",microtime());

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

                if ($item_id !== false) {

                    //now, we check if each consument of the feed has already published the item

                    //prepare data for new post - user_id later
                    $new_post = array();
                    $new_post['rss_item_id'] = $item_id;
                    $new_post['title'] = $values['title'];
                    $new_post['content'] = $values['content'];
                    $new_post['created'] = $values['date'];
                    $new_post['modified'] = $values['date'];
                    $new_post['links'] = $values['links'];


                    foreach ($feed['RssFeedsUser'] as $rssFeedUser) {

                        if (isset($rssFeedUser['id'])) {
                            $this->Post->contain();
                            $post = $this->Post->find('first', array('fields' => array('id'), 'conditions' => array('Post.rss_item_id' => $item_id, 'Post.user_id' => $rssFeedUser['user_id'])));

                            if (!isset($post['Post']['id'])) {
                                $new_post['user_id'] = $rssFeedUser['user_id'];
                                $this->Post->create();
                                $this->Post->updateSolr = true;


                                if ($this->Post->save($new_post)) {
                                    $this->_posts_created++;

                                    //read and cound category_paper_posts for log
                                    $this->CategoryPaperPosts->contain();

                                    //debug($this->CategoryPaperPosts->find('count', array('conditions' => array('CategoryPaperPosts.post_id' => $this->Post->id))));

                                    $this->_category_paper_posts_created += $this->CategoryPaperPosts->find('count', array('conditions' => array('CategoryPaperPosts.post_id' => $this->Post->id)));
                                }
                                else {
                                    $this->_posts_not_created++;
                                    $this->_log('Error while saving post', $this->Post->invalidFields(), $new_post);
                                }
                            }
                            else {
                                //debug($feed);
                                //die();
                                //$this->_log('Found post that should not exist with post_id ' . $post['Post']['id'] . '. For rss_item_id ' . $item_id . ' and user_id ' . $rssFeedUser['id'], array(), $post);
                            }
                        }
                        else {
                            $this->_log('Error loading user', array(), $rssFeedUser);
                        }
                    }
                }
                else {
                    $this->_log('Error loading item_id while _processRssItem()', array(), array('item_id' => $item_id, 'feed_id' => $feed_id, 'hash' => $hash, 'value' => $values));
                }


            }
        }
        else {
            // not enabled
        }

        $end = explode(" ",microtime());

        $duration = (($end[1] + $end[0]) - ($start[1] + $start[0]));

        $log_data = array(
            'log' => json_encode($this->_log),
            'duration' => $duration,
            'rss_feed_id' => $feed_id,
            'posts_created' => $this->_posts_created,
            'posts_not_created' => $this->_posts_not_created,
            'rss_feeds_items_created' => $this->_rss_feeds_items_created,
            'rss_feeds_items_not_created' => $this->_rss_feeds_items_not_created,
            'rss_items_created' => $this->_rss_items_created,
            'rss_items_not_created' => $this->_rss_items_not_created,
            'rss_items_contents_created' => $this->_rss_items_contents_created,
            'rss_items_contents_not_created' => $this->_rss_items_contents_not_created,
            'category_paper_posts_created' => $this->_category_paper_posts_created
        );

        $this->RssImportLog->create();
        if(!$this->RssImportLog->save(array('RssImportLog' => $log_data))){
            $this->log('Error saving RssImportLog. ' . json_encode($log_data));
        }

        return true;

    }


    private function _clearCounter(){
        $this->_posts_created = 0;
        $this->_posts_not_created = 0;
        $this->_rss_feeds_items_created = 0;
        $this->_rss_feeds_items_not_created = 0;
        $this->_rss_items_created = 0;
        $this->_rss_items_not_created = 0;
        $this->_rss_items_contents_created = 0;
        $this->_rss_items_contents_not_created = 0;
        $this->_category_paper_posts_created = 0;
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

                $rss_feed_item_data = array('RssFeedsItem' => array('feed_id' => $feed_id, 'item_id' => $item_id));
                if (!$this->RssFeedsItem->save($rss_feed_item_data)) {
                    $this->_rss_feeds_items_not_created++;
                    $this->_log('Error while associating the feed_item to the feed', $this->RssFeedsItem->invalidFields(), $rss_feed_item_data);
                }
                else{

                    $this->_rss_feeds_items_created++;
                }
            }
            else {
                //error reading new rss item
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
                $rss_feed_item_data = array('RssFeedsItem' => array('feed_id' => $feed_id, 'item_id' => $item_id));
                if (!$this->RssFeedsItem->save($rss_feed_item_data)) {
                    $this->_rss_feeds_items_not_created++;
                    $this->_log('Error while associating the feed_item to the feed', $this->RssFeedsItem->invalidFields(), $rss_feed_item_data);
                    //errow while associating the feed_item to the feed
                }
                else{

                    $this->_rss_feeds_items_created++;
                }

            }
        }
        if ($item_id == '') return false;

        return $item_id;
    }

    /**
     *
     * @return false | $item_id
     */
    private function _createNewRssItem($hash, $values)
    {
        $data = array();
        $data['RssItem']['hash'] = $hash;
        $this->RssItem->create();
        if ($this->RssItem->save($data)) {
            $this->_rss_items_created++;

            //save values for each item
            $rss_item_id = $this->RssItem->id;
            $this->RssItemContent->contain();
            foreach ($values as $key => $value) {
                $data = array('RssItemContent' => array('item_id' => $rss_item_id, 'key' => $key, 'value' => $value));
                $this->RssItemContent->create();
                if (!$this->RssItemContent->save($data)) {
                    $this->_rss_items_contents_not_created++;
                    $this->_log('Error while saving rss_item_content ', $this->RssItemContent->invalidFields(), $data);
                    // log, but not return false!!!
                }
                else{
                    $this->_rss_items_contents_created++;
                }
            }
        }
        else {
            $this->_rss_items_not_created++;
            $this->_log('Error while saving rss_item ', $this->RssItem->invalidFields(), $data);
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

    /**
     * append log-stack $_log with log msg
     *
     * @param  $msg
     * @param string $data
     * @return void
     */
    private function _log($msg, $invalidFields = array(),  $data = ''){

        $_data  = array(
            'date' => date($this->Rss->getTimestampFormat()),
            'msg'  => $msg
        );

        if(is_array($invalidFields) && !empty($invalidFields)){
            $_data['invalidFields'] = json_encode($invalidFields);
        }else{
            $_data['invalidFields'] = $invalidFields;
        }

        if(is_array($data)){
            $_data['data'] = json_encode($data);
        }
        else{
            $_data['data'] = $data;
        }

        $this->_log[] = $_data;

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
       // $this->RssFeed->recursive = 1;
        // $this->RssFeed->contain('RssFeedsItem.id','RssItem.hash','RssItem.created');
       // debug($this->RssFeed->find('all'));
                App::import('model','RssFeedsUser');
        $this->RssFeed->RssFeedsUser = new RssFeedsUser();
        $this->RssFeed->deletePostsForDeletedFeedAssociation(18,6);
    }

}

/*

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

