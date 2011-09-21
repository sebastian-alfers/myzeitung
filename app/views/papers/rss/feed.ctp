<?php

foreach($posts as $post){
    echo $this->MzRss->item(array(),$this->MzRss->preparePostForRSS($post));

}

$channelTitle = "myZeitung - ".$paper['Paper']['title'].' ('.$paper['User']['username'].')';

//this 'set' sets back data to the layout
$this->set('channel', array('title' => $channelTitle,'description' => $paper['Paper']['description'], 'image' => array('url' => $this->Html->url('/img/assets/logo-mail.gif', true),'title' => 'myZeitung', 'link' => $this->Html->url('/',true))));



?>