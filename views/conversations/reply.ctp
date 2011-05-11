<div class="reply form">
<?php echo $this->Form->create('Conversation');?>
	<fieldset>
 		<legend><?php __('Reply'); ?></legend>
	<?php
		echo $this->Form->hidden('user_id',array('value' => $user_id));
		echo $this->Form->hidden('id',array('value' => $conversation_id));
		echo $this->Form->input('message');

	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
