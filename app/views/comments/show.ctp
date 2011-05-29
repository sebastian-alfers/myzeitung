<div class="comments index">
	<h2><?php __('Comments');?></h2>
	<table cellpadding="0" cellspacing="0">

	<?php
	$i = 0;
	foreach ($comments as $comment):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
		
		
		// direct comments
		
		
		
		
		?>
		<tr<?php echo $class;?>>
			<td>
				<?php echo $comment['Comment']['created']; ?>&nbsp;
				<?php echo $this->Html->link($comment['User']['username'], array('controller' => 'users', 'action' => 'view', $comment['User']['id'])); ?>:&nbsp;
				<?php echo $comment['Comment']['text']; ?>&nbsp;
			</td>
			<td class="actions">
				<?php echo $this->Html->link(__('Reply', true), array('action' => 'add', $comment['Comment']['post_id'],$comment['Comment']['id'])); ?>
			</td>
		</tr>
		<?php
		
		
		
		// comment -> replies
		
		
		
		
		if(!empty($comment['children'])):?>
			<?php foreach($comment['children'] as $reply): ?>
			<tr<?php echo $class;?>>
			<td>
				&nbsp;&nbsp;&nbsp;<?php echo $reply['Comment']['created']; ?>&nbsp;
				<?php echo $this->Html->link($reply['User']['username'], array('controller' => 'users', 'action' => 'view', $reply['User']['id'])); ?>:&nbsp;
				<?php echo $reply['Comment']['text']; ?>&nbsp;
			</td>
			<td class="actions">
				<?php echo $this->Html->link(__('Reply', true), array('action' => 'add', $reply['Comment']['post_id'],$reply['Comment']['id'])); ?>
			</td>
		</tr>
		<?php 
		
		
		
		
		// comment -> reply  -> replies
		
		
		
		if(!empty($reply['children'])):?>
				<?php foreach($reply['children'] as $replyReply): ?>
				<tr<?php echo $class;?>>
				<td>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $replyReply['Comment']['created']; ?>&nbsp;
					<?php echo $this->Html->link($replyReply['User']['username'], array('controller' => 'users', 'action' => 'view', $replyReply['User']['id'])); ?>:&nbsp;
					<?php echo $replyReply['Comment']['text']; ?>&nbsp;
				</td>
				<td class="actions">
					<?php echo $this->Html->link(__('Reply', true), array('action' => 'add', $reply['Comment']['post_id'],$reply['Comment']['id'])); ?>
				</td>
			</tr>
				
				
		
<?php				endforeach; ?>		
	<?php		endif; ?>
<?php		endforeach; ?>
	<?php	endif;
	endforeach; ?>
	</table>



</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Comment', true), array('action' => 'add',$post_id)); ?></li>
		<li><?php echo $this->Html->link(__('Back to Post', true), array('controller' => 'posts', 'action' => 'view', $post_id));?></li>
	</ul>
</div>