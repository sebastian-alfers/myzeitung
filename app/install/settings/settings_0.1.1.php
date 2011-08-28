<?php
$sql[] = "ALTER TABLE `settings` CHANGE `model_type` `model_type` VARCHAR(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `model_id` `model_id` INT(11) NULL DEFAULT NULL";
$sql[] = "ALTER TABLE `settings` ADD `value_data_type` VARCHAR(20) NOT NULL DEFAULT 'string' AFTER `value`";
$sql[] = "ALTER TABLE `settings` ADD `note` TEXT NULL DEFAULT NULL AFTER `value_data_type`";


//**** !2nd param is IMPORTANT! ****
$log = 'new table for settings';

?>



