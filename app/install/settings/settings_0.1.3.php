<?php
$sql[] = "INSERT INTO `settings` (`id`, `model_type`, `model_id`, `namespace`, `key`, `value`, `value_data_type`, `note`, `created`, `modified`) VALUES
(3, NULL, NULL, 'Core', 'Cache.disable', '0', 'yes_no', 'Turn off all caching application-wide.', NOW(), NOW()),
(4, NULL, NULL, 'Core', 'Cache.check', '0', 'yes_no', 'Enable cache checking.\r\n\r\nIf set to true, for view caching you must still use the controller var cacheAction inside your controllers to define caching settings. You can either set it controller-wide by setting var cacheAction = true, or in each action using this->cacheAction = true.\r\n', NOW(), NOW()),
(9, NULL, NULL, 'Core', 'debug', '2', 'debug_level_chooser', 'Production Mode:\r\n0: No error messages, errors, or warnings shown. Flash messages redirect.\r\n\r\nDevelopment Mode:\r\n1: Errors and warnings shown, model caches refreshed, flash messages halted.\r\n2: As in 1, but also with full debug messages and SQL output.', NOW(), NOW()),
(10, NULL, NULL, 'Core', 'Memache.enable', '1', 'yes_no', NULL, NOW(), NOW()),
(11, NULL, NULL, 'Core', 'Session.save', 'database', 'session_save_chooser', NULL, NOW(), NOW()),
(12, NULL, NULL, 'Core', 'Cdn.enable', '0', 'yes_no', 'use AWS Cloudfront for static content (images, js, css) ', NOW(), NOW()),
(13, NULL, NULL, 'Core', 'Solr.enable', '1', 'yes_no', NULL, NOW(), NOW()),
(14, NULL, NULL, 'Core', 'Solr.port', '8080', 'string', NULL, NOW(), NOW()),
(15, NULL, NULL, 'Core', 'Solr.host', 'localhost', 'string', NULL, NOW(), NOW()),
(16, NULL, NULL, 'Core', 'Frontend.disable_combine_css', '1', 'yes_no', 'yes: do NOT combine css into one file', NOW(), NOW()),
(17, NULL, NULL, 'Core', 'Frontend.disable_combine_js', '1', 'yes_no', 'yes: do NOT combine js into one file', NOW(), NOW());";


//**** !2nd param is IMPORTANT! ****
$log = 'install basic conf';

?>



