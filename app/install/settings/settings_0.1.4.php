<?php
$sql[] = "INSERT INTO `settings` (`model_type`, `model_id`, `namespace`, `key`, `value`, `value_data_type`, `note`, `created`, `modified`) VALUES
(NULL, NULL, 'Core', 'GoogleAnalytics.enable', '0', 'yes_no', 'Track google analytics', NOW(), NOW()),
(NULL, NULL, 'Core', 'GoogleAnalytics.account', '0', 'string', '', NOW(), NOW()),
(NULL, NULL, 'Core', 'Cache.save_handler', 'file', 'cache_save_handler_chooser', '', NOW(), NOW()),
(NULL, NULL, 'Core', 'Memcache.host', '127.0.0.1', 'string', '', NOW(), NOW()),
(NULL, NULL, 'Core', 'Memcache.port', '11211', 'string', '', NOW(), NOW());";

$sql[] = "UPDATE `settings` set `settings`.`key` = 'Memcache.enable' where `key` = 'Memache.enable';";
$sql[] = "ALTER TABLE `settings` CHANGE `value_data_type` `value_data_type` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'string'";
$sql[] = "ALTER TABLE `settings` CHANGE `namespace` `namespace` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL";

//**** !2nd param is IMPORTANT! ****
$log = 'GA configs, memcache configs';

?>



