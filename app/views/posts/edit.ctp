<div class="posts form">
<?php echo $this->Form->create('Post');?>
	<fieldset>
 		<legend><?php __('Edit Post'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->hidden('user_id');
		echo $this->Form->input('topic_id');
		echo $this->Form->input('title');
		echo $this->Form->input('content');
        echo $this->Form->input('visible');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<?php echo $this->element('navigation'); ?>	
	<h3><?php __('Options'); ?></h3>
	<ul>
	        <li><?php echo $this->Html->link(__('Back', true), array('controller' => 'users',  'action' => 'view', $session->read('Auth.User.id'))); ?> </li>
	</ul>	
</div>