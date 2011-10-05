<?php

foreach($posts as $post){
    echo $this->MzRss->item(array(),$this->MzRss->preparePostForRSS($post));
}

$channelTitle = "myZeitung - ";
if(!empty($user['User']['name'])){
    $channelTitle .= $user['User']['name'].' ('.$user['User']['username'].')';
}else{
    $channelTitle .= $user['User']['username'];
}
//this 'set' sets back data to the layout
$this->set('channel', array('title' => $channelTitle,'description' => $user['User']['description'], 'image' => array('url' => $this->Cf->url('assets/logo-mail.gif', true),'title' => 'myZeitung', 'link' => $this->Html->url('/',true))));



?>