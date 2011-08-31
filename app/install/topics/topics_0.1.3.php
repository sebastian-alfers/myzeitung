<?php 


$sql[] = "ALTER TABLE `topics` CHANGE `content_paper_count` `subscriber_count` INT(11) NOT NULL";
//**** !2nd param is IMPORTANT! ****
$log = 'repost_count added';




?>