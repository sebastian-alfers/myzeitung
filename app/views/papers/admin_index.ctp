<div class="users index">
	<h2><?php __('Papers');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
        <th><?php echo $this->Paginator->sort('enabled');?></th>
        <th><?php echo $this->Paginator->sort('id');?></th>
        <th><?php echo $this->Paginator->sort('User-ID' ,'owner_id');?></th>
        <th><?php echo $this->Paginator->sort('Username', 'User.username');?></th>
        <th><?php echo $this->Paginator->sort('title');?></th>
        <th><?php echo $this->Paginator->sort('subscription_count');?></th>
        <th><?php echo $this->Paginator->sort('content_paper_count');?></th>
        <th><?php echo $this->Paginator->sort('category_paper_post_count');?></th>
        <th><?php echo $this->Paginator->sort('created');?></th>
        <th><?php echo $this->Paginator->sort('modified');?></th>
        <th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($papers as $paper):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
        <td><?php echo $paper['Paper']['enabled']; ?>&nbsp;</td>
		<td><?php echo $paper['Paper']['id']; ?>&nbsp;</td>
        <td><?php echo $paper['Paper']['owner_id']; ?>&nbsp;</td>
        <td><?php echo $paper['User']['username']; ?>&nbsp;</td>
        <td><?php echo $this->Text->truncate($paper['Paper']['title'], 40,array('ending' => '...', 'exact' => true, 'html' => false)); ?>&nbsp;</td>
        <td><?php echo $paper['Paper']['subscription_count']; ?></td>
        <td><?php echo $paper['Paper']['content_paper_count']; ?></td>
        <td><?php echo $paper['Paper']['category_paper_post_count']; ?></td>
		<td><?php echo $this->MzTime->format('d.m.y',$paper['Paper']['created']); ?>&nbsp;</td>
		<td><?php echo $this->MzTime->format('d.m.y',$paper['Paper']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array($paper['Route'][0]['source'], 'admin' => false), array('target' => 'blank')); ?>
            <?php if($is_superadmin): ?>
                <?php if($paper['Paper']['enabled']):?>
			        <?php echo $this->Html->link(__('Disable', true), array('action' => 'disable', $paper['Paper']['id']),null, sprintf(__('Are you sure you want to disable this paper?: %s', true), $paper['Paper']['title'])); ?>
                <?php else:?>
                    <?php echo $this->Html->link(__('Enable', true), array('action' => 'enable', $paper['Paper']['id']),null, sprintf(__('Are you sure you want to enable this paper?: %s', true), $paper['Paper']['title'])); ?>
                <?php endif;?>
			    <?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $paper['Paper']['id']), null, sprintf(__('Are you sure you want to delete this paper?: %s', true), $paper['Paper']['title'])); ?>
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