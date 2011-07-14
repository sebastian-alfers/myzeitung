<?php

$sql[] = "ALTER TABLE `complaints` ADD `reporter_name` VARCHAR(100) NOT NULL AFTER `reporter_email`";



//**** !2nd param is IMPORTANT! ****
$log = 'add field to save user name';



