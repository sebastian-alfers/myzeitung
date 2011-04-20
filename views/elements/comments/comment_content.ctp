<p class="user-info">
		<?php echo $this->Html->link($this->Html->image($image->resize($current_comment['User']['image'],65, 65, true)),array('controller' => 'users', 'action' => 'view', $current_comment['User']['id']),array('class' => "user-image", 'escape' => false));?>
		<?php echo $this->Html->link($current_comment['User']['username'],array('controller' => 'users', 'action' => 'view', $current_comment['User']['id']));?>
		<?php echo $this->Time->timeAgoInWords($current_comment['Comment']['created'], array('end' => '+1 Year'));?><br />
</p>
	<p class="content">
	<span class="info">kommentar</span>
	<?php echo $current_comment['Comment']['text'];?>
	</p>
	<?php echo $this->Html->link('<span class="reply-icon"></span>'.__('Reply', true), array('controller' => 'comments', 'action' => 'add', $current_comment['Comment']['post_id'],$current_comment['Comment']['reply_id']), array('escape' => false, 'class' => 'btn', ));?>