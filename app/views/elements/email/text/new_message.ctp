<?php $conversation_link = $this->Html->url(array('controller' => 'conversations', 'action' =>  'view',$conversation['Conversation']['id'], '#' => 'answer'), true); ?>

<?php  echo $sender['User']['username']; ?>
<?php echo __(' wrote a new message in the conversation: ');?>
<?php echo $conversation['Conversation']['title']."\n\n";?>

<?php $messageCount = count($conversation['ConversationMessage']);?>
<?php $lastShownMessageId = $conversation['ConversationMessage'][$messageCount - 1]['id'];?>
<?php $new_message = true; ?>
<?php foreach($conversation['ConversationMessage'] as $message):?>
<?php if($new_message):?>
<?php $new_message = false;?>
<?php echo $this->MzTime->format('d.m.y G:i', $message['created'])."\n";?>
<?php echo $message['User']['username'];?>: <?php echo $message['message']."\n\n"?>
<?php if($messageCount > 1):?>
<?php echo __('Conversation History', true)."\n";?>
<?php endif;?>
<?php else:?>
<?php echo $this->MzTime->format('d.m.y G:i', $message['created'])."\n";?>
<?php echo $message['User']['username']; ?>: <?php echo $message['message']."\n\n"?>

<?php endif;?>
<?php endforeach;?>

<?php echo __('Use the following link to see the complete conversation on myZeitung or to answer: ',true);?>

<?php echo $conversation_link;?>


