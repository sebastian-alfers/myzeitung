<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'comments';
$this->data['Install']['version'] = 0.1.0;
$this->Install->save($this->data); 
*/

$sql[] = "CREATE TABLE `conversation_users` (\n"
    . " `id` int(11) unsigned NOT NULL AUTO_INCREMENT,\n"
    . " `conversation_id` int(11) NOT NULL,\n"
    . " `user_id` int(11) NOT NULL,\n"
    . " `status` tinyint(2) unsigned NOT NULL,\n"
    . " `last_viewed_message` int(11) unsigned NOT NULL,\n"
    . " `created` datetime NOT NULL,\n"
    . " PRIMARY KEY (`id`),\n"
    . " KEY `user_id` (`user_id`),\n"
    . " KEY `conversation_id` (`conversation_id`)\n"
    . ") ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;";

//**** !2nd param is IMPORTANT! ****
$log = 'new table conversation_users';




?>