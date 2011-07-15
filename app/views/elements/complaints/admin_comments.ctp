<?php
foreach(array_reverse(unserialize($comments)) as $comment){
        if(isset($this->data['Complaint']['comment_author'])){
            echo __('Written by', true) . $this->data['Complaint']['comment_author'];
        }
        echo $this->Time->timeAgoInWords($comment['date'], array('end' => '+1 Week'));
        echo "<br />";
        echo $comment['comment'];
        echo "<hr>";
}
?>