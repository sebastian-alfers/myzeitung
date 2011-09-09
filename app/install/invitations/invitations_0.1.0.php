<?php 


$sql[] = "CREATE TABLE `myzeitung`.`invitations` (`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` INT(11) NOT NULL, `text` VARCHAR(1000) NULL, `created` DATETIME NOT NULL) ENGINE = MyISAM;";
//**** !2nd param is IMPORTANT! ****
$log = 'new table invitations';




?>