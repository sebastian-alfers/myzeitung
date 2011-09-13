<div class="helpelements view">
<h2><?php  __('Helpelement');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $helpelement['Helpelement']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Description'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $helpelement['Helpelement']['description']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Page'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($helpelement['Page']['id'], array('controller' => 'helppages', 'action' => 'view', $helpelement['Page']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Accessor'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $helpelement['Helpelement']['accessor']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Deu'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $helpelement['Helpelement']['deu']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Eng'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $helpelement['Helpelement']['eng']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $helpelement['Helpelement']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $helpelement['Helpelement']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Helpelement', true), array('action' => 'edit', $helpelement['Helpelement']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Helpelement', true), array('action' => 'delete', $helpelement['Helpelement']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $helpelement['Helpelement']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Helpelements', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Helpelement', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Helppages', true), array('controller' => 'helppages', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Page', true), array('controller' => 'helppages', 'action' => 'add')); ?> </li>
	</ul>
</div>
