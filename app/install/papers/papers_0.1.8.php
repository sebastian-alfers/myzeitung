<?php 


$sql[] = "ALTER TABLE `papers` CHANGE `category_paper_post_count` `post_count` INT(11) NOT NULL";
//**** !2nd param is IMPORTANT! ****
$log = 'renamed categorypaperpostcount to post_count';




?>