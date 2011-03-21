<?php 
echo 'run test 1.0';
/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'spaaace';
$this->data['Install']['version'] = 666;
$this->Install->save($this->data); 
*/

$sql[] = "ALTER TABLE `installs` ADD `hhhtestaaamy` VARCHAR(10) NOT NULL";

//**** !2nd param is IMPORTANT! ****
$log = 'added new column hhhtestaaamy to table installs';




?>