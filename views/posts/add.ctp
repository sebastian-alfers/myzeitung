<div class="posts form">
<?php echo $this->Form->create('Post');?>
		
	
	<fieldset>
 		<legend><?php __('Add Post'); ?></legend>
	<?php
		echo $this->Form->input('topic_id');
		echo $this->Form->input('title');
		echo $this->Form->input('content');
		echo $this->Form->hidden('user_id',array('value' => $user_id));

	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<?php echo $this->element('navigation'); ?>	
</div>
