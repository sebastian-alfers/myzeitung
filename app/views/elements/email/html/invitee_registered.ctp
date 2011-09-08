<?php
 $invitee_name = $invitee['User']['username'];
    if(!empty($invitee['User']['name'])){
        $invitee_name .= ' - '.$invitee['User']['name'];
    }

 $invitee_link = $this->Html->link($invitee_name, $this->Html->url(array('controller' => 'users', 'action' => 'view', 'username' => strtolower($invitee['User']['username'])),true), array('style' => 'color:#232424;'));
?>

<p style='color:#232424;'><strong><?php echo __('Your invitee has just registered',true);?></strong></p>
<p style='color:#232424;'><?php echo sprintf(__('You have sent a invitation to the email address %s.',true), $invitee['User']['email'] )?></p>
<p style='color:#232424;'><?php echo sprintf(__('A user named %s has just registered with that email address.',true), $invitee_link)?></p>