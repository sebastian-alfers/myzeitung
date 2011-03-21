<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'spaaace';
$this->data['Install']['version'] = 666;
$this->Install->save($this->data); 
*/

$sql[] = "ALTER TABLE `installs` ADD `performance1` VARCHAR(10) NOT NULL";

//**** !2nd param is IMPORTANT! ****
$log = 'added performance 0.1.1';




?>