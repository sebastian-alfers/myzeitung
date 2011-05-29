<div class="comments form">
<?php echo $this->Form->create('Comment');?>
	<fieldset>
 		<legend><?php __('Add Comment'); ?></legend>
	<?php
		echo $this->Form->hidden('user_id',array('value' => $user_id));
		echo $this->Form->hidden('post_id',array('value' => $post_id));
		echo $this->Form->hidden('parent_id',array('value' => $parent_id));
		echo $this->Form->input('text');

	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
