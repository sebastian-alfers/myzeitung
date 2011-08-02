<?php 

$sql[] = "ALTER TABLE `users` CHANGE `posts_user_count` `repost_count` INT(11) NOT NULL";
$sql[] = "ALTER TABLE `posts` CHANGE `posts_user_count` `repost_count` INT(11) NOT NULL";
//**** !2nd param is IMPORTANT! ****
$log = 'renamed posts_user_count to repost_count. geeeeil';

?>