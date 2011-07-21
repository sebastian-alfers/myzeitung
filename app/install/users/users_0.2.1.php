<?php

$sql[] = "ALTER TABLE `users` ADD `topic_count` INT(11) NOT NULL";

//**** !2nd param is IMPORTANT! ****
$log = 'new cache_column for user to see if he has topics: needed for repost button to decide if topic chooser or not';




?>




