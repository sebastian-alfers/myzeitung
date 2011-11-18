<?php

$sql[] = "ALTER TABLE `rss_feeds` ADD `crawled` DATETIME NOT NULL DEFAULT '2000-01-01 13:00:00' AFTER `url`";


//**** !2nd param is IMPORTANT! ****
$log = 'new table for logging rss imports';




?>




