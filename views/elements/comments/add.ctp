	<div class="your-comment">
		<h3><?php echo __('Leave a comment')?></h3>
		<p class="user-info">
			<?php echo $this->Html->link($this->Html->image($image->resize($session->read('Auth.User.image'), 65, 65, true)), array('controller' => 'users', 'action' => 'view', $session->read('Auth.User.id')), array('escape' => false));?>
			<?php echo $this->Html->link($session->read('Auth.User.username'), array('controller' => 'users', 'action' => 'view', $session->read('Auth.User.id')));?>
		</p>
		
		<form action="" class="leave-comment">
			<label>Kommentar</label>
			<textarea rows="5" cols="10" ></textarea>
			<a class="btn" ><span>+</span><?php echo __('Post comment', true);?></a>
		</form>
	</div> <!-- / .your-comment -->