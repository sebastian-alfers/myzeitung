<div class="helppages index">
	<h2><?php __('Helppages');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('controller');?></th>
			<th><?php echo $this->Paginator->sort('action');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($helppages as $helppage):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $helppage['Helppage']['id']; ?>&nbsp;</td>
		<td><?php echo $helppage['Helppage']['controller']; ?>&nbsp;</td>
		<td><?php echo $helppage['Helppage']['action']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $helppage['Helppage']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $helppage['Helppage']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $helppage['Helppage']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $helppage['Helppage']['id'])); ?>
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
        <?php if($is_superadmin): ?>
		    <li><?php echo $this->Html->link(__('New Helppage', true), array('action' => 'add')); ?></li>
        <?php endif; ?>
	</ul>
</div>