	<div class="your-comment">
		<h3><?php echo __('Leave comment')?></h3>
		<p class="user-info">
			<?php echo $this->Html->link($this->Html->image($image->resize($user['User']['image'], 65, 65, true)), array('controller' => 'users', 'action' => 'view'), array('escape' => false));?>
			<?php echo $this->Html->link($user['User']['username'], array('controller' => 'users', 'action' => 'view'));?>
		</p>
		
		<form action="" class="leave-comment">
			<label>Kommentar</label>
			<textarea rows="5" cols="10" ></textarea>
			<a class="btn" ><span>+</span><?php echo __('Post comment', true);?></a>
		</form>
	</div> <!-- / .your-comment -->