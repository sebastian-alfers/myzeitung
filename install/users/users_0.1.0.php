<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'comments';
$this->data['Install']['version'] = 0.1.0;
$this->Install->save($this->data); 
*/

$sql[] ="ALTER TABLE `users` ADD `image` VARCHAR(300) NULL DEFAULT NULL AFTER `password`";

//**** !2nd param is IMPORTANT! ****
$log = 'users have a new field: image';




?>
