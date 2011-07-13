<?php

$sql[] = "CREATE TABLE `complaints` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paper_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `comment_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `reason_id` int(3) NOT NULL,
  `comments` text NOT NULL,
  `reporter_id` int(11) DEFAULT NULL,
  `reporter_email` varchar(255) NOT NULL,
  `complaintstatus_id` int(3) NOT NULL,
  `created` int(11) NOT NULL,
  `updated` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";


$sql[] = "CREATE TABLE `myzeitung`.`reasons` (`id` INT(3) NOT NULL AUTO_INCREMENT PRIMARY KEY, `value` VARCHAR(2255) NOT NULL) ENGINE = MyISAM;";
$sql[] = "INSERT INTO `reasons` (`id`, `value`) VALUES
                    (1, 'Against the Law'),
                    (2, 'Other reason (see comment below)');";

$sql[] = "CREATE TABLE `myzeitung`.`complaintstatuses` (`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY, `value` VARCHAR(200) NOT NULL) ENGINE = MyISAM;";
$sql[] = "INSERT INTO `complaintstatus` (`id`, `value`) VALUES
                    (1, 'New'),
                    (2, 'In Progress'),
                    (3, 'Waiting for Input'),
                    (4, 'Closed');";


//**** !2nd param is IMPORTANT! ****
$log = 'create table for complaints';




?>
