<?php 

/* example
$this->Install->create();
$this->data['Install']['namespace'] = 'comments';
$this->data['Install']['version'] = 0.1.0;
$this->Install->save($this->data); 
*/

$sql[] = "ALTER TABLE `papers` DROP `count_subscriptions`";

//**** !2nd param is IMPORTANT! ****
$log = 'new subscription counter';




?>