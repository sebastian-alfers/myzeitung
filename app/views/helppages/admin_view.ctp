<div class="helppages view">
<h2><?php  __('Helppage');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $helppage['Helppage']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Controller'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $helppage['Helppage']['controller']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Action'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $helppage['Helppage']['action']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Helppage', true), array('action' => 'edit', $helppage['Helppage']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Helppage', true), array('action' => 'delete', $helppage['Helppage']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $helppage['Helppage']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Helppages', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Helppage', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Helpelements', true), array('controller' => 'helpelements', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Helpelement', true), array('controller' => 'helpelements', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php __('Related Helpelements');?></h3>
	<?php if (!empty($helppage['Helpelement'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Description'); ?></th>
		<th><?php __('Page Id'); ?></th>
		<th><?php __('Accessor'); ?></th>
		<th><?php __('Deu'); ?></th>
		<th><?php __('Eng'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($helppage['Helpelement'] as $helpelement):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $helpelement['id'];?></td>
			<td><?php echo $helpelement['description'];?></td>
			<td><?php echo $helpelement['page_id'];?></td>
			<td><?php echo $helpelement['accessor'];?></td>
			<td><?php echo $helpelement['deu'];?></td>
			<td><?php echo $helpelement['eng'];?></td>
			<td><?php echo $helpelement['created'];?></td>
			<td><?php echo $helpelement['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'helpelements', 'action' => 'view', $helpelement['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'helpelements', 'action' => 'edit', $helpelement['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'helpelements', 'action' => 'delete', $helpelement['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $helpelement['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Helpelement', true), array('controller' => 'helpelements', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
