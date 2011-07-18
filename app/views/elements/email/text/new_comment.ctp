<?php
echo $commentator['User']['username'];
if(!empty($commentator['User']['name'])) { echo ' - '.$commentator['User']['name'];}

echo $comment['Comment']['text'];

?>