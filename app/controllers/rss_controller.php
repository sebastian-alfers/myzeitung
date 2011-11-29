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


    var $uses = array('RssFeedsUser',  'RssFeed', 'RssItem', 'RssFeedsItem', 'RssItemContent', 'Post', 'RssImportLog', 'CategoryPaperPosts');

    var $components = array('Rss');




    public function beforeFilter()
    {
        parent::beforeFilter();
    }


    /*
     * crawlNextFeeds
     * Get a feed from the database. mark it as crawled recently. crawl the feed.

     * @param int NoOfFeeds - limit of feeds that should be imported at this iteration
     */

    function crawlNextFeeds(){

        $limit = $this->params['limit'];

        for($i=0; $i < $limit; $i++){
            //get the next feed
            $this->RssFeed->contain();
            $feed = $this->RssFeed->find('first', array('conditions' => array('enabled' => 1),
                                                /*'limit' => 1 ,*/ 'order' => array('crawled', 'created')));

            if(isset($feed['RssFeed']['id'])){

                //$feedData = array();
                //mark it as recently crawled
                //(doing this before crawling to assure that no parrallel running cron-jobs crawl the same feeds)
                $this->RssFeed->id = $feed['RssFeed']['id'];
               // $feedData['id'] = $feed['RssFeed']['id'];
               // $feedData['url'] =$feed['RssFeed']['url'];
                //$feedData['crawled'] = date(RssComponent::DATE_FORMT);
                $feed['RssFeed']['crawled'] = date(RssComponent::DATE_FORMT);
                $this->RssFeed->save($feed, array('validate' => false, 'fieldList' => array('crawled'), 'callbacks' => false));

                CakeLog::write('cron', $feed);

                //crawl the feed
                $this->_import($feed['RssFeed']['id']);
            }


        }

    }

    function admin_analyzeFeed(){

        $valid_posts = 0;
        $invalid_posts = array();

        if(isset($this->data['Rss']['feed_url']) && !empty($this->data['Rss']['feed_url'])){
            $feed_data = $this->Rss->get(array('RssFeed' => array('url' => $this->data['Rss']['feed_url'])));

            foreach($feed_data as $feed){

                $this->Post->set(array('Post' => $feed));
                if(!$this->Post->validates()){
                    $invalid_posts[] = $this->Post->invalidFields();
                }
                else{
                    $valid_posts++;
                }
            }

            $this->set('valid_posts', $valid_posts);
            if(count($invalid_posts) > 0){
                $this->set('invalid_posts', $invalid_posts);
            }

            if($this->data['Rss']['extend']){
                $this->set('feed_data', $feed_data);
            }

        }
    }



    /**
     * @return void
     */
  /*  function removeFeedForUser(){

        if(!$this->data || !isset($this->data['User']['feed_id']) || empty($this->data['User']['feed_id'])){
            $this->Session->setFlash(__('No Permission', true));

            $this->redirect($this->referer());
        }

        debug('check if feed is his own');


        if($this->canEdit('RssFeedsUser', $this->data['User']['feed_id'], 'user_id')){
            debug('jepp, he is the owner');

            $this->log($this->data);

            if((boolean)$this->data['User']['delete'] === true){
                debug('do delete all rss feeds posts');
            }
            else{
                debug('do not delete all rss feeds posts');
            }
        }
        else{
            //user is not the owner
            $this->Session->setFlash(__('No Permission', true));
            $this->redirect($this->referer());
        }
<<<<<<< HEAD
        die('wtf?');
    }
=======
        die();
    } */


  /*  function addFeedForUser(){

        if($this->data){
            if($this->Session->read('Auth.User.id')){
                //he is logged in
                $url = $this->data['User']['feed_url'];

                //$this->
            }
        }

        $this->redirect($this->referer());
    }*/

    function addFeedForUser(){
       if (!empty($this->data)) {

          // App::import('model','RssFeed');
          // $this->RssFeed = new RssFeed();
           $validation = $this->_addFeedForUserValidations($this->data['User']['feed_url']);

            if(is_string($validation)){
               $this->Session->setFlash($validation);
               $this->redirect($this->referer());
            }

            $feed_id = false;
            $feed_id = $this->RssFeed->addFeedToUser($this->Session->read('Auth.User.id'), $this->data['User']['feed_url']);
			if ($feed_id !== false) {

                //schedule refresh
                /*
                 ClassRegistry::init('Robot.RobotTask')->schedule(
                      '/rss/feedCrawl',
                     array('feed_id' => $feed_id)
                 );
                */
                $flashMessage =__('The Rss-Feed has been added to your account. It might take a while until the first posts are generated.',true);

                $this->Session->setFlash($flashMessage, 'default', array('class' => 'success'));
                $this->redirect($this->referer());
			} else {
				$this->Session->setFlash(__('The Rss-Feed could not be added.'.' '.'Please, try again.', true));
                $this->redirect($this->referer());
			}
		}
	}
    
    private function _addFeedForUserValidations($feed_url){

        $this->RssFeedsUser->contain('RssFeed.url');
        $feeds = $this->RssFeedsUser->find('all', array('conditions' => array('user_id' => $this->Session->read('Auth.User.id') )));
        $this->log($feeds);
        if(count($feeds) == 3 ){
            return __('You can only add up to 3 RSS-Feeds for automatic generation of posts.',true);
        }
        foreach($feeds as $feed){
            if($feed['RssFeed']['url'] == $feed_url){
                return __('You already added this Feed to your account',true);
            }
        }
        return true;

    }

    function removeFeedForUser() {

      /*  if(!$this->data || !isset($this->data['User']['feed_id']) || empty($this->data['User']['feed_id'])){
            $this->Session->setFlash(__('No Permission', true));

            $this->redirect($this->referer());
        }
      */

		if (!isset($this->data['User']['feed_id']) || empty($this->data['User']['feed_id'])) {
			$this->Session->setFlash(__('Invalid id for RSS-Feed', true));
			$this->redirect(array('controller' => 'users',  'action' => 'accRssImport'));
		}


        $this->RssFeedsUser->contain();
        if($this->RssFeedsUser->find('count', array('conditions' => array('user_id' => $this->Session->read('Auth.User.id'),'feed_id' => $this->data['User']['feed_id']))) >= 1){

            if ($this->RssFeed->removeFeedFromUser($this->Session->read('Auth.User.id'), $this->data['User']['feed_id'], $this->data['User']['delete'])) {
        
                $this->Session->setFlash(__('Rss-Feed has been removed.', true), 'default', array('class' => 'success'));
                $this->redirect(array('controller' => 'users',  'action' => 'accRssImport'));

            }

            $this->Session->setFlash(__('Rss-Feed could not be removed.'.' '.'Please, try again.', true));
            $this->redirect(array('controller' => 'users',  'action' => 'accRssImport'));
        } else {
            $this->Session->setFlash(__('The RSS-Feed is not associated with your user account.', true));
            $this->redirect(array('controller' => 'users',  'action' => 'accRssImport'));
        }
    }





    /**
     * for robot script
     *
     * @return void
     */
    function feedCrawl()
    {
        
        //$this->log('************ init crawl ' . json_encode($this->params));

        if (isset($this->params['robot']['feed_id']) && !empty($this->params['robot']['feed_id'])) {
            $id = $this->params['robot']['feed_id'];
            if ($this->_import($id)) {
                $this->log(' ** crawled '.$id.' **');
            }
            else {
                $this->log(' ** error(1) while crawled '.$id.' **');
                // error ???????????
            }
        }
        else {
            $this->log(' ** error(2) while crawle **');
            // error ???????????
        }

    }

    function admin_feedCrawl()
    {

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

    //    $this->log('enter import for Feed ' . (int)$feed_id);

        $this->_clearCounter();

        $start = explode(" ",microtime());


        $this->RssFeed->contain('RssFeedsUser');

        $feed = $this->RssFeed->read(array('id', 'url', 'enabled'), (int)$feed_id);


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
                                    //$this->_posts_not_created++;
                                    //$this->_log('Error while saving post', $this->Post->invalidFields(), $new_post);
                                    //$this->log('error 5 ' . json_encode($this->Post->invalidFields()));
                                }
                            }
                        }
                        else {
                            //$this->log('error 3');
                          //  $this->_log('Error loading user', array(), $rssFeedUser);
                        }
                    }
                }
                else {
                    //$this->log('error 2');
                    //$this->_log('Error loading item_id while _processRssItem()', array(), array('item_id' => $item_id, 'feed_id' => $feed_id, 'hash' => $hash, 'value' => $values));
                }


            }
        }
        else {

            // not enabled
        }


        if($this->_needToLog()){

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

            $this->log(' log data ' . json_encode($log_data));

            $this->RssImportLog->create();
            if(!$this->RssImportLog->save(array('RssImportLog' => $log_data))){
                $this->log('Error saving RssImportLog. ' . json_encode($log_data));
            }
        }


        $this->log(' > finish import');

        return true;

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
        $this->log('process item');
        $this->log($feed_id);
        $this->log($hash);
        //$this->log($values);
        $this->RssItem->contain();
        $data = $this->RssItem->find('first', array('conditions' => array('hash' => $hash)));
        $item_id = '';
        if (!isset($data['RssItem']['id'])) {
            // this item has not been imported before
            $item_id = $this->_createNewRssItem($hash, $values);
            $this->log('process item - A');
            if ($item_id) {
                //now we have am item with all contents saved
                // -> creating feed item relation
                $this->RssFeedsItem->create();
                $this->log('process item - B');
                $rss_feed_item_data = array('RssFeedsItem' => array('feed_id' => $feed_id, 'item_id' => $item_id));
                if (!$this->RssFeedsItem->save($rss_feed_item_data)) {
                    $this->_rss_feeds_items_not_created++;
                    $this->_log('Error while associating the feed_item to the feed', $this->RssFeedsItem->invalidFields(), $rss_feed_item_data);
                    $this->log('process item - C');
                }
                else{
                    $this->log('process item - D - ITEM CREATED');
                    $this->_rss_feeds_items_created++;
                }
            }
            else {
                $this->log('process item - E');
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

    /**
     * check if the counter values are changed
     *
     * @return boolean
     */
    private function _needToLog(){

        if($this->_posts_created > 0) return true;
        if($this->_posts_not_created > 0) return true;
        if($this->_rss_feeds_items_created > 0) return true;
        if($this->_rss_feeds_items_not_created > 0) return true;
        if($this->_rss_items_created > 0) return true;
        if($this->_rss_items_not_created > 0) return true;
        if($this->_rss_items_contents_created > 0) return true;
        if($this->_rss_items_contents_not_created > 0) return true;
        if($this->_category_paper_posts_created > 0) return true;

        return false;
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







    /*
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
    */



    /*


    function scheduleAllFeedsForCrawling()
    {
        $this->log('** add all + self in 2-minutes **');

        $this->RssFeed->contain();
        $feeds = $this->RssFeed->find('all', array('fields' => array('id', 'url', 'crawled', 'created'), 'conditions' => array('enabled' => 1),  'limit' => 3, 'order' => array('crawled', 'created')));

        //schedule a crawl action for each active feed
        foreach ($feeds as $feed) {
            $this->scheduleSingleFeedForCrawling($feed['RssFeed']['id']);
        }


        //rescheduling itself for next crawling
        ClassRegistry::init('Robot.RobotTask')->schedule(
            '/rss/scheduleAllFeedsForCrawling',
            array(),
            strtotime("+10 Minutes")
        );

        App::import('model', 'Robot.RobotTask');
        $robots_task = new RobotTask();
        //delete all posts that kept in running and older then 5 min
        $robots_task->deleteAll(array('RobotTask.status' => 'running', 'RobotTask.scheduled <' => date(RssComponent::DATE_FORMT, strtotime('-22 minutes'))));
        
    }
    */

      /*
    function scheduleSingleFeedForCrawling($feed_id)
    {
        ClassRegistry::init('Robot.RobotTask')->schedule(
            '/rss/feedCrawl',
            array('feed_id' => $feed_id)
        );
    }
      */

    function testModels()
    {
       // $this->RssFeed->recursive = 1;
        // $this->RssFeed->contain('RssFeedsItem.id','RssItem.hash','RssItem.created');
       // debug($this->RssFeed->find('all'));
       /*         App::import('model','RssFeedsUser');
        $this->RssFeed->RssFeedsUser = new RssFeedsUser();
        $this->RssFeed->deletePostsForDeletedFeedAssociation(18,6);*/


        //$this->RssFeed->delete(1, true);

      //  $string = preg_replace('/[^\s\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}]/mu', 'posts/view/한국어/page:1/sort:asc', '_');
       // debug($string);

        //debug(date('Y-m-d H:i:s'));
    }

    /*
    function admin_robotTasks(){
        App::import('model', 'Robot.RobotTask');

        $robots_task = new RobotTask();


        $data = $robots_task->find('all', array('conditions' => array('RobotTask.status' => 'running', 'RobotTask.scheduled >' => date('Y-m-d', strtotime('-5 minutes')))));

        debug($data);
    }
    */




}

/*
 *
 *

 *


'',
'http://www.herthabsc.de/index.php?id=1809&type=100',
'http://www.spiegel.de/schlagzeilen/tops/index.rss',
'http://www.spiegel.de/video/index.rss',
'http://www.spiegel.de/home/seite2/index.rss',
'http://www.facebook.com/feeds/page.php?id=141973839152649&format=rss20',
'http://www.facebook.com/feeds/page.php?id=6815841748&format=rss20',
'http://rss.golem.de/rss.php?tp=games&feed=ATOM1.0',
'http://www.heise.de/newsticker/heise-atom.xml',
'http://www.heise.de/newsticker/heise.rdf',
'http://www.heise.de/newsticker/heise-top-atom.xml',
'http://www.heise.de/autos/rss/news-atom.xml',
'http://www.heise.de/autos/rss/news.rdf',
'http://www.heise.de/ct-tv/rss/news-atom.xml',
'http://www.heise.de/ct-tv/rss/vorschau/news-atom.xml',
''

http://www.spiegel.de/schlagzeilen/eilmeldungen/index.rss
*/
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

