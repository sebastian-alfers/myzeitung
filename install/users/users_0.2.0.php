<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'comments';
$this->data['Install']['version'] = 0.1.1;
$this->Install->save($this->data); 
*/

$sql[] = "ALTER TABLE `users` ADD `allow_messages` TINYINT NOT NULL DEFAULT '1' AFTER `enabled`, ADD `allow_comments` TINYINT NOT NULL DEFAULT '1' AFTER `allow_messages`";

//**** !2nd param is IMPORTANT! ****
$log = 'added settings for allow comments / messages';




?>
