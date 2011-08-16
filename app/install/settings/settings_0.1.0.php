<?php
$sql[] = "DROP TABLE `myzeitung`.`settings`";
$sql[] = "CREATE TABLE `myzeitung`.`settings` (`id` INT(11) NOT NULL AUTO_INCREMENT, `model_type` VARCHAR(20) NOT NULL, `model_id` INT(11) NOT NULL, `key` VARCHAR(50) NOT NULL, `value` TEXT NOT NULL, `created` DATETIME NOT NULL, `modified` DATETIME NOT NULL, PRIMARY KEY (`id`)) ENGINE = MyISAM;";
$sql[] = "ALTER TABLE `settings` CHANGE `value` `value` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '' ";
$sql[] = "ALTER TABLE `settings` ADD `namespace` VARCHAR(20) NULL AFTER `model_id`";



//**** !2nd param is IMPORTANT! ****
$log = 'new table for settings';

?>

