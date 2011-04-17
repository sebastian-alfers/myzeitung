<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'topics';
$this->data['Install']['version'] = 0.1.0;
$this->Install->save($this->data); 
*/

$sql[] = "ALTER TABLE `topics` ADD `post_count` INT NOT NULL AFTER `content_paper_count`";

//**** !2nd param is IMPORTANT! ****
$log = 'post_count';




?>