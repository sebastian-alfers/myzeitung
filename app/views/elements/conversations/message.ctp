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
        <li class="user-image">
            <?php
             $image_options = array();
              $image_options['tag'] = 'div';
             echo $image->render($message['User'], 37, 37, array("alt" => $message['User']['username']), $image_options, ImageHelper::USER); ?>
            <?php //echo $this->Html->link($image->render($message['User'], 37, 37, array( "alt" => $message['User']['username'], "class" => 'user-image'), array(), ImageHelper::USER),array('controller' => 'users', 'action' => 'view',$message['User']['id']), array('escape' => false));?>
        </li>
        <li class="is-answer">
        </li>
        <li class="message-info">
            <p class="from"><?php echo __("from", true);?>&nbsp;<?php echo $this->Html->link('<strong>'.$message['User']['username'].'</strong>'.$message['User']['name'],array('controller' => 'users', 'action' => 'view',$message['User']['id']), array('escape' => false));?>

            <p class="message-content"><?php echo $message['ConversationMessage']['message'];?></p>
        </li>
        <li class="actions">
            <p><?php echo $this->MzTime->timeAgoInWords($message['ConversationMessage']['created'], array('format' => 'd.m.y  h:m','end' => '+1 Month'));?></p>
        </li>
    </ul>

</li><!-- /.message -->