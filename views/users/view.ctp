<div class="users view">

<h2><?php  __('User');?></h2>
	<dl>
		<dt><?php __('User'); ?></dt>
		<dd><?php echo $user['User']['username'];?></dd>
		
		
		<?php if($user['User']['firstname'] or $user['User']['name']){?>
		<dt><?php __('Name'); ?></dt>
		<dd><?php echo $user['User']['firstname'].' '.$user['User']['name']; ?></dd>
		<?php }?>
		
		<dt><?php __('Member since'); ?></dt>
		<dd><?php echo $user['User']['created'];?></dd>			
	</dl>
</div>


<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<?php echo $this->element('navigation'); ?>	
</div>
<?php debug($user['Post']);?>
  
<div class="related">
	<h3><?php __('Related Posts');?></h3>
	<?php if (!empty($user['Post'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Repost'); ?></th>
		<th><?php __('User Id'); ?></th>
		<th><?php __('Title'); ?></th>
		<th><?php __('Content'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Count Views'); ?></th>
		<th><?php __('Count Reposts'); ?></th>
		<th><?php __('Count Comments'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($user['Post'] as $index => $post):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}

		?>
		
		<tr<?php echo $class;?>>
		<?php echo $index;?>
			<td><?php if($post['user_id'] != $user['User']['id']) {echo "repost";}?></td>
			<td><?php echo $post['user_id'];?></td>
			<td><?php echo $post['title'];?></td>
			<td><?php echo $post['content'];?></td>
			<td><?php echo $post['created'];?></td>
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
	</div>
<?php endif; /* ?>


	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Post', true), array('controller' => 'posts', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Topics');?></h3>
	<?php if (!empty($user['Topic'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Name'); ?></th>
		<th><?php __('User Id'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th><?php __('Enabled'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($user['Topic'] as $topic):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $topic['id'];?></td>
			<td><?php echo $topic['name'];?></td>
			<td><?php echo $topic['user_id'];?></td>
			<td><?php echo $topic['created'];?></td>
			<td><?php echo $topic['modified'];?></td>
			<td><?php echo $topic['enabled'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'topics', 'action' => 'view', $topic['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'topics', 'action' => 'edit', $topic['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'topics', 'action' => 'delete', $topic['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $topic['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Topic', true), array('controller' => 'topics', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<?php */ ?>