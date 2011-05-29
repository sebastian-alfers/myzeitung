<ul id="comment_list">
	<?php foreach ($comments as $comment):?>
		<li class="comment">
		<?php $current_comment['Comment'] = $comment['Comment'];?>
		<?php $current_comment['User'] = $comment['User'];?>
		<?php $current_comment['Comment']['reply_id'] = $comment['Comment']['id'];?>
		<?php echo $this->element('comments/comment_content', array('current_comment' => $current_comment)); ?>
		<?php if(!empty($comment['children'])):?>
			<ul>
				<?php foreach($comment['children'] as $reply): ?>
					<li class="comment">
						<?php $current_comment['Comment'] = $reply['Comment'];?>
						<?php $current_comment['User'] = $reply['User'];?>
						<?php $current_comment['Comment']['reply_id'] = $reply['Comment']['id'];?>
					<?php echo $this->element('comments/comment_content', array('current_comment' => $current_comment)); ?>
					<?php if(!empty($reply['children'])):?>
						<ul>
							<?php foreach($reply['children'] as $replyReply): ?>
								<li class="comment">
								<?php $current_comment['Comment'] = $replyReply['Comment'];?>
								<?php $current_comment['User'] = $replyReply['User'];?>
								<?php $current_comment['Comment']['reply_id'] = $reply['Comment']['id'];?>
								<?php echo $this->element('comments/comment_content', array('current_comment' => $current_comment)); ?>
								</li> <!-- / .comment -->
							<?php endforeach;?>	
						</ul>	
					<?php endif;?>
					</li> <!-- / .comment -->
				<?php endforeach;?>			
			</ul>
		<?php endif;?>
		</li> <!-- / .comment -->
	<?php endforeach;?>
</ul>