<p class="user-info">
		<?php echo $this->Html->link($this->Html->image($image->resize($current_comment['User']['image'],65, 65, true)),array('controller' => 'users', 'action' => 'view', $current_comment['User']['id']),array('class' => "user-image", 'escape' => false));?>
		<?php echo $this->Html->link($current_comment['User']['username'],array('controller' => 'users', 'action' => 'view', $current_comment['User']['id']));?>
		<?php echo $this->Time->timeAgoInWords($current_comment['Comment']['created'], array('end' => '+1 Year'));?><br />
</p>
	<p class="content">
	<span class="info">kommentar</span>
	<?php echo nl2br($current_comment['Comment']['text']); ?>
	</p>
	<?php 
		$comment_user_id = $current_comment['Comment']['user_id'];
		$logged_in_user_id = $session->read('Auth.User.id');
		
		if($comment_user_id == $logged_in_user_id): ?>
			<?php echo $this->Html->link('<span class="reply-icon"></span>'. __('Remove', true), array('controller' => 'comments', 'action' => 'delete', $current_comment['Comment']['id']), array('escape' => false, 'class' => 'btn', ));?>
		<?php endif; ?>
	
<?php /*
	<a class="reply btn"><span class="reply-icon"></span><?php __('Reply'); ?></a>
	
	*/ ?>