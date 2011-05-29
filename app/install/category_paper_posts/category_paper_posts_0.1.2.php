<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'spaaace';
$this->data['Install']['version'] = 666;
$this->Install->save($this->data); 
*/

$sql[] = "ALTER TABLE `category_paper_posts` ADD `reposter_id` INT NULL DEFAULT NULL AFTER `content_paper_id`";

//**** !2nd param is IMPORTANT! ****
$log = 'new field: reposter_id - if the posts_users entry was a repost, this is the id of the reposting user';




?>