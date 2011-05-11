<div class="conversation form">
<?php echo $this->Form->create('Conversation');?>
	<fieldset>
 		<legend><?php __('New Message'); ?></legend>
	<?php
		echo $this->Form->hidden('user_id',array('value' => $user_id));
		echo $this->Form->input('recipents');
		echo $this->Form->input('title');
		echo $this->Form->input('message');

	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
