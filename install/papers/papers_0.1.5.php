<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'comments';
$this->data['Install']['version'] = 0.1.0;
$this->Install->save($this->data); 
*/

$sql[] = "ALTER TABLE  `papers` CHANGE  `image`  `image` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL";

//**** !2nd param is IMPORTANT! ****
$log = 'new subscription counter';




?>