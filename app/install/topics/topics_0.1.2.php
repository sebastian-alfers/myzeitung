<?php 


$sql[] = "ALTER TABLE `topics` ADD `repost_count` INT(11) NOT NULL AFTER `post_count`";

//**** !2nd param is IMPORTANT! ****
$log = 'repost_count added';




?>