<?php

$sql[] = "CREATE TABLE `myzeitung`.`rss_feeds_users` (`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, `feed_id` INT(11) NOT NULL, `user_id` INT(11) NOT NULL) ENGINE = MyISAM;";

//**** !2nd param is IMPORTANT! ****
$log = 'added users feeds relation';




?>




