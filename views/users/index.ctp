<div class="users index">
	<h2><?php __('Users');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('firstname');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('username');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('post_count');?></th>
			<th><?php echo $this->Paginator->sort('posts_user_count');?></th>
			<th><?php echo $this->Paginator->sort('comment_count');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($users as $user):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	
	<tr<?php echo $class;?>>
		<td><?php echo $this->Html->link($user['User']['firstname'], array('action' => 'view', $user['User']['id'])); ?>&nbsp;</td>
		<td><?php echo $this->Html->link($user['User']['name'], array('action' => 'view', $user['User']['id'])); ?>&nbsp;</td>
		<td><?php echo $this->Html->link($user['User']['username'], array('action' => 'view', $user['User']['id'])); ?>&nbsp;</td>
		<td><?php echo $this->Time->timeAgoInWords($user['User']['created'], array('end' => '+1 Year'));?>&nbsp;</td>
		<td><?php echo $user['User']['post_count']; ?>&nbsp;</td>
		<td><?php echo $user['User']['posts_user_count']; ?>&nbsp;</td>
		<td><?php echo $user['User']['comment_count']; ?>&nbsp;</td>
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
	<?php echo $this->element('navigation'); ?>	
</div>
