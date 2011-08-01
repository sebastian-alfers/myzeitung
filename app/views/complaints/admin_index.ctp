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
			<?php __('Paper:'); ?> <a href="/papers/view/<?php echo $complaint['Paper']['id']; ?>" target="blank"><?php echo $complaint['Paper']['title']; ?> </a>
		</td>
        <?php endif; ?>
        <?php if(!empty($complaint['Complaint']['post_id'])): ?>
		<td>
			<?php __('Post:'); ?> <a href="/posts/view/<?php echo $complaint['Post']['id']; ?>" target="blank"><?php echo $complaint['Post']['title']; ?> </a>
		</td>
        <?php endif; ?>
        <?php if(!empty($complaint['Complaint']['comment_id'])): ?>
		<td>
			<?php __('Comment:'); ?> <a href="/posts/view/<?php echo $complaint['Comment']['post_id']; ?>/#comment_<?php echo $complaint['Comment']['id']; ?>" target="blank">
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
			<?php __('User:'); ?> <a href="/users/view/<?php echo $complaint['User']['id']; ?>" target="blank"><?php echo $complaint['User']['username']; ?></a>
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
               <a href="/users/view/<?php echo $complaint['Reporter']['id']; ?>" target="blank"><?php echo $complaint['Reporter']['name']; ?></a>
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
