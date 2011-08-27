<?php
$link = '/login';
if($session->read('Auth.User.id')){
    $link = '#';
}
?>
<ul>
<li><a href="<?php echo $link; ?>" class="btn subscribe-user" id="<?php echo $user['User']['id']; ?>"><span>+</span><?php __('Subscribe'); ?></a></li>
<?php if($user['User']['id'] != $session->read('Auth.User.id')): // - cannot send a message to himself ?>
	<?php if($user['User']['allow_messages']):?>
		<li>
            <a href="#" class="btn gray new-conversation"><span class="send-icon"></span><?php __('Send Message'); ?></a>
        </li>
	<?php endif;?>
	<?php else:?>
		<?php if($this->params['controller'] == 'users' && $this->params['action'] == 'viewSubscriptions'): ?>
			<li><?php echo $this->Html->link('<span>+</span>'.__('New Paper', true), array('controller' => 'papers',  'action' => 'add'), array('escape' => false, 'class' => 'btn', ));?></li>
		<?php endif; ?>
<?php endif; ?>
</ul>
<hr />
