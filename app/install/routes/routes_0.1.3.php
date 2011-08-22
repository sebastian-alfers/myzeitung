
<?php

$sql[] = "ALTER TABLE `routes` ADD `ref_type` VARCHAR(10) NOT NULL AFTER `id`";
$sql[] = "ALTER TABLE `routes` CHANGE `ref_id` `ref_id` INT(11) NOT NULL";


//**** !2nd param is IMPORTANT! ****
$log = 'drop enabled field.' ;

?>