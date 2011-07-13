<?php

$sql[] = "DROP TABLE `myzeitung`.`complaints`;";

$sql[] = "CREATE TABLE `myzeitung`.`complaints` ( `id` int( 11 ) NOT NULL AUTO_INCREMENT ,
`paper_id` int( 11 ) DEFAULT NULL ,
`post_id` int( 11 ) DEFAULT NULL ,
`comment_id` int( 11 ) DEFAULT NULL ,
`user_id` int( 11 ) DEFAULT NULL ,
`reason_id` int( 3 ) NOT NULL ,
`comments` text NOT NULL ,
`reporter_id` int( 11 ) DEFAULT NULL ,
`reporter_email` varchar( 255 ) NOT NULL ,
`complaintstatus_id` int( 3 ) NOT NULL ,
`created` datetime NOT NULL ,
`modified` datetime NOT NULL ,
PRIMARY KEY ( `id` ) ) ENGINE = MyISAM DEFAULT CHARSET = latin1 AUTO_INCREMENT =0;";



//**** !2nd param is IMPORTANT! ****
$log = 'update table structure';