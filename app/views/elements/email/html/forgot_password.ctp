<p style='color:#232424;'>
<?php echo __("Your new password: ")." ".$password; ?>
</p>
<p style='color:#232424;'>
    <?php echo __("Login and change your password:", true)." ".$this->Html->url(array('controller' => 'users', 'action' => 'accGeneral'), true);?>
</p>