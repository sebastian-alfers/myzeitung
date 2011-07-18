<?php

echo $conversation['Conversation']['title'];

foreach($conversation['Conversation']['ConversationMessage'] as $message){
    echo $message['User']['username'].' '.$message['created'];
    echo $message['message'];
}

?>