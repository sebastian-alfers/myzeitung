<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'comments';
$this->data['Install']['version'] = 0.1.1;
$this->Install->save($this->data); 
*/

$sql[] = "ALTER TABLE `users` ADD `paper_count` INT NOT NULL AFTER `comment_count`";

//**** !2nd param is IMPORTANT! ****
$log = 'content counter';




?>
