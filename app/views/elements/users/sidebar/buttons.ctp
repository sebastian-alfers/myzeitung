<a href="#" class="btn subscribe-user" id="<?php echo $user['User']['id']; ?>"><span>+</span><?php __('Subscribe'); ?></a>
<?php if($user['User']['id'] != $session->read('Auth.User.id')): // - cannot send a message to himself ?>
	<?php if($user['User']['allow_messages']):?>

		<?php echo $this->Html->link('<span class="send-icon"></span>'.__('Send Message', true), array('controller' => 'conversations', 'action' => 'add', $user['User']['id']), array('escape' => false, 'class' => 'btn btn-send', ));?>
	<?php endif;?>
	<?php else:?>
		<?php if($this->params['controller'] == 'users' && $this->params['action'] == 'viewSubscriptions'): ?>
			<?php echo $this->Html->link('<span>+</span>'.__('New Paper', true), array('controller' => 'papers',  'action' => 'add'), array('escape' => false, 'class' => 'btn', ));?>
		<?php endif; ?>
<?php endif; ?>
