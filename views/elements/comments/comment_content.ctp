<p class="user-info">
		<?php
		//$info = $image->resize(['image'],65, 65, null, true);
		//echo $this->Html->link($this->Html->image($info['path']),array('controller' => 'users', 'action' => 'view', $current_comment['User']['id']),array('class' => "user-image", 'style' => $info['inline'], 'escape' => false));
		

		$user = $current_comment['User'];
		$img_data = $image->getImgPath($user['image']);
		if(is_array($img_data)){
							
			//debug($img_data);die();
			//found img in db
			$info = $image->resize($img_data['path'], 65, 65, $img_data['size'], true);
			$img = $this->Html->image($info['path'], array("alt" => $user['username'], 'style' => $info['inline']));
			echo $this->Html->link($img, array('controller' => 'users', 'action' => 'view', $user['id']), array('class' => "user-image", 'escape' => false, 'style' => 'overflow:hidden;height:65px;width:65px;'));
		}
		else{
			//not logged in
			$path = $image->resize($img_data, 65, 65, null, false);
			$img = $this->Html->image($path, array("alt" => $user['username']));							
			echo $this->Html->link($img, array('controller' => 'users', 'action' => 'view', $user['id']), array('class' => "user-image", 'escape' => false));
		}
		?>		
		
		
		
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