<div class="conversation form">
<?php echo $this->Form->create('Conversation');?>
	<fieldset>
 		<legend><?php __('New Message'); ?></legend>
		<strong>To: <?php echo $recipent['User']['username']?>
		<?php if(!empty($recipent['User']['name'])) echo ' - '.$recipent['User']['name'];?></strong>
	<?php
		echo $this->Form->hidden('user_id',array('value' => $user_id));
		echo $this->Form->hidden('recipents', array('value' => $recipent['User']['id']));
		echo $this->Form->input('title');
		echo $this->Form->input('message');

	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
