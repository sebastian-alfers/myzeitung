<div class="papers view">
<h2><?php  __('Paper');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $paper['Paper']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Route'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($paper['Route']['id'], array('controller' => 'routes', 'action' => 'view', $paper['Route']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Owner Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $paper['Paper']['owner_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Title'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $paper['Paper']['title']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Description'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $paper['Paper']['description']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Url'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $paper['Paper']['url']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Paper', true), array('action' => 'edit', $paper['Paper']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Paper', true), array('action' => 'delete', $paper['Paper']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $paper['Paper']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Papers', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Paper', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Routes', true), array('controller' => 'routes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Route', true), array('controller' => 'routes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Categories', true), array('controller' => 'categories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Category', true), array('controller' => 'categories', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Subscriptions', true), array('controller' => 'subscriptions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Subscription', true), array('controller' => 'subscriptions', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php __('Related Categories');?></h3>
	<?php if (!empty($paper['Category'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Parent Id'); ?></th>
		<th><?php __('Paper Id'); ?></th>
		<th><?php __('Route Id'); ?></th>
		<th><?php __('Name'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($paper['Category'] as $category):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $category['id'];?></td>
			<td><?php echo $category['parent_id'];?></td>
			<td><?php echo $category['paper_id'];?></td>
			<td><?php echo $category['route_id'];?></td>
			<td><?php echo $category['name'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'categories', 'action' => 'view', $category['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'categories', 'action' => 'edit', $category['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'categories', 'action' => 'delete', $category['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $category['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Category', true), array('controller' => 'categories', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Subscriptions');?></h3>
	<?php if (!empty($paper['Subscription'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Paper Id'); ?></th>
		<th><?php __('User Id'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($paper['Subscription'] as $subscription):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $subscription['id'];?></td>
			<td><?php echo $subscription['paper_id'];?></td>
			<td><?php echo $subscription['user_id'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'subscriptions', 'action' => 'view', $subscription['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'subscriptions', 'action' => 'edit', $subscription['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'subscriptions', 'action' => 'delete', $subscription['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $subscription['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Subscription', true), array('controller' => 'subscriptions', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
