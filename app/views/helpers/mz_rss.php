<?php
App::import('Helper', 'Rss');


class MzRssHelper extends RssHelper {

    public $helpers = array('MzHtml', 'Image','MzText', 'Time');

    function preparePostForRSS($data){
        $author = '';
        if(!empty($data['User']['name'])){
            $author = $data['User']['name'].' ('.$data['User']['username'].')';
        }else{
            $author = $data['User']['username'];
        }

        if(isset($data['Post']['image']) && !empty($data['Post']['image'])){
            $imagedata = unserialize($data['Post']['image']);
            $imagedata = $imagedata[0];

            $imagepath = '/img/'.$this->Image->resize($imagedata['path'], null, 100);
            $imagetype = $imagedata['size']['mime'];
        }

        $rssItem = array('title' => $data['Post']['title'],
                     'link' => $data['Route'][0]['source'],
                     'guid' => $data['Route'][0]['source'],
                     'description' => $this->MzText->truncate(strip_tags($data['Post']['content']), 220,array('ending' => '...', 'exact' => false, 'html' => true)),
                     'author' => $author,
                     'pubDate' => $data['Post']['created'],
        );
        if(isset($imagepath) && isset($imagetype)){
            $rssItem['enclosure'] = array('url' => $this->MzHtml->url($imagepath, false),'type' => $imagetype);
        }


        return $rssItem;
    }

}