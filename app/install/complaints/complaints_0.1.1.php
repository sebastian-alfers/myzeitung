<?php

$sql[] = "CREATE TABLE  `myzeitung`.`complaintstatuses` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
 `value` VARCHAR( 200 ) NOT NULL ,
PRIMARY KEY (  `id` )
) ENGINE = MYISAM DEFAULT CHARSET = latin1 AUTO_INCREMENT =5;

INSERT INTO  `myzeitung`.`complaintstatuses`
SELECT *
FROM  `myzeitung`.`complaintstatus` ;

DROP TABLE  `myzeitung`.`complaintstatus` ;";



//**** !2nd param is IMPORTANT! ****
$log = 'rename table for cake conventions';