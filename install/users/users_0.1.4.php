<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'comments';
$this->data['Install']['version'] = 0.1.1;
$this->Install->save($this->data); 
*/

$sql[] = "ALTER TABLE `users` ADD `post_count` INT NOT NULL AFTER `content_paper_count`, ADD `posts_user_count` INT NOT NULL AFTER `post_count`";

//**** !2nd param is IMPORTANT! ****
$log = 'content counter';




?>
