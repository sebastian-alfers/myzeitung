<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'spaaace';
$this->data['Install']['version'] = 666;
$this->Install->save($this->data); 
*/

$sql = "ALTER TABLE `installs` ADD `performance1` VARCHAR(10) NOT NULL AFTER `performance`";

//**** !2nd param is IMPORTANT! ****
$this->_run($sql, 'added performance 0.1.1');




?>