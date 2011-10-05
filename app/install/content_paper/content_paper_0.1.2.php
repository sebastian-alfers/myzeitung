<?php 



$sql[] ="ALTER TABLE `content_papers` ADD `created` DATETIME NOT NULL";
//**** !2nd param is IMPORTANT! ****
$log = 'date field added - only created. you cannot modify (just delete) an entry';




?>