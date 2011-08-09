<?php echo __("Your new password: ")." ".$password."\n"; ?>
<?php echo __("Login and change your password:", true)." ".$this->Html->url(array('controller' => 'users', 'action' => 'accGeneral'), true);?>
