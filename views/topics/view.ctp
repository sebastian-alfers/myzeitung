<div class="topics view">
<h2><?php  __('Topic');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $topic['Topic']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $topic['Topic']['name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('User'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($topic['User']['name'], array('controller' => 'users', 'action' => 'view', $topic['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $topic['Topic']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $topic['Topic']['modified']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Enabled'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $topic['Topic']['enabled']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<?php echo $this->element('navigation'); ?>
</div>
<div class="related">
	<h3><?php __('Related Posts');?></h3>
	<?php if (!empty($topic['Post'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('User Id'); ?></th>
		<th><?php __('Topic Id'); ?></th>
		<th><?php __('Title'); ?></th>
		<th><?php __('Content'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Enabled'); ?></th>
		<th><?php __('Count Views'); ?></th>
		<th><?php __('Count Reposts'); ?></th>
		<th><?php __('Count Comments'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($topic['Post'] as $post):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $post['id'];?></td>
			<td><?php echo $post['user_id'];?></td>
			<td><?php echo $post['topic_id'];?></td>
			<td><?php echo $post['title'];?></td>
			<td><?php echo $post['content'];?></td>
			<td><?php echo $post['modified'];?></td>
			<td><?php echo $post['created'];?></td>
			<td><?php echo $post['enabled'];?></td>
			<td><?php echo $post['count_views'];?></td>
			<td><?php echo $post['count_reposts'];?></td>
			<td><?php echo $post['count_comments'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'posts', 'action' => 'view', $post['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'posts', 'action' => 'edit', $post['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'posts', 'action' => 'delete', $post['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $post['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Post', true), array('controller' => 'posts', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
