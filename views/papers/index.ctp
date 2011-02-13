<div class="papers index">
	<h2><?php __('Papers');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('route_id');?></th>
			<th><?php echo $this->Paginator->sort('owner_id');?></th>
			<th><?php echo $this->Paginator->sort('title');?></th>
			<th><?php echo $this->Paginator->sort('description');?></th>
			<th><?php echo __('Categories'); ?></td>
			<th><?php echo $this->Paginator->sort('url');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php

	$i = 0;	
	foreach ($papers as $paper):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $paper['Paper']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($paper['Route']['source'], $paper['Route']['source']); ?>
		</td>
		<td><?php echo $paper['User']['firstname'] . ' ' .$paper['User']['name']; ?>&nbsp;</td>
		<td><?php echo $paper['Paper']['title']; ?>&nbsp;</td>
		<td><?php echo $paper['Paper']['description']; ?>&nbsp;</td>
		<td><?php  debug($paper); ?></td>
		<td><?php echo $paper['Paper']['url']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Add Category', true), array('controller' => 'categories', 'action' => 'add', $paper['Paper']['id'])); ?>		
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $paper['Paper']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $paper['Paper']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $paper['Paper']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $paper['Paper']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Paper', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Routes', true), array('controller' => 'routes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Route', true), array('controller' => 'routes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Categories', true), array('controller' => 'categories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Category', true), array('controller' => 'categories', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Subscriptions', true), array('controller' => 'subscriptions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Subscription', true), array('controller' => 'subscriptions', 'action' => 'add')); ?> </li>
	</ul>
</div>