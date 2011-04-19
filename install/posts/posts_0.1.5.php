<?php 

$sql[] = "ALTER TABLE `posts` ADD `content_preview` VARCHAR(250) NOT NULL AFTER `content`";

//**** !2nd param is IMPORTANT! ****
$log = 'new column to get preview';



?>