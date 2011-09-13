<div class="helpelements index">
	<h2><?php __('Helpelements');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('description');?></th>
			<th><?php echo $this->Paginator->sort('page_id');?></th>
			<th><?php echo $this->Paginator->sort('accessor');?></th>
			<th><?php echo $this->Paginator->sort('deu');?></th>
			<th><?php echo $this->Paginator->sort('eng');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($helpelements as $helpelement):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $helpelement['Helpelement']['id']; ?>&nbsp;</td>
		<td><?php echo $helpelement['Helpelement']['description']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($helpelement['Page']['id'], array('controller' => 'helppages', 'action' => 'view', $helpelement['Page']['id'])); ?>
		</td>
		<td><?php echo $helpelement['Helpelement']['accessor']; ?>&nbsp;</td>
		<td><?php echo $helpelement['Helpelement']['deu']; ?>&nbsp;</td>
		<td><?php echo $helpelement['Helpelement']['eng']; ?>&nbsp;</td>
		<td><?php echo $helpelement['Helpelement']['created']; ?>&nbsp;</td>
		<td><?php echo $helpelement['Helpelement']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $helpelement['Helpelement']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $helpelement['Helpelement']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $helpelement['Helpelement']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $helpelement['Helpelement']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Helpelement', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Helppages', true), array('controller' => 'helppages', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Page', true), array('controller' => 'helppages', 'action' => 'add')); ?> </li>
	</ul>
</div>