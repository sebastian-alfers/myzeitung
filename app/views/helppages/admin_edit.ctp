<div class="helppages form">
<?php echo $this->Form->create('Helppage');?>
	<fieldset>
		<legend><?php __('Admin Edit Helppage'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('controller');
		echo $this->Form->input('action');
        echo $this->Form->input('deu');
        echo $this->Form->input('eng');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
