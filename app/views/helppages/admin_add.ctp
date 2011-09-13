<div class="helppages form">
<?php echo $this->Form->create('Helppage');?>
	<fieldset>
		<legend><?php __('Admin Add Helppage'); ?></legend>
	<?php
		echo $this->Form->input('controller');
		echo $this->Form->input('action');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Helppages', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Helpelements', true), array('controller' => 'helpelements', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Helpelement', true), array('controller' => 'helpelements', 'action' => 'add')); ?> </li>
	</ul>
</div>