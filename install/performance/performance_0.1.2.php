<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'spaaace';
$this->data['Install']['version'] = 666;
$this->Install->save($this->data); 
*/

$sql = "ALTER TABLE `installs` ADD `performance2` VARCHAR(10) NOT NULL AFTER `performance1`";


//**** !2nd param is IMPORTANT! ****
$this->_run($sql, 'performanace 0.1.2');




?>