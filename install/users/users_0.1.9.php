<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'comments';
$this->data['Install']['version'] = 0.1.1;
$this->Install->save($this->data); 
*/

$sql[] = "ALTER TABLE `users` DROP `firstname`";

//**** !2nd param is IMPORTANT! ****
$log = 'dropped firstname';




?>
