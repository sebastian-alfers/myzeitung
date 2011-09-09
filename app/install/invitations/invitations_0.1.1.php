<?php 


$sql[] = "CREATE TABLE `myzeitung`.`invitee` (`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, `invitation_id` INT(11) NOT NULL, `email` VARCHAR(256) NOT NULL, `reminder_count` INT(11) NOT NULL) ENGINE = MyISAM;";


?>