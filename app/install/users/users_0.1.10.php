<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'comments';
$this->data['Install']['version'] = 0.1.1;
$this->Install->save($this->data); 
*/

$sql[] = "ALTER TABLE  `users` CHANGE  `image`  `image` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL";

//**** !2nd param is IMPORTANT! ****
$log = 'bigger field for image';




?>
