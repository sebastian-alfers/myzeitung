<?php 

$sql[] = "update papers set visible = 0;";
$sql[] = "ALTER TABLE `papers` CHANGE `visible` `visible_home` TINYINT(1) NOT NULL DEFAULT '0'";
$sql[] = "ALTER TABLE `papers` ADD `visible_index` TINYINT(1) NOT NULL DEFAULT '1' AFTER `visible_home`, ADD INDEX (`visible_index`) ";
$sql[] = "ALTER TABLE `users` ADD `visible_home` TINYINT(1) NOT NULL AFTER `enabled`, ADD INDEX (`visible_home`) ";
$sql[] = "ALTER TABLE `users` CHANGE `visible_home` `visible_home` TINYINT(1) NOT NULL DEFAULT '0'";



//**** !2nd param is IMPORTANT! ****
$log = 'no paper is visible on home page by default, every paper is visible on index by default, no user is visible on index by default, only posts that appear papers that are visible on homepage appear';




?>