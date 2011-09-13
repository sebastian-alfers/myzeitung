<div class="helpelements form">
<?php echo $this->Form->create('Helpelement');?>
	<fieldset>
		<legend><?php __('Admin Edit Helpelement'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('description');
		echo $this->Form->input('page_id');
		echo $this->Form->input('accessor');
		echo $this->Form->input('deu');
		echo $this->Form->input('eng');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Helpelement.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Helpelement.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Helpelements', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Helppages', true), array('controller' => 'helppages', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Page', true), array('controller' => 'helppages', 'action' => 'add')); ?> </li>
	</ul>
</div>