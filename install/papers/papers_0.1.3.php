<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'paper';
$this->data['Install']['version'] = 0.1.2;
$this->Install->save($this->data); 
*/

$sql[] = "ALTER TABLE `papers` ADD `category_paper_post_count` INT NOT NULL AFTER `content_paper_count`";

//**** !2nd param is IMPORTANT! ****
$log = 'post counter - called category paper post count';




?>