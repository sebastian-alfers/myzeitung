<div class="posts form">
<?php echo $this->Form->create('Post');?>
		
	
	<fieldset>
 		<legend><?php __('Add Post'); ?></legend>
	<?php
		echo $this->Form->input('topic_id');
		echo $this->Form->input('title');
		echo $this->Form->input('content');
		echo $this->Form->hidden('user_id',array('value' => $user_id));

	?>
	tim <?php echo $user_id; ?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('List Posts', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Users', true), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Topics', true), array('controller' => 'topics', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Topic', true), array('controller' => 'topics', 'action' => 'add')); ?> </li>
	</ul>
</div>
