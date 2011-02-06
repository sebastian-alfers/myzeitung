<div class="topics form">
<?php echo $this->Form->create('Topic');?>
	<fieldset>
 		<legend><?php __('Add Topic'); ?></legend>
	<?php
		echo $this->Form->hidden('user_id',array('value' => $user_id));
		echo $this->Form->input('name');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<?php echo $this->element('navigation'); ?>
</div>