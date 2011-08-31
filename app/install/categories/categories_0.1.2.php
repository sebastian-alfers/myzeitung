<?php 

$sql[] = "ALTER TABLE `categories` CHANGE `content_paper_count` `author_count` INT(11) NOT NULL";

//**** !2nd param is IMPORTANT! ****
$log = 'renamed content paper count';

?>