<?php 
$sql[] = "ALTER TABLE `papers` ADD `visible` TINYINT(1) NOT NULL DEFAULT '1' AFTER `enabled`, ADD INDEX (`visible`) ";

//**** !2nd param is IMPORTANT! ****
$log = 'we hide papers from start-page and from /papers page';




?>