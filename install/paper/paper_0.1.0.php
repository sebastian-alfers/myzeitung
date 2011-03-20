<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'spaaace';
$this->data['Install']['version'] = 666;
$this->Install->save($this->data); 
*/

$sql = "ALTER TABLE `installs` ADD `test010` VARCHAR(10) NOT NULL AFTER `test01`";

//**** !2nd param is IMPORTANT! ****
$this->_run($sql, 'added new column version to table installs 010');




?>