<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'spaaace';
$this->data['Install']['version'] = 666;
$this->Install->save($this->data); 
*/

$sql[] = "ALTER TABLE `category_paper_posts` ADD `content_paper_id` INT(11) NOT NULL AFTER `post_user_id`";

//**** !2nd param is IMPORTANT! ****
$log = 'new column to connect paper_index to content_papers table to delete all posts created by that association, while deleting that association';




?>