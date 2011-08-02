<div class="users index">
	<h2><?php __('Posts');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
        <th><?php echo $this->Paginator->sort('enabled');?></th>
        <th><?php echo $this->Paginator->sort('id');?></th>
        <th><?php echo $this->Paginator->sort('User-ID' ,'user_id');?></th>
        <th><?php echo $this->Paginator->sort('Username', 'User.username');?></th>
        <th><?php echo $this->Paginator->sort('title');?></th>
        <th><?php echo $this->Paginator->sort('repost_count');?></th>
        <th><?php echo $this->Paginator->sort('view_count');?></th>
        <th><?php echo $this->Paginator->sort('comment_count');?></th>
        <th><?php echo $this->Paginator->sort('created');?></th>
        <th><?php echo $this->Paginator->sort('modified');?></th>
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
        <td><?php echo $post['Post']['enabled']; ?>&nbsp;</td>
		<td><?php echo $post['Post']['id']; ?>&nbsp;</td>
        <td><?php echo $post['Post']['user_id']; ?>&nbsp;</td>
        <td><?php echo $post['User']['username']; ?>&nbsp;</td>
        <td><?php echo $this->Text->truncate($post['Post']['title'], 40,array('ending' => '...', 'exact' => true, 'html' => false)); ?>&nbsp;</td>
        <td><?php echo $post['Post']['repost_count']; ?></td>
        <td><?php echo $post['Post']['view_count']; ?></td>
        <td><?php echo $post['Post']['comment_count']; ?></td>
		<td><?php echo $this->MzTime->format('d.m.y',$post['Post']['created']); ?>&nbsp;</td>
		<td><?php echo $this->MzTime->format('d.m.y',$post['Post']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $post['Post']['id'], 'admin' => false), array('target' => 'blank')); ?>
            <?php if($is_superadmin): ?>
                <?php if($post['Post']['enabled']):?>
			        <?php echo $this->Html->link(__('Disable', true), array('action' => 'disable', $post['Post']['id']),null, sprintf(__('Are you sure you want to disable this post?: %s', true), $post['Post']['title'])); ?>
                <?php else:?>
                    <?php echo $this->Html->link(__('Enable', true), array('action' => 'enable', $post['Post']['id']),null, sprintf(__('Are you sure you want to enable this post?: %s', true), $post['Post']['title'])); ?>
                <?php endif;?>
			    <?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $post['Post']['id']), null, sprintf(__('Are you sure you want to delete this post?: %s', true), $post['Post']['title'])); ?>
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