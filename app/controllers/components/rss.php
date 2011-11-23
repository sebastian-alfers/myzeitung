<?php

class RssComponent extends Object
{

    // directory for cache feeds
    var $cache;

    const DATE_FORMT = 'Y-m-d H:i:s';

    //instance for crawler
    var $_crawler = null;

    //for getting http-response code of images
    var $_dom = null;


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

        //we only want valid feeds

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

        //   debug($item->get_item_tags());

        //debug($item->get_content());
        //debug($item->get_enclosure());
        //die();


        $data = array();


        $data['asdfadsf'] = 'adsf';

        $data['content'] = htmlspecialchars_decode($item->get_content());
                $this->log('zimmer content1');
        $this->log($data['content']);
      // $data['content'] = strip_tags($data['content'], '<span><param><param /><a><blockquote><div><object><img><br><br /><p><ul><li><ol><h1><h2><h3><h4><h5><h6>');

        //(ORDER IMPORTANT ! ) h1 and h2 is not allowed : decreasing the number of the headlines to assure there is no second h2 element
        $data['content'] = str_replace(array('<h5','</h5>'),array('<h6','</h6>'),$data['content']);
        $data['content'] = str_replace(array('<h4','</h4>'),array('<h5','</h5>'),$data['content']);
        $data['content'] = str_replace(array('<h3','</h3>'),array('<h4','</h4>'),$data['content']);
        $data['content'] = str_replace(array('<h2','</h2>'),array('<h3','</h3>'),$data['content']);
        $data['content'] = str_replace(array('<h1','</h1>'),array('<h3','</h3>'),$data['content']);

        /*
        if(!$this->_imagesValid($data['content'])){
            $data['content'] = strip_tags($data['content'], '<object><br><br />');
        }
        */
        $this->log('zimmer content2');
        $this->log($data['content']);

        $data['copyright'] = $item->get_copyright();
        $data['date'] = $item->get_date(self::DATE_FORMT);
        if($data['date'] > date(self::DATE_FORMT)){
            $data['date'] = date(self::DATE_FORMT);
        }


        $data['hash'] = $this->_getHash($item->get_id());

        App::Import('helper', 'Text');
        $text_helper = new TextHelper();
        $data['title'] = trim($text_helper->truncate(htmlspecialchars_decode($item->get_title()), 200, array('ending' => '...', 'exact' => false)));

/*
        if ($data['title'] == '') {
            $data['title'] = trim($text_helper->truncate(strip_tags($item->get_title()), 200, array('ending' => '...', 'exact' => false)));
        }

        if ($data['title'] == '') {
            $data['title'] = $text_helper->truncate(strip_tags($data['content']), 200, array('ending' => '...', 'exact' => false));
        }
*/

     //   $data['title'] = htmlspecialchars_decode($data['title']);

        $data['link'] = htmlspecialchars_decode( $item->get_link());

        $temp_links = $item->get_links();
        foreach($temp_links as &$temp_link){
            $temp_link = htmlspecialchars_decode( $temp_link);
        }
        $data['links'] = serialize($temp_links);

     //   $data['link'] = $item->get_link();


        $data['permalink'] = htmlspecialchars_decode($item->get_permalink());

        $valid_data = array();
        //remove empty fields
        foreach ($data as $key => $value) {
            if ($value != '' && !is_array($value) && !empty($value)) {
                $valid_data[$key] = $value;
            }
        }
        $this->log($valid_data);
        return $valid_data;
    }


    private function _imagesValid($content)
    {

        @$this->_dom->loadHTML($content);
        $this->_dom->preserveWhiteSpace = false;
        $images = $this->_dom->getElementsByTagName('img');

        if ($images->length > 0) {
            foreach ($images as $image) {
                //echo "<img src='".$image->getAttribute('src')."'>";
                $src = $image->getAttribute('src');
                if (!empty($src) && !fopen($src, "r")) {
                    return false;
                }
            }
        }
        return true;
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
            $strip_htmltags = $this->_crawler->strip_htmltags;
            //delete some tags from default list
            array_splice($strip_htmltags, array_search('object', $strip_htmltags), 1);
            array_splice($strip_htmltags, array_search('param', $strip_htmltags), 1);
            array_splice($strip_htmltags, array_search('embed', $strip_htmltags), 1);
            //add tag to default list
            $strip_htmltags[] = 'span';
            $this->log($strip_htmltags);
            $this->_crawler->strip_htmltags($strip_htmltags);

    
            $this->log('STRIP ATTRIUTES');
            $this->log($this->_crawler->strip_attributes);
              $this->_crawler->strip_attributes();


        }

        $this->_dom = new domDocument;

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

    function getTimestampFormat()
    {
        return self::DATE_FORMT;
    }


}

