<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'paper';
$this->data['Install']['version'] = 0.1.2;
$this->Install->save($this->data); 
*/

$sql[] = "ALTER TABLE `papers` ADD `image` VARCHAR(300) NOT NULL AFTER `url`";

//**** !2nd param is IMPORTANT! ****
$log = 'added image';




?>