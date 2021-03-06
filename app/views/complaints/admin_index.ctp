<div class="complaints index">
	<h2><?php __('Complaints');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo __('Complain Type'); ?></th>
            <th><?php __('Comments'); ?></th>
			<th><?php __('Reporter'); ?></th>
			<th class="actions"></th>
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
        <?php if(!empty($complaint['Complaint']['paper_id'])): ?>
		<td>
            <?php if($complaint['Paper']['id']): ?>
			    <?php __('Paper:'); echo $this->Html->link($complaint['Paper']['title'], $complaint['Paper']['Route'][0]['source'], array('target' => 'blank'));?>
            <?php else: ?>
                <?php __('Paper not available. Deleted?'); ?>
            <?php endif; ?>
		</td>
        <?php endif; ?>
        <?php if(!empty($complaint['Complaint']['post_id'])): ?>
		<td>
            <?php if($complaint['Post']['id']): ?>
			    <?php __('Post:'); echo $this->Html->link($complaint['Post']['title'], $complaint['Post']['Route'][0]['source'], array('target' => 'blank'));?>
            <?php else: ?>
                <?php __('Post not available. Deleted?'); ?>
            <?php endif; ?>

		</td>
        <?php endif; ?>
        <?php if(!empty($complaint['Complaint']['comment_id'])): ?>
		<td>
			<?php __('Comment:'); echo $this->Html->link($complaint['Post']['title'], $complaint['Route'][0]['source'].'#comment_'.$complaint['Comment']['id'], array('target' => 'blank'));?>
            <?php if(strlen($complaint['Comment']['text']) > 30){
                    echo substr($complaint['Comment']['text'], 0, 30) . ' ...';
                }
                else{
                    echo $complaint['Comment']['text'];
                }
            ?></a>
		</td>
        <?php endif; ?>
        <?php if(!empty($complaint['Complaint']['user_id'])): ?>

		<td>
			<?php __('User:'); echo $this->Html->link($complaint['User']['username'], array('controller' => 'users','action' => 'view', 'username' => strtolower($complaint['User']['username']), 'admin' => false), array('target' => 'blank'));?>
		</td>
        <?php endif; ?>
		<td><?php

            if(isset($complaint['Complaint']['comments']) && !empty($complaint['Complaint']['comments'])){
                $complaint['Complaint']['comments'] =  unserialize($complaint['Complaint']['comments']);
                $count= count($complaint['Complaint']['comments']);
                echo sprintf(__n('%d comment', '%d comments', $count, true), $count);

             }

                ?></td>
		<td>
            <?php if(isset($complaint['Reporter']['id']) && !empty($complaint['Reporter']['id'])): ?>
                <?php echo $this->Html->link($complaint['Reporter']['username'],array('controller' => 'users', 'action' => 'view', 'username' => strtolower($complaint['Reporter']['username'])), array('target' => 'blank')); ?>
               
            <?php endif; ?>
            <br />
            <?php echo $complaint['Complaint']['reporter_name']; ?><br />
            <?php echo $complaint['Complaint']['reporter_email']; ?>

		</td>
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
