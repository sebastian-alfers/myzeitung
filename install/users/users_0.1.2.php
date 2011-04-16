<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'comments';
$this->data['Install']['version'] = 0.1.1;
$this->Install->save($this->data); 
*/

$sql[] = "ALTER TABLE `users` CHANGE `image` `image` VARCHAR(300) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";

//**** !2nd param is IMPORTANT! ****
$log = 'image not null';




?>
