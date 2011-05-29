<div class="conversations">
	<h2><?php __('Conversations');?></h2>
	<table cellpadding="0" cellspacing="0">
	
	
	<tr>
		<td>
			<strong>Participants</strong>
		</td>
		<td>
			<strong>Title</strong>
		</td>
		<td>
			<strong>Last Message</strong>
		</td>
		<td>
			<strong>Options</strong>
		</td>
		<td>
			<strong>Status</strong>
		</td>
	</tr>
	
	<?php foreach($conversations as $conversation):?>
	<tr>
		<td>
		<?php echo "you";?>
		<?php foreach($conversation['Conversation']['ConversationUser'] as $user){
			if($user['User']['id'] != $session->read('Auth.User.id')){
				echo ', '.$user['User']['username'];
			}
		}?>
		</td>
		<td>
			<?php echo $this->Html->link($conversation['Conversation']['title'],array('controller' => 'conversations', 'action' => 'view', $conversation['Conversation']['id']));?>
		</td>
		<td>
			<?php echo $this->Html->link($conversation['Conversation']['LastMessage']['message'],array('controller' => 'conversations', 'action' => 'view', $conversation['Conversation']['id']));?>
		</td>
		<td>
			<?php echo $this->Html->link('view',array('controller' => 'conversations', 'action' => 'view', $conversation['Conversation']['id']));?>
			&nbsp;
			<?php echo $this->Html->link('remove',array('controller' => 'conversations', 'action' => 'remove', $conversation['Conversation']['id']));?>
		</td>
		<td>
			<?php if($conversation['ConversationUser']['status'] == conversation::STATUS_NEW) echo 'NEW';?>
			<?php if($conversation['ConversationUser']['status'] == conversation::STATUS_REPLIED) echo 'REPLIED';?>
		</td>
		</tr>
		<?php endforeach;?>
	
	</table>
			
</div>