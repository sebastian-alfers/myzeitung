<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'spaaace';
$this->data['Install']['version'] = 666;
$this->Install->save($this->data); 
*/

$sql[] = "ALTER TABLE `category_paper_posts` ADD `reposter_username` VARCHAR(64) NULL AFTER `reposter_id`";

//**** !2nd param is IMPORTANT! ****
$log = 'new field: reposter_username';




?>