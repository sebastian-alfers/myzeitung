<?php
 $invitee_name = $invitee['User']['username'];
    if(!empty($invitee['User']['name'])){
        $invitee_name .= ' - '.$invitee['User']['name'];
    }

 $invitee_link = $this->Html->url(array('controller' => 'users', 'action' => 'view', 'username' => strtolower($invitee['User']['username'])),true);
?>

<?php echo __('Your invitee has just registered',true)."\n\n";?>
<?php echo sprintf(__('You have sent a invitation to the email address %s.',true), $invitee['User']['email'] )."\n"?>
<?php echo sprintf(__('A user named %s has just registered with that email address.',true), $invitee_name)."\n"?>
<?php echo sprintf(__('Use the following link to get directly to the profile of the invitee: %s',true), $invitee_link)."\n"?>