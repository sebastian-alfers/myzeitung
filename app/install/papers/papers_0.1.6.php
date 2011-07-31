<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'comments';
$this->data['Install']['version'] = 0.1.0;
$this->Install->save($this->data); 
*/

$sql[] = "ALTER TABLE `papers` ADD `enabled` TINYINT(1) NOT NULL DEFAULT '1' AFTER `modified`";


//**** !2nd param is IMPORTANT! ****
$log = 'new field enabled';




?>