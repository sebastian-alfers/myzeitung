<div class="comments form">
<?php echo $this->Form->create('Comment');?>
	<fieldset>
 		<legend><?php __('Add Comment'); ?></legend>
	<?php
		echo $this->Form->hidden('user_id',array('value' => $user_id));
		echo $this->Form->hidden('post_id',array('value' => $post_id));
		echo $this->Form->hidden('parent_id',array('value' => $parent_id));
		echo $this->Form->input('text');

	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Back to Comments', true), array('action' => 'show', $post_id));?></li>
		<li><?php echo $this->Html->link(__('Back to Post', true), array('controller' => 'posts', 'action' => 'view', $post_id));?></li>

	</ul>
</div>