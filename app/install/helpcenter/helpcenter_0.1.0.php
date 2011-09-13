<?php 

$sql[] = "CREATE TABLE `myzeitung`.`helpelements` (`id` INT(11) NOT NULL AUTO_INCREMENT, `page_id` INT(11) NOT NULL, `accessor` VARCHAR(100) NOT NULL, `deu` TEXT NULL DEFAULT NULL, `eng` TEXT NULL DEFAULT NULL, `created` DATETIME NOT NULL, `modified` DATETIME NOT NULL, PRIMARY KEY (`id`)) ENGINE = MyISAM;";
$sql[] = "CREATE TABLE `myzeitung`.`helppages` (`id` INT(11) NOT NULL AUTO_INCREMENT, `controller` VARCHAR(100) NOT NULL, `action` VARCHAR(100) NOT NULL, PRIMARY KEY (`id`)) ENGINE = MyISAM;";
$sql[] = "ALTER TABLE `helpelements` ADD `description` VARCHAR(100) NULL DEFAULT NULL AFTER `id`";



//**** !2nd param is IMPORTANT! ****
$log = 'helpcenter';




?>