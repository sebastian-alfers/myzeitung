<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'comments';
$this->data['Install']['version'] = 0.1.0;
$this->Install->save($this->data); 
*/

$sql[] = "ALTER TABLE `content_papers` CHANGE `user_id` `user_id` INT(11) NOT NULL";


//**** !2nd param is IMPORTANT! ****
$log = 'user_id can not be null';




?>