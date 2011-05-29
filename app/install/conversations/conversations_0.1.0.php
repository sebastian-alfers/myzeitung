<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'comments';
$this->data['Install']['version'] = 0.1.0;
$this->Install->save($this->data); 
*/

$sql[] = "CREATE TABLE `conversations` (\n"
    . " `id` int(11) NOT NULL AUTO_INCREMENT,\n"
    . " `user_id` int(11) NOT NULL,\n"
    . " `title` varchar(60) COLLATE utf8_unicode_ci NOT NULL,\n"
    . " `created` datetime NOT NULL,\n"
    . " `last_message_id` int(11) unsigned NOT NULL,\n"
    . " `conversation_message_count` int(11) unsigned NOT NULL,\n"
    . " PRIMARY KEY (`id`),\n"
    . " KEY `user_id` (`user_id`)\n"
    . ") ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;";

//**** !2nd param is IMPORTANT! ****
$log = 'new table conversations';




?>