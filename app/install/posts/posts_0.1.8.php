<?php 

$sql[] = "ALTER TABLE `posts` ADD `links` TEXT NOT NULL AFTER `image`";

//**** !2nd param is IMPORTANT! ****
$log = 'new field for links';

?>