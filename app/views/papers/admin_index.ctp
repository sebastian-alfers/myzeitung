<div class="users index">
	<h2><?php __('Papers');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
        <th><?php echo $this->Paginator->sort('enabled');?></th>
        <th><?php echo $this->Paginator->sort('id');?></th>
        <th><?php echo $this->Paginator->sort('User-ID' ,'owner_id');?></th>
        <th><?php echo $this->Paginator->sort('Username', 'User.username');?></th>
        <th><?php echo $this->Paginator->sort('title');?></th>
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
        <td><?php echo $this->MzText->truncate($paper['Paper']['title'], 40,array('ending' => '...', 'exact' => true, 'html' => false)); ?>&nbsp;</td>
        <td><?php echo $this->MzTime->format('d.m.y',$paper['Paper']['created']); ?>&nbsp;</td>
		<td><?php echo $this->MzTime->format('d.m.y',$paper['Paper']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), $paper['Route'][0]['source'], array('target' => 'blank')); ?>
            <?php $label = $paper['Paper']['visible_home']? 'Hide (home)': 'Show  (home)'; ?>
            <?php echo $this->Html->link(__($label, true), array('action' => 'toggleVisible', 'home/'.$paper['Paper']['id'])); ?>
            <?php $label = $paper['Paper']['visible_index']? 'Hide (index)': 'Show (index)'; ?>
            <?php echo $this->Html->link(__($label, true), array('action' => 'toggleVisible', 'index/'.$paper['Paper']['id'])); ?>
            <?php if($is_superadmin): ?>
                <?php echo $this->Html->link(__('Edit Premium', true), array('action' => 'editPremium', $paper['Paper']['id'])); ?>
                <?php if($paper['Paper']['enabled']):?>
                    <?php echo $this->Html->link(__('Disable', true), array('action' => 'disable', 'home/'.$paper['Paper']['id']),null, sprintf(__('Are you sure you want to disable this paper?: %s', true), $paper['Paper']['title'])); ?>
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