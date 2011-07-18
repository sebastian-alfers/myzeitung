<?php

if($topic == null &&  $category == null){
echo $user['User']['username'].' subscribed to all of your posts into the paper '.$paper['Paper']['title'];
}
if($topic != null &&  $category == null){
echo $user['User']['username'].' subscribed to the posts of your topic '.$topic['Topic']['name'].' into the paper '.$paper['Paper']['title'];
}
if($topic == null &&  $category != null){
echo $user['User']['username'].' subscribed to all of your posts into the category '.$category['Category']['name'].' of the paper '.$paper['Paper']['title'];
}
if($topic == null &&  $category != null){
echo $user['User']['username'].' subscribed to the posts of your topic '.$topic['Topic']['name'].' into the category '.$category['Category']['name'].' of the paper '.$paper['Paper']['title'];
}

?>