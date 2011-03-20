<div class="posts index">

	<h3><?php __('Posts');?></h3>
	<?php if (!empty($posts)):?>
	<table cellpadding = "0" cellspacing = "0">

	<tr>
			<th></th>
			<th><?php echo $this->Paginator->sort('user_id');?></th>
			<th><?php echo $this->Paginator->sort('title');?></th>
			<th><?php echo $this->Paginator->sort('content');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('count_views');?></th>
			<th><?php echo $this->Paginator->sort('count_reposts');?></th>
			<th><?php echo $this->Paginator->sort('count_comments');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		
		foreach ($posts as $index => $post):			
			$class = null;
				if ($i++ % 2 == 0) {
					$class = ' class="altrow"';
				}
				?>
				
			<tr<?php echo $class;?>>
				<td>  
					<?php // if($post['Post']['image']): ?>
						<?php // echo $this->Html->image($image->resize($post['Post']['image'], 150, 50, true, 'post')); ?>
					<?php // endif; ?> 
				</td>
				<td><?php echo $this->Html->link($post['User']['username'], array('controller' => 'users','action' => 'view', $post['Post']['user_id'])); ?></td>
				<td><?php echo $this->Html->link($post['Post']['title'], array('controller' => 'posts', 'action' => 'view', $post['Post']['id']));?></td>
				<td><?php echo $post['Post']['content'];?></td>
				<td><?php echo $this->Time->timeAgoInWords($post['Post']['created'], array('end' => '+1 Year'));?></td>
				<td><?php echo $post['Post']['count_views'];?></td>
				<td><?php echo $post['Post']['count_reposts'];?></td>
				<td><?php
				//add link to comments page if there are already comments
				if($post['Post']['count_comments'] > 0){
					echo $this->Html->link($post['Post']['count_comments'], array('controller' => 'comments','action' => 'show', $post['Post']['id']));
				} else {
					echo $post['Post']['count_comments'];
				}?></td>
				<td class="actions"><?php 
				//if user did not repost a post yet, there will be a repost button. otherwise there will be a undoRepost Button
				if(is_array($post['Post']['reposters'])){
					if(!in_array($session->read('Auth.User.id'),$post['Post']['reposters'])){
						if($session->read('Auth.User.id') != $post['Post']['user_id']){
							//repost button
							echo $this->Html->link(__('Repost', true), array('controller' => 'posts', 'action' => 'repost', $post['Post']['id']));	
						}
					}else{
					//undoRepost button
						echo $this->Html->link(__('undoRepost', true), array('controller' => 'posts','action' => 'undoRepost', $post['Post']['id']));
					} 
				}else 
				{
					if($session->read('Auth.User.id') != $post['Post']['user_id']){
						//repost button (if no array ... )
						echo $this->Html->link(__('Repost', true), array('controller' => 'posts','action' => 'repost', $post['Post']['id']));	
					}
					
				}?>
				
					
					<?php
						//show button only if it's the users own post on it's own blog e 
						if($session->read('Auth.User.id') == $post['Post']['user_id']){
								echo $this->Html->link(__('Edit', true), array('controller' => 'posts', 'action' => 'edit', $post['Post']['id']));
						} ?>
					<?php
						//show button only if it's the users own post on it's own blog e 
						if($session->read('Auth.User.id') == $post['Post']['user_id']){
							 echo $this->Html->link(__('Delete', true), array('controller' => 'posts', 'action' => 'delete', $post['Post']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $post['Post']['id'])); 
						}?>
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