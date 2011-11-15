<?php

$sql[] = "CREATE TABLE `myzeitung`.`rss_import_log` (`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY, `log` TEXT NOT NULL, `duration` INT NOT NULL DEFAULT '0', `rss_feed_id` INT NOT NULL DEFAULT '0', `posts_created` INT NOT NULL DEFAULT '0', `posts_not_created` INT NOT NULL DEFAULT '0', `rss_feeds_items_created` INT NOT NULL DEFAULT '0', `rss_feeds_items_not_created` INT NOT NULL DEFAULT '0', `rss_items_created` INT NOT NULL DEFAULT '0', `rss_items_not_created` INT NOT NULL DEFAULT '0', `rss_items_contents_created` INT NOT NULL DEFAULT '0', `rss_items_contents_not_created` INT NOT NULL DEFAULT '0', `category_paper_posts_created` INT NOT NULL DEFAULT '0', `created` DATETIME NOT NULL, `modified` DATETIME NOT NULL) ENGINE = MyISAM;";

//**** !2nd param is IMPORTANT! ****
$log = 'new table for logging rss imports';




?>



