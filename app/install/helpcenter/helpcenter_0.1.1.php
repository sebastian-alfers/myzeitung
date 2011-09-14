<?php 

$sql[] = "ALTER TABLE `helpelements` ADD `order` INT(2) NOT NULL DEFAULT '0' AFTER `modified`";
$sql[] = "ALTER TABLE `helppages` ADD `deu` TEXT NULL DEFAULT NULL AFTER `id`, ADD `eng` TEXT NULL DEFAULT NULL AFTER `deu`";



//**** !2nd param is IMPORTANT! ****
$log = 'helpcenter';




?>