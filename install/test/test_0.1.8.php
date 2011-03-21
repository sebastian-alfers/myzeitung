<?php 
echo 'run test 1.5';
/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'spaaace';
$this->data['Install']['version'] = 666;
$this->Install->save($this->data); 
*/

$sql[] = "ALTER TABLE `installs` ADD `ttttesttt` VARCHAR(10) NOT NULL";

//**** !2nd param is IMPORTANT! ****
$log = 'added new column asdftestmfdafy to table installs';




?>