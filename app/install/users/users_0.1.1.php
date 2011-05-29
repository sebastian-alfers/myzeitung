<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'comments';
$this->data['Install']['version'] = 0.1.1;
$this->Install->save($this->data); 
*/

$sql[] = "ALTER TABLE `users` ADD `subscription_count` INT NOT NULL  AFTER `count_comments`, ADD `content_paper_count` INT NOT NULL  AFTER `subscription_count`";

//**** !2nd param is IMPORTANT! ****
$log = 'counters for subscriptions and being-subscribed -> content_paper';




?>
