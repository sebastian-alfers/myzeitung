<div class="conversation form">
<?php echo $this->Form->create('Conversation');?>
	<fieldset>
 		<legend><?php __('New Message'); ?></legend>
		<strong>To: <?php echo $recipient['User']['username']?>
		<?php if(!empty($recipient['User']['name'])) echo ' - '.$recipient['User']['name'];?></strong>
	<?php
		echo $this->Form->hidden('user_id',array('value' => $user_id));
		echo $this->Form->hidden('recipients', array('value' => $recipient['User']['id']));
		echo $this->Form->input('title');
		echo $this->Form->input('message');

	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
