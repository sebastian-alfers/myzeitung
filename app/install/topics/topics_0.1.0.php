<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'topics';
$this->data['Install']['version'] = 0.1.0;
$this->Install->save($this->data); 
*/

$sql[] = "ALTER TABLE `topics` ADD `content_paper_count` INT NOT NULL AFTER `enabled`";

//**** !2nd param is IMPORTANT! ****
$log = 'content counter';




?>