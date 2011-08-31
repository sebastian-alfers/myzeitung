<?php 

$sql[] = "ALTER TABLE `categories` CHANGE `category_paper_post_count` `post_count` INT(11) NOT NULL";
//**** !2nd param is IMPORTANT! ****
$log = 'renamed category_paper_post_count to post_count';

?>