<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'comments';
$this->data['Install']['version'] = 0.1.0;
$this->Install->save($this->data); 
*/

$sql[] = "ALTER TABLE `posts` ADD `comment_count` INT NOT NULL AFTER `reposters`, ADD `posts_user_count` INT NOT NULL AFTER `comment_count`";

//**** !2nd param is IMPORTANT! ****
$log = 'new counters';




?>