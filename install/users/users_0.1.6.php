<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'comments';
$this->data['Install']['version'] = 0.1.1;
$this->Install->save($this->data); 
*/

$sql[] = "ALTER TABLE `users` DROP `count_posts_reposts`, DROP `count_reposts`, DROP `count_comments`;";

//**** !2nd param is IMPORTANT! ****
$log = 'content counter';




?>
