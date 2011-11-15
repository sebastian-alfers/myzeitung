<?php 

$sql[] = "ALTER TABLE `posts` ADD `rss_item_id` INT NOT NULL AFTER `view_count`";

//**** !2nd param is IMPORTANT! ****
$log = 'added id for (maybe) existent rss_item source';

?>