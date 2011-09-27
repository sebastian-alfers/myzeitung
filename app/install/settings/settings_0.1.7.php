<?php
$sql[] = "INSERT INTO `myzeitung`.`settings` (`id`, `model_type`, `model_id`, `namespace`, `key`, `value`, `value_data_type`, `note`, `created`, `modified`) VALUES (NULL, 'user', NULL, 'default', 'allow_comments', '1', 'yes_no', NULL, '2011-09-26 09:45:53', '2011-09-26 09:45:53'), (NULL, 'user', NULL, 'default', 'allow_messages', '1', 'yes_no', NULL, '2011-09-26 09:45:53', '2011-09-26 09:45:53');";

$sql[] = "INSERT INTO `myzeitung`.`settings` (`id`, `model_type`, `model_id`, `namespace`, `key`, `value`, `value_data_type`, `note`, `created`, `modified`) VALUES (NULL, 'user', NULL, 'email', 'invitee_registered', '1', 'yes_no', NULL, '2011-09-26 09:45:53', '2011-09-26 09:45:53'), (NULL, 'user', NULL, 'email', 'new_comment', '1', 'yes_no', NULL, '2011-09-26 09:45:53', '2011-09-26 09:45:53');";

$sql[] = "INSERT INTO `myzeitung`.`settings` (`id`, `model_type`, `model_id`, `namespace`, `key`, `value`, `value_data_type`, `note`, `created`, `modified`) VALUES (NULL, 'user', NULL, 'email', 'new_message', '1', 'yes_no', NULL, '2011-09-26 09:45:53', '2011-09-26 09:45:53'), (NULL, 'user', NULL, 'email', 'subscription', '1', 'yes_no', NULL, '2011-09-26 09:45:53', '2011-09-26 09:45:53');";

//**** !2nd param is IMPORTANT! ****
$log = 'Test for install on shell script';

?>



