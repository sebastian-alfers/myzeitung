<div class="users index">
	<h2><?php __('Comments');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
        <th><?php echo $this->Paginator->sort('enabled');?></th>
        <th><?php echo $this->Paginator->sort('id');?></th>
        <th><?php echo $this->Paginator->sort('User-ID' ,'user_id');?></th>
        <th><?php echo $this->Paginator->sort('Username', 'User.username');?></th>
        <th><?php echo $this->Paginator->sort('text');?></th>
        <th><?php echo $this->Paginator->sort('created');?></th>
        <th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;

	foreach ($comments as $comment):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
        <td><?php echo $comment['Comment']['enabled']; ?>&nbsp;</td>
		<td><?php echo $comment['Comment']['id']; ?>&nbsp;</td>
        <td><?php echo $comment['Comment']['user_id']; ?>&nbsp;</td>
        <td><?php echo $comment['User']['username']; ?>&nbsp;</td>
        <td><?php echo $this->MzText->truncate($comment['Comment']['text'], 40,array('ending' => '...', 'exact' => true, 'html' => false)); ?>&nbsp;</td>
		<td><?php echo $this->MzTime->format('d.m.y',$comment['Comment']['created']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View Post', true), $comment['Post']['Route'][0]['source'], array('target' => 'blank')); ?>
            <?php if($is_superadmin): ?>
                <?php if($comment['Comment']['enabled']):?>
			        <?php echo $this->Html->link(__('Disable', true), array('action' => 'disable', $comment['Comment']['id']),null, sprintf(__('Are you sure you want to disable this comment?: %s', true), $comment['Comment']['text'])); ?>
                <?php else:?>
                    <?php echo $this->Html->link(__('Enable', true), array('action' => 'enable', $comment['Comment']['id']),null, sprintf(__('Are you sure you want to enable this comment?: %s', true), $comment['Comment']['text'])); ?>
                <?php endif;?>
			    <?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $comment['Comment']['id']), null, sprintf(__('Are you sure you want to delete this comment?: %s', true), $comment['Comment']['text'])); ?>
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