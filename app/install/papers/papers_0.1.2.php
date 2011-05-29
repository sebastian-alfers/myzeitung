<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'paper';
$this->data['Install']['version'] = 0.1.2;
$this->Install->save($this->data); 
*/

$sql[] = "ALTER TABLE `papers` ADD `content_paper_count` INT NOT NULL AFTER `subscription_count`";

//**** !2nd param is IMPORTANT! ****
$log = 'content counter';




?>