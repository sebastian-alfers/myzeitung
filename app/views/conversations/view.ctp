<div class="conversation view">
	<h2><?php __('Conversation between you');?>
	<?php foreach($users as $user){
		if($user['User']['id'] != $session->read('Auth.User.id')){
			echo ', '.$user['User']['username'];
		}
	}?>
	</h2>
	<b><?php echo __('Topic: ', true).$conversation['Conversation']['title'];?></b>
	
	<table cellpadding="0" cellspacing="0">
	
	<tr>
			<th><?php echo 'username'?></th>
			<th><?php echo 'created';?></th>
			<th><?php echo 'message';?></th>
	</tr>
	<?php foreach($messages as $message): ?>

	<tr>
		<td><?php echo $this->Html->link($message['User']['username'], array('action' => 'view', $message['User']['id'])); ?>&nbsp;</td>
		<td><?php echo $message['ConversationMessage']['created'];?>&nbsp;</td>
		<td><?php echo $message['ConversationMessage']['message']; ?>&nbsp;</td>
		</tr>
<?php endforeach; ?>
	</table>
</div>