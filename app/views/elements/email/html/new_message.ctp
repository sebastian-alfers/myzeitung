<?php $conversation_link = $this->Html->url(array('controller' => 'conversations', 'action' =>  'view',$conversation['Conversation']['id'] , '#' => 'answer'), true); ?>
<tr>
    <td style="padding:15px 23px 2px; border-bottom: 1px solid #e4e3e3">
        <p style="font-size: 13px;">
           <?php  echo $this->Html->link($sender['User']['username'], $this->Html->url(array('controller' => 'users', 'action' =>  'view','username' => strtolower($sender['User']['username'])),true), array('style' => 'color:#232424; font-weight:bold;')); ?>
           <?php echo __(' wrote a new message in the conversation ');?>
           <?php echo $this->Html->link($conversation['Conversation']['title'], $conversation_link, array('style' => 'color:#232424; font-weight:bold'));?>
        </p>
    </td>
</tr>
    <?php $messageCount = count($conversation['ConversationMessage']);?>
    <?php $lastShownMessageId = $conversation['ConversationMessage'][$messageCount - 1]['id'];?>
    <?php $new_message = true; ?>
    <?php foreach($conversation['ConversationMessage'] as $message):?>
    <?php if($new_message):?>
            <?php $new_message = false;?>
            <tr><!-- new message -->
                <td style="padding:2px 23px 2px 23px; border-bottom: 1px solid #e4e3e3">
                    <p style="text-align: right; line-height: 15px; margin:0; padding:0 0 5px 0; font-size:11px; "><?php echo $this->MzTime->format('d.m.y G:i', $message['created']);?></p>
                    <p><strong><?php echo $message['User']['username'];?></strong> <?php echo $message['message']?></p>
                    <?php if($messageCount > 1):?>
                        <p style=" text-align:left; padding-top: 10px"><strong><?php echo __('Conversation History', true);?></strong></p>
                    <?php endif;?>
                </td>
            </tr>
    <?php else:?>
         <tr><!-- old message -->
			<td style="padding:2px 23px 2px 23px; border-bottom: 1px solid #e4e3e3">
                <p style="text-align: right; line-height: 15px; margin:0; padding:0 0 5px 0; font-size:11px; color:#a7a7a7;"><?php echo $this->MzTime->format('d.m.y G:i', $message['created']);?></p>
                <p style="color:#a7a7a7;"><strong><?php echo $message['User']['username']; ?></strong> <?php echo $message['message']?></p>
                <?php if($message['id'] == $lastShownMessageId): ?>
                    <p style=" text-align:right; padding-top: 10px; ;"><strong><?php echo $this->Html->link(__('View complete Conversation on myZeitung ', true), $conversation_link, array('style' => 'color:#232424; font-weight:bold;'));?></strong></p>
                <?php endif;?>
             </td>

		</tr>


        <?php endif;?>
    <?php endforeach;?>


 <tr>
    <td style="padding:30px 20px 20px 20px">
    <table>
        <tr>
            <td>
                <?php echo $this->Html->link('<img src="'.$this->Html->url('/img/assets/btn-answer-mail.gif',true).'" style="border:none; float:left; margin-right: 20px;">', $conversation_link, array('escape' => false));?>
                <?php /*echo $this->Html->image('http://myzeitung.loc/img/assets/btn-answer-mail.gif',
                                              array('alt' => __('answer', true),'url' => $this->Html->url(array('controller' => 'conversations', 'action' =>  'view',$conversation['Conversation']['id']), true) ,'style' => 'border:none; float:left; margin-right: 20px;')); */?>
            </td>
            <td>
            <a href="<?php echo $conversation_link;?>" style="color:#6e6e6e;"><?php echo $conversation_link;?></a>
            </td>

        </tr>
    </table>
    </td>
</tr>


<?php



/*
    echo $message['User']['username'].' '.$message['created'];
    echo $message['message'];

*/
?>