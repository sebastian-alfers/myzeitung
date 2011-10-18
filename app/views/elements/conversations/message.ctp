<?php
$additional_class = '';
$element_id = '';
if(isset($class) && !empty($class)){
    $additional_class = $class;
}

if(isset($id) && !empty($id)){
    $element_id = 'id="'.$id.'"';
}

?>
<li class="message <?php echo $additional_class; ?>" <?php echo $element_id; ?>>

    <ul>
        <li alt="<?php echo $this->MzText->getUserName($message['User']); ?>" rel="<?php echo $this->MzText->getSubscribeUrl(); ?>" id="<?php echo $message['User']['id']; ?>" link="<?php echo $this->MzHtml->url(array('controller' => 'users', 'action' => 'view', 'username' => strtolower($message['User']['username']))); ?>">
            <?php
             $image_options = array();
             $image_options['tag'] = 'div';
             echo $image->render($message['User'], 37, 37, array("alt" => $message['User']['username']), $image_options, ImageHelper::USER); ?>
            <?php //echo $this->Html->link($image->render($message['User'], 37, 37, array( "alt" => $message['User']['username'], "class" => 'user-image'), array(), ImageHelper::USER),array('controller' => 'users', 'action' => 'view', 'username' => $message['User']['username']), array('escape' => false));?>
        </li>
        <li class="is-answer">
        </li>
        <li class="message-info">
            <?php if(empty($message['User']['id'])):$message['User']['username'] = __('deleted user', true); endif;?>
            <p class="from"><?php echo __("from", true);?>&nbsp;
                <?php if($message['User']['id']):?>
                    <?php   $tipsy_name = $this->MzText->generateDisplayName($message['User'], true); ?>
                    <?php echo $this->Html->link('<strong>'.$this->MzText->generateDisplayName($message['User']).'</strong>',array('controller' => 'users', 'action' => 'view', 'username' => strtolower($message['User']['username'])), array('class' => 'tt-title', 'title' => $tipsy_name, 'escape' => false));?>
                <?php else: ?>
                    <?php echo '<strong>'.$message['User']['username'].'</strong>';?>
                <?php endif;?>

            <p class="message-content"><?php echo $message['ConversationMessage']['message'];?></p>
        </li>
        <li class="actions time">
            <p><?php echo $this->MzTime->timeAgoInWords($message['ConversationMessage']['created'], array('format' => 'd.m.y  h:m','end' => '+1 Month'));?></p>
        </li>
    </ul>

</li><!-- /.message -->