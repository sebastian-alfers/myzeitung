			<?php echo $this->element('users/sidebar'); ?>
					<div id="maincolwrapper">
					<div id="maincol" class="account message-overview message-view">
						<h2 class="account-title message-title"><?php echo $conversation['Conversation']['title'];?></h2>

                        <p class="from-top"><?php echo __('Conversation between you',true);?>
                            <?php foreach($users as $user):?>
                                <?php if($user['User']['id'] != $session->read('Auth.User.id')):?>
                                    ,&nbsp;<strong>&nbsp;<?php echo $this->Html->link($user['User']['username'], array('controller' => 'users', 'action' => 'view','username' =>  strtolower($user['User']['username'])));?></strong>
                                <?php endif; ?>
                            <?php endforeach;?>
                            </p>

						<ul class="messages">
                            <?php foreach($messages as $message): ?>
							    <?php echo $this->element('conversations/message', array('message' => $message)); ?>
                            <?php endforeach; ?>

                        </ul>
                        <a name="answer"></a>
						<div class="write-anwer">
								<form action="">
									<h4>Antworten</h4>
									<textarea rows="5" cols="10" id="reply_value"></textarea>
									<a class="btn big" id="btn_reply"><span class="icon icon-send"></span>Nachricht senden</a>
								</form>
						</div>

					</div><!-- / #maincol -->

				</div><!-- / #maincolwrapper -->

<script type="text/javascript">
$(document).ready(function() {
    $('#btn_reply').click(function(){
        var reply = $('#reply_value').val();
        if(reply == ''){
            alert('Please fill all fields');
        }
        else{



            var req = $.post(base_url+'/conversations/reply.json', {conversation_id:<?php echo $conversation['Conversation']['id'];?>, reply:reply})
        	   .success(function( reply ){

                    if(reply.status == 'success'){
                        $('.messages').append(reply.data.html);
                        var options = {};
                        $('#'+reply.data.id+'').toggle( 'blind', options, 500 );
                        $('#reply_value').val('');
                    }
                    else{
                        alert('Error while placing the reply');
                    }
	        })
	        .error(function(){
		        alert('error');
	        });
        }
    });
});


</script>



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
		<td><?php echo $this->Html->link($message['User']['username'], array('action' => 'view', 'username' => $message['User']['username'])); ?>&nbsp;</td>
		<td><?php echo $message['ConversationMessage']['created'];?>&nbsp;</td>
		<td><?php echo $message['ConversationMessage']['message']; ?>&nbsp;</td>
		</tr>
<?php endforeach; ?>
	</table>
</div>
            */ ?>


