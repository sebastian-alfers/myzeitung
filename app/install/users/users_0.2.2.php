<?php

$sql[] = "ALTER TABLE `subscriptions` ADD `enabled` TINYINT(1) NOT NULL DEFAULT '1'";

//**** !2nd param is IMPORTANT! ****
$log = 'new field enabled for subscriptions (namespace subscriptions did not work)';




?>




