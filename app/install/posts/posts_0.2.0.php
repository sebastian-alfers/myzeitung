<?php
$sql[] = "CREATE TABLE `myzeitung`.`media` (`id` INT(11) NOT NULL AUTO_INCREMENT, `post_id` INT(11) NOT NULL, `type` VARCHAR(50) NOT NULL, `path` INT(255) NOT NULL, `file_name` VARCHAR(200) NOT NULL, `size` TEXT NOT NULL, `og` TEXT NULL, `description` TEXT NULL, `created` DATETIME NOT NULL, `modified` DATETIME NOT NULL, PRIMARY KEY (`id`)) ENGINE = MyISAM;";
