<?php

App::import('model', 'Complaint');

$sql[] = "ALTER TABLE `reasons` ADD `type` INT(1) NOT NULL";
$sql[] = "TRUNCATE TABLE `reasons`";

//reasons for all persons
$sql[] = "INSERT INTO `myzeitung`.`reasons` (`id`, `value`, `type`) VALUES (NULL, 'It is a picture of my person', ".Complaint::TYPE_ALL.");";
$sql[] = "INSERT INTO `myzeitung`.`reasons` (`id`, `value`, `type`) VALUES (NULL, 'Other Reason', ".Complaint::TYPE_ALL.");";

//reasons for users
$sql[] = "INSERT INTO `myzeitung`.`reasons` (`id`, `value`, `type`) VALUES (NULL, 'The profile of the user image is inappropriate', ".Complaint::TYPE_USER.");";
$sql[] = "INSERT INTO `myzeitung`.`reasons` (`id`, `value`, `type`) VALUES (NULL, 'I am bothered by this user', ".Complaint::TYPE_USER.");";
$sql[] = "INSERT INTO `myzeitung`.`reasons` (`id`, `value`, `type`) VALUES (NULL, 'Posts / Comments by this user not match with the Terms and Conditions (Extremism/Glorification of violence/Pornografie)', ".Complaint::TYPE_USER.");";
$sql[] = "INSERT INTO `myzeitung`.`reasons` (`id`, `value`, `type`) VALUES (NULL, 'The user sends spam', ".Complaint::TYPE_USER.");";
$sql[] = "INSERT INTO `myzeitung`.`reasons` (`id`, `value`, `type`) VALUES (NULL, 'It is a hacked Author Profile', ".Complaint::TYPE_USER.");";

//reasons for post
$sql[] = "INSERT INTO `myzeitung`.`reasons` (`id`, `value`, `type`) VALUES (NULL, 'Contents of this article not match with the Terms and Conditions (Extremism/Glorification of violence/Pornografie)', ".Complaint::TYPE_POST.");";
$sql[] = "INSERT INTO `myzeitung`.`reasons` (`id`, `value`, `type`) VALUES (NULL, 'Comments of this article not match with the Terms and Conditions (Extremism/Glorification of violence/Pornografie)', ".Complaint::TYPE_POST.");";

//paper
$sql[] = "INSERT INTO `myzeitung`.`reasons` (`id`, `value`, `type`) VALUES (NULL, 'The papers Authors / Post not match with the Terms and Conditions (Extremism/Glorification of violence/Pornografie)', ".Complaint::TYPE_PAPER.");";
$sql[] = "INSERT INTO `myzeitung`.`reasons` (`id`, `value`, `type`) VALUES (NULL, 'The description of the paper is inappropriate', ".Complaint::TYPE_PAPER.");";
$sql[] = "INSERT INTO `myzeitung`.`reasons` (`id`, `value`, `type`) VALUES (NULL, 'The profile image of the paper is inappropriate', ".Complaint::TYPE_PAPER.");";






//**** !2nd param is IMPORTANT! ****
$log = 'added field to different reasons';




