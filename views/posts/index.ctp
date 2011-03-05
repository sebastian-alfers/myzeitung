
<div class="posts index">
	<h2><?php __('Posts');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('user_id');?></th>
			<th><?php echo $this->Paginator->sort('topic_id');?></th>
			<th><?php echo $this->Paginator->sort('title');?></th>
			<th><?php echo $this->Paginator->sort('content');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('enabled');?></th>
			<th><?php echo $this->Paginator->sort('count_views');?></th>
			<th><?php echo $this->Paginator->sort('count_reposts');?></th>
			<th><?php echo $this->Paginator->sort('count_comments');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	
	$i = 0;
	foreach ($posts as $post):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
		

	?>
	

	<tr<?php echo $class;?>>
		<td><?php echo $post['Post']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($post['User']['username'], array('controller' => 'users', 'action' => 'view', $post['User']['id'])); ?>
		</td>
		<td><?php echo $post['Post']['title']; ?>&nbsp;</td>
		<td><?php echo $post['Post']['content']; ?>&nbsp;</td>
		<td><?php echo $post['Post']['modified']; ?>&nbsp;</td>
		<td><?php echo $post['Post']['created']; ?>&nbsp;</td>
		<td><?php echo $post['Post']['enabled']; ?>&nbsp;</td>
		<td><?php echo $post['Post']['count_views']; ?>&nbsp;</td>
		<td><?php echo $post['Post']['count_reposts']; ?>&nbsp;</td>
		<td><?php echo $post['Post']['count_comments']; ?>&nbsp;</td>
		<td class="actions">
			
			<?php
			//if user did not repost a post yet, there will be repost button. otherwise there will be a undoRepost Button
			if(!in_array($session->read('Auth.User.id'),$post['Post']['reposters'])){
			//repost button
			echo $this->Html->link(__('Repost', true), array('action' => 'repost', $post['Post']['id'], '1'));	
			}else{
			//undoRepost button
			echo $this->Html->link(__('undoRepost', true), array('action' => 'undoRepost', $post['Post']['id']));
			}
			?>
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $post['Post']['id'],)); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $post['Post']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $post['Post']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $post['Post']['id'])); ?>
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
	<?php echo $this->element('navigation'); ?>	
</div>