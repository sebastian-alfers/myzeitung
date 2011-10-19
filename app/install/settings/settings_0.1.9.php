<?php
$sql[] = "INSERT INTO `myzeitung`.`settings` (`id`, `model_type`, `model_id`, `namespace`, `key`, `value`, `value_data_type`, `note`, `created`, `modified`) VALUES (NULL,  'user', NULL,  'twitter',  'oauth_token',  '',  'string', NULL,  NOW(),  NOW());";
$sql[] = "INSERT INTO `myzeitung`.`settings` (`id`, `model_type`, `model_id`, `namespace`, `key`, `value`, `value_data_type`, `note`, `created`, `modified`) VALUES (NULL,  'user', NULL,  'twitter',  'oauth_token_secret',  '',  'string', NULL,  NOW(),  NOW());";


//**** !2nd param is IMPORTANT! ****
$log = 'token to connect to twitter';

?>



