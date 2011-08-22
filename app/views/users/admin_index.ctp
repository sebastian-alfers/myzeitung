<div class="users index">
	<h2><?php __('Users');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('group_id');?></th>
        <th><?php echo $this->Paginator->sort('username');?></th>
        <th><?php echo $this->Paginator->sort('name');?></th>
        <th><?php echo $this->Paginator->sort('email');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th class="actions"><?php __('Actions');?></th>
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
		<td><?php echo $user['User']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $user['User']['group_id']; ?>
        </td>
        <td><?php echo $user['User']['username']; ?>&nbsp;</td>
        <td><?php echo $this->Text->truncate($user['User']['name'], 25,array('ending' => '...', 'exact' => true, 'html' => false)); ?>&nbsp;</td>
        <td><?php echo $user['User']['email']; ?>&nbsp;</td>
		<td><?php echo $this->MzTime->format('d.m.y',$user['User']['created']); ?>&nbsp;</td>
		<td><?php echo $this->MzTime->format('d.m.y',$user['User']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', 'username' => strtolower($user['User']['username']),'admin' => false),  array('target' => 'blank')); ?>
            <?php if($is_superadmin): ?>
			    <?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $user['User']['id'])); ?>
                <?php if($user['User']['enabled']):?>
                      <?php echo $this->Html->link(__('Disable', true), array('action' => 'disable', $user['User']['id']), null, sprintf(__('Are you sure you want to disable this user: %s?', true), $user['User']['username'])); ?>
                <?php else:?>
                      <?php echo $this->Html->link(__('Enable', true), array('action' => 'enable', $user['User']['id']), null, sprintf(__('Are you sure you want to disable this user: %s?', true), $user['User']['username'])); ?>
                <?php endif;?>
			    <?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $user['User']['id']), null, sprintf(__('Are you sure you want to delete this user: %s?', true), $user['User']['username'])); ?>
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