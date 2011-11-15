<?php

class RssComponent extends Object
{

    // directory for cache feeds
    var $cache;

    const DATE_FORMT = 'Y-m-d H:i:s';

    //instance for crawler
    var $_crawler = null;


    function __construct()
    {
        $this->cache = CACHE . 'rss' . DS;
    }


    /**
     * get a string or array with feed(s)
     *
     * @param array $feeds
     * @return bool
     */
    public function get($feed = array())
    {



        if (empty($feed) || !is_array($feed)) return false;


        $this->_initParser();

        $feeds_items = array();

        //we only wand valid feeds
        if (!$this->_isValidFeed($feed)) return false;

        $feed_url = $feed['RssFeed']['url'];


        $feeds_items = array();
        $items = $this->_parseFeed($feed_url);

        if (is_array($items)) {
            foreach ($items as $key => $item) {
                $item_data = $this->_getItemData($item);

                if (!is_array($item_data)) return false;

                $hash = $item_data['hash'];

                $feeds_items[$hash] = $item_data;
            }
        }
        else {
            return false;
        }


        return $feeds_items;
    }

    /**
     * get an instance of SimplePie_Item and get all available
     * values
     *
     * return only not emtpy values
     *
     * @param  $item SimplePie_Item
     * @return false | array
     */
    private function _getItemData($item)
    {

        if (!$item instanceof SimplePie_Item) return false;

        $data = array();

        $data['content'] = $item->get_content();

        $data['copyright'] = $item->get_copyright();
        $data['date'] = $item->get_date(self::DATE_FORMT);
        $data['hash'] = $this->_getHash($item->get_id());

        App::Import('helper', 'Text');
        $text_helper = new TextHelper();
        $data['title'] =  trim($text_helper->truncate($item->get_title(), 200, array('ending' => '...', 'exact' => false)));

        if($data['title'] == ''){
            $data['title'] = trim(strip_tags($text_helper->truncate($item->get_title(), 200, array('ending' => '...', 'exact' => false))));
        }

        if($data['title'] == ''){
            $data['title'] = $text_helper->truncate(strip_tags($data['content']), 200, array('ending' => '...', 'exact' => false));
        }

        $data['link'] = $item->get_link();


        $data['links'] = serialize($item->get_links());
        $data['permalink'] = $item->get_permalink();

        $valid_data = array();
        //remove empty fields
        foreach($data as $key => $value){
            if($value != '' && !is_array($value) && !empty($value)){
                $valid_data[$key] = $value;
            }
        }

        return $valid_data;
    }

    /**
     * check required fields
     *
     * @param  $feed array
     * @return bool
     */
    private function _isValidFeed($feed)
    {
        if (!isset($feed['RssFeed']['url']) || empty($feed['RssFeed']['url'])) return false;

        return true;
    }


    /**
     * get a feed url to parse
     *
     * @param string $feed
     * @return array | false
     */
    private function _parseFeed($feed = '')
    {

        if (is_array($feed) || empty($feed) || !is_string($feed)) return false;

        $this->_crawler->set_feed_url($feed);

        //retrieve the feed
        $this->_crawler->init();

        //get the feed items
        $items = $this->_crawler->get_items();

        if (is_array($items)) {
            return $items;
        }
        return false;
    }

    /**
     * create cache dir
     *
     * @return void
     */
    private function _initParser()
    {
        //make the cache dir if it doesn't exist
        if (!file_exists($this->cache)) {
            if (!mkdir($this->cache, 0755, true)) {
                $this->log('can not create directory for post: ' . $this->cache);
                return false;
            }
        }

        if ($this->_crawler == null) {
            $this->_crawler = new SimplePie();
            $this->_crawler->set_cache_location($this->cache);

            $this->_crawler->strip_htmltags(array('base', 'blink', 'body', 'doctype', 'embed', 'font', 'form', 'frame', 'frameset', 'html', 'iframe', 'input', 'marquee', 'meta', 'noscript', 'script', 'style'));

        }

        return true;
    }


    /**
     * get a hash for a string - e.g. from feed->item->guid
     *
     * @return string
     */
    private function _getHash($string)
    {
        return Security::hash($string);
    }

    function getTimestampFormat(){
        return self::DATE_FORMT;
    }


    /**
     * check if a running task is in robot_tasks
     *
     *
     * @return boolean
     */
    function hasRunningTask(){
        App::import('model', 'Robot.RobotTask');
        $this->RobotTask = new RobotTask();

        return $this->RobotTask->find('count', array('conditions' => array('RobotTask.status' => 'running'))) > 0;
    }
}
