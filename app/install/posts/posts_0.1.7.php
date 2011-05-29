<?php 

$sql[] = "ALTER TABLE `posts` ADD `allow_comments` VARCHAR(10) NULL AFTER `reposters`";
//**** !2nd param is IMPORTANT! ****
$log = 'new field: allow_comments';



?>