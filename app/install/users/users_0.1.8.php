<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'comments';
$this->data['Install']['version'] = 0.1.1;
$this->Install->save($this->data); 
*/

$sql[] = "ALTER TABLE `users` ADD `description` VARCHAR(300) NOT NULL AFTER `name`";

//**** !2nd param is IMPORTANT! ****
$log = 'description for users (bio)';




?>
