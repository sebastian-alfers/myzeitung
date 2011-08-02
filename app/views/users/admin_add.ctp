<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
		<legend><?php __('Admin Add User'); ?></legend>
	<?php
		echo $this->Form->input('group_id');
		echo $this->Form->input('name');
		echo $this->Form->input('description');
		echo $this->Form->input('email');
		echo $this->Form->input('username');
		echo $this->Form->input('password');
		echo $this->Form->input('image');
		echo $this->Form->input('lastlogin');
		echo $this->Form->input('enabled');
		echo $this->Form->input('allow_messages');
		echo $this->Form->input('allow_comments');
		echo $this->Form->input('subscription_count');
		echo $this->Form->input('content_paper_count');
		echo $this->Form->input('post_count');
		echo $this->Form->input('repost_count');
		echo $this->Form->input('comment_count');
		echo $this->Form->input('paper_count');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Users', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Groups', true), array('controller' => 'groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group', true), array('controller' => 'groups', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Routes', true), array('controller' => 'routes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Route', true), array('controller' => 'routes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Posts', true), array('controller' => 'posts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Post', true), array('controller' => 'posts', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Post Users', true), array('controller' => 'post_users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Post User', true), array('controller' => 'post_users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Topics', true), array('controller' => 'topics', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Topic', true), array('controller' => 'topics', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Content Papers', true), array('controller' => 'content_papers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Content Paper', true), array('controller' => 'content_papers', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Papers', true), array('controller' => 'papers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Paper', true), array('controller' => 'papers', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Subscriptions', true), array('controller' => 'subscriptions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Subscription', true), array('controller' => 'subscriptions', 'action' => 'add')); ?> </li>
	</ul>
</div>