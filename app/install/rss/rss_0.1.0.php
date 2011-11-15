<?php

$sql[] = "CREATE TABLE `myzeitung`.`rss_feeds` (`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, `url` TEXT NOT NULL, `enabled` TINYINT(1) NOT NULL DEFAULT '1', `created` DATETIME NOT NULL) ENGINE = MyISAM;";

$sql[] = "CREATE TABLE `myzeitung`.`rss_items` (`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, `hash` VARCHAR(200) NOT NULL, `created` DATETIME NOT NULL) ENGINE = MyISAM;";

$sql[] = "CREATE TABLE `myzeitung`.`rss_item_content` (`id` INT(11) NOT NULL  AUTO_INCREMENT, `item_id` INT(11) NOT NULL, `key` VARCHAR(50) NOT NULL, `value` TEXT NOT NULL, PRIMARY KEY (`id`)) ENGINE = MyISAM;";

$sql[] = "CREATE TABLE `myzeitung`.`rss_feeds_items` (`id` INT(11) NOT NULL  AUTO_INCREMENT, `feed_id` INT(11) NOT NULL, `item_id` INT(11) NOT NULL, PRIMARY KEY (`id`)) ENGINE = MyISAM;";

//**** !2nd param is IMPORTANT! ****
$log = 'initial rss tables';




?>




