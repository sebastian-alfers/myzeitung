<?php

$sql[] = "ALTER TABLE `routes` CHANGE `parent_id` `parent_id` INT(11) NULL DEFAULT NULL";
$sql[] = "ALTER TABLE `routes` CHANGE `target_param` `target_param` VARCHAR(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL";

//**** !2nd param is IMPORTANT! ****
$log = 'some fields defaults changed, ;

?>




