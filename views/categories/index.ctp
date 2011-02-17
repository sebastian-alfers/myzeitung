<div class="categories index">
	<h2><?php __('Categories');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('Parent Category');?></th>
			<th><?php echo $this->Paginator->sort('paper_id');?></th>
			<th><?php echo $this->Paginator->sort('route_id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($categories as $category):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $category['Category']['id']; ?>&nbsp;</td>
		<td>
			<?php if(isset($category['Parent']['id'])): ?>
				<?php echo $category['Parent']['name']; ?>
			<?php else: ?>	
				-
			<?php endif; ?>
		</td>
		<td>
			<?php echo $this->Html->link($category['Paper']['title'], array('controller' => 'papers', 'action' => 'view', $category['Paper']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($category['Route']['id'], array('controller' => 'routes', 'action' => 'view', $category['Route']['id'])); ?>
		</td>
		<td><?php echo $category['Category']['name']; ?>&nbsp;</td>
		<td class="actions">
		
			<?php if($category['Category']['parent_id'] == 0) echo $this->Html->link(__('Add Subcategory', true), array('controller' => 'categories', 'action' => 'add', CategoriesController::PARAM_CATEGORY, $category['Category']['id'])); ?>
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $category['Category']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $category['Category']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $category['Category']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $category['Category']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Category', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Papers', true), array('controller' => 'papers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Paper', true), array('controller' => 'papers', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Routes', true), array('controller' => 'routes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Route', true), array('controller' => 'routes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Categories', true), array('controller' => 'categories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Child Category', true), array('controller' => 'categories', 'action' => 'add')); ?> </li>
	</ul>
</div>