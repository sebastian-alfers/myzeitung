<div class="papers form">
<?php echo $this->Form->create('Paper');?>
	<fieldset>
 		<legend><?php __('Edit Paper'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('route_id');
		echo $this->Form->input('owner_id');
		echo $this->Form->input('title');
		echo $this->Form->input('description');
		echo $this->Form->input('url');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Paper.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Paper.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Papers', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Routes', true), array('controller' => 'routes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Route', true), array('controller' => 'routes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Categories', true), array('controller' => 'categories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Category', true), array('controller' => 'categories', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Subscriptions', true), array('controller' => 'subscriptions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Subscription', true), array('controller' => 'subscriptions', 'action' => 'add')); ?> </li>
	</ul>
</div>