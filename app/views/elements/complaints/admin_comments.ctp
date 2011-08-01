<?php
foreach(array_reverse(unserialize($comments)) as $comment){
        if(isset($this->data['Complaint']['comment_author'])){
            echo __('Written by', true) . $this->data['Complaint']['comment_author'];
        }
        echo $this->MzTime->timeAgoInWords($comment['date'], array('format' => 'd.m.y  h:m','end' => '+1 Month'));
        echo "<br />";
        echo $comment['comment'];
        echo "<hr>";
}
?>