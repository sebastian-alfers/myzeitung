<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'categories';
$this->data['Install']['version'] = 0.1.0;
$this->Install->save($this->data); 
*/

$sql[] = "ALTER TABLE `categories` ADD `content_paper_count` INT NOT NULL AFTER `modified`";

//**** !2nd param is IMPORTANT! ****
$log = 'content counter';




?>