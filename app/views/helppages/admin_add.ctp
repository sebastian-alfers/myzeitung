<div class="helppages form">
<?php echo $this->Form->create('Helppage');?>
	<fieldset>
		<legend><?php __('Admin Add Helppage'); ?></legend>
	<?php
		echo $this->Form->input('controller');
		echo $this->Form->input('action');
        echo $this->Form->input('description');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>