<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
 		<legend><?php __('Edit User'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('group_id');
		echo $this->Form->input('email');
		//echo $this->Form->input('password' , array('value', ''));
		echo $this->Form->input('firstname');
		echo $this->Form->input('name');

	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?> asdf</h3>
	<?php echo $this->element('navigation'); ?>	
</div>