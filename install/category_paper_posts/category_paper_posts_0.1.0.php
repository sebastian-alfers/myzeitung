<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'spaaace';
$this->data['Install']['version'] = 666;
$this->Install->save($this->data); 
*/

$sql[] = "ALTER TABLE `category_paper_posts` ADD `post_user_id` INT(11) NOT NULL AFTER `category_id`";

//**** !2nd param is IMPORTANT! ****
$log = 'new column to connect paper_index to post_user for undo repost';




?>