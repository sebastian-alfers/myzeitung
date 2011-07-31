<?php

$sql[] = "ALTER TABLE `comments` ADD `enabled` TINYINT(1) NOT NULL DEFAULT '1'";

//**** !2nd param is IMPORTANT! ****
$log = 'new field enabled for posts_users (namespace posts_users did not work)';




?>




