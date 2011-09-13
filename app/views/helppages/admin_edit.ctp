<div class="helppages form">
<?php echo $this->Form->create('Helppage');?>
	<fieldset>
		<legend><?php __('Admin Edit Helppage'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('controller');
		echo $this->Form->input('action');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Helppage.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Helppage.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Helppages', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Helpelements', true), array('controller' => 'helpelements', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Helpelement', true), array('controller' => 'helpelements', 'action' => 'add')); ?> </li>
	</ul>
</div>