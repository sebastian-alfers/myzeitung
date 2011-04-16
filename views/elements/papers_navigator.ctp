<div class="posts index">

	<h3><?php __('Papers');?></h3>
	<?php if (!empty($papers)):?>
	<table cellpadding = "0" cellspacing = "0">

	<tr>
			<th>Paperbild</th>
			<th><?php echo $this->Paginator->sort('title');?></th>
			<th><?php echo $this->Paginator->sort('count_subscriptions');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		
		foreach ($papers as $index => $paper):			
			$class = null;
				if ($i++ % 2 == 0) {
					$class = ' class="altrow"';
				}
				?>
				
			<tr<?php echo $class;?>>
				<td>  
					<?php // if($paper['Paper']['image']): ?>
						<?php // echo $this->Html->image($image->resize($paper['Paper']['image'], 150, 50, true, 'paper')); ?>
					<?php // endif; ?> 
				</td>
				<td><?php echo $this->Html->link($paper['Paper']['title'], array('controller' => 'papers','action' => 'view', $paper['Paper']['id'])); ?></td>
				<td><?php echo $paper['Paper']['subscription_count'];?></td>
				<td class="actions">
							
					<?php
						//EDIT BUTTON - show button only if it's the users own paper 
						if($session->read('Auth.User.id') == $paper['Paper']['owner_id']){
								echo $this->Html->link(__('Edit', true), array('controller' => 'papers', 'action' => 'edit', $paper['Paper']['id']));
						} ?>
					<?php
						//DELETE BUTTON- show button only if it's the users own paper
						if($session->read('Auth.User.id') == $paper['Paper']['owner_id']){
							 echo $this->Html->link(__('Delete', true), array('controller' => 'papers', 'action' => 'delete', $paper['Paper']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $paper['Paper']['id'])); 
						}?>
					<?php 
						//SUBSCRIBE BUTTON - just if user is not the owner of the paper 
						if(($session->read('Auth.User.id') != $paper['Paper']['owner_id']) && ($paper['Paper']['subscribed'] == false)){
								echo $this->Html->link(__('Subscribe', true), array('controller' => 'papers', 'action' => 'subscribe', $paper['Paper']['id']));
						}
					?>
					<?php 
						//UNSUBSCRIBE BUTTON - just if user is not the owner of the paper 
						if(($session->read('Auth.User.id') != $paper['Paper']['owner_id']) && ($paper['Paper']['subscribed'] == true)){
								echo $this->Html->link(__('Unsubscribe', true), array('controller' => 'papers', 'action' => 'unsubscribe', $paper['Paper']['id']));
						}
					?>
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
	  	<?php echo $this->Paginator->numbers();?>
 
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<?php endif;  ?>