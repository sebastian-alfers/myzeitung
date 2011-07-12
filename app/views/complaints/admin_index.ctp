<div class="complaints index">
	<h2><?php __('Complaints');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('paper_id');?></th>
			<th><?php echo $this->Paginator->sort('post_id');?></th>
			<th><?php echo $this->Paginator->sort('comment_id');?></th>
			<th><?php echo $this->Paginator->sort('user_id');?></th>
			<th><?php echo $this->Paginator->sort('reason_id');?></th>
			<th><?php echo $this->Paginator->sort('comments');?></th>
			<th><?php echo $this->Paginator->sort('reporter_id');?></th>
			<th><?php echo $this->Paginator->sort('reporter_email');?></th>
			<th><?php echo $this->Paginator->sort('complaintstatus_id');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('updated');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($complaints as $complaint):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $complaint['Complaint']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($complaint['Paper']['title'], array('controller' => 'papers', 'action' => 'view', $complaint['Paper']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($complaint['Post']['title'], array('controller' => 'posts', 'action' => 'view', $complaint['Post']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($complaint['Comment']['id'], array('controller' => 'comments', 'action' => 'view', $complaint['Comment']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($complaint['User']['name'], array('controller' => 'users', 'action' => 'view', $complaint['User']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($complaint['Reason']['id'], array('controller' => 'reasons', 'action' => 'view', $complaint['Reason']['id'])); ?>
		</td>
		<td><?php echo $complaint['Complaint']['comments']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($complaint['Reporter']['name'], array('controller' => 'users', 'action' => 'view', $complaint['Reporter']['id'])); ?>
		</td>
		<td><?php echo $complaint['Complaint']['reporter_email']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($complaint['Complaintstatus']['id'], array('controller' => 'complaintstatuses', 'action' => 'view', $complaint['Complaintstatus']['id'])); ?>
		</td>
		<td><?php echo $complaint['Complaint']['created']; ?>&nbsp;</td>
		<td><?php echo $complaint['Complaint']['updated']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $complaint['Complaint']['id'])); ?>
			<?php if($is_superadmin): ?>
                <?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $complaint['Complaint']['id'])); ?>
			    <?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $complaint['Complaint']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $complaint['Complaint']['id'])); ?>
            <?php endif; ?>
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
