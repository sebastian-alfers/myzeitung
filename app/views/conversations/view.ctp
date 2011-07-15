			<?php echo $this->element('users/sidebar'); ?>
					<div id="maincolwrapper">
					<div id="maincol" class="account message-overview message-view">

						<h4 class="account-title message-title"><?php echo $conversation['Conversation']['title'];?></h4>
						<ul class="messages">
                            <?php foreach($messages as $message): ?>
							<li class="message">

								<ul>
									<li class="user-image">
                                        <?php echo $this->Html->link($image->render($message['User'], 37, 37, array( "alt" => $message['User']['username'], "class" => 'user-image'), array(), ImageHelper::USER),array('controller' => 'users', 'action' => 'view',$message['User']['id']), array('escape' => false));?>
									</li>
									<li class="is-answer">
									</li>
									<li class="message-info">
										<p class="from"><?php echo __("from", true);?>&nbsp;<?php echo $this->Html->link('<strong>'.$message['User']['username'].'</strong>'.$message['User']['name'],array('controller' => 'users', 'action' => 'view',$message['User']['id']), array('escape' => false));?>
                                           
										<p class="message-content"><?php echo $message['ConversationMessage']['message'];?></p>
									</li>
									<li class="actions">
										<p><?php echo $this->Time->timeAgoInWords($message['ConversationMessage']['created'], array('end' => '+1 Week'));?></p>
									</li>
                                </ul>

                            </li><!-- /.message -->
                            <?php endforeach; ?>

                        </ul>

						<div class="write-anwer">
								<form action="">
									<h4>Antworten</h4>
									<textarea rows="5" cols="10" ></textarea>
									<a class="btn big"><span class="icon icon-send"></span>Nachricht senden</a>
								</form>
						</div>

					</div><!-- / #maincol -->

				</div><!-- / #maincolwrapper -->


<?php


/*

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
            */ ?>