<?php if($user['User']['id'] != $session->read('Auth.User.id')): //can not subscribe to himself - cannot send a message to himself ?>
	<?php echo $this->Html->link('<span>+</span>'.__('Subscribe', true), array('controller' => 'users',  'action' => 'subscribe', $user['User']['id']), array('escape' => false, 'class' => 'btn', ));?>
	<?php if($user['User']['allow_messages']):?>
		<?php echo $this->Html->link('<span class="send-icon"></span>'.__('Send Message', true), array('controller' => 'conversations', 'action' => 'add', $user['User']['id']), array('escape' => false, 'class' => 'btn btn-send', ));?>
	<?php endif;?>
<?php endif; ?>