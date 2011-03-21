<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'spaaace';
$this->data['Install']['version'] = 666;
$this->Install->save($this->data); 
*/

$sql[] = "ALTER TABLE `installs` ADD `performancemontag` VARCHAR(10) NOT NULL";

//**** !IMPORTANT! ****
$log = 'added new column version to table installs 010';




?>