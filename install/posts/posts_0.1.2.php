<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'comments';
$this->data['Install']['version'] = 0.1.0;
$this->Install->save($this->data); 
*/

$sql[] = "ALTER TABLE `posts` DROP `count_views`";


//**** !2nd param is IMPORTANT! ****
$log = 'new name for counter';




?>