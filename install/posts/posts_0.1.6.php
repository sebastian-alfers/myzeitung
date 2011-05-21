<?php 

$sql[] = "ALTER TABLE  `posts` CHANGE  `image`  `image` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL";

//**** !2nd param is IMPORTANT! ****
$log = 'varachar(300) was to short for serialized image';



?>