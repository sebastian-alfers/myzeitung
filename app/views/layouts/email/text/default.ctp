<?php echo "myZeitung.de"."\n";?>
<?php echo $content_for_layout;?>
<?php echo "\n\n";?>
<?php echo sprintf(__('This message was sent to %s.' ,true),$recipient['User']['email']);?>
<?php echo __('If you don\'t want to receive these emails from myZeitung, you can unsubscribe emails here:' )."\n";?>
<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'accPrivacy'), true);?>
