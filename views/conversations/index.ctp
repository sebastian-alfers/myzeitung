<div class="conversations">
	<h2><?php __('Conversations');?></h2>
	<table cellpadding="0" cellspacing="0">
	<?php foreach($conversations as $conversation):?>
	
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
	</tr>
	
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
		</tr>
		<?php endforeach;?>
	
	</table>
			
</div>