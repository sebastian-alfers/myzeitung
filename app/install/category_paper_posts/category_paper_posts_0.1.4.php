<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'spaaace';
$this->data['Install']['version'] = 666;
$this->Install->save($this->data); 
*/

$sql[] = "ALTER TABLE `category_paper_posts` CHANGE `category_id` `category_id` INT(11) NULL DEFAULT NULL";

//**** !2nd param is IMPORTANT! ****
$log = 'category_id default null now';




?>