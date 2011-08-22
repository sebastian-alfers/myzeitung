<?php if($user['User']['name']):?>
<p><strong><?php echo $user['User']['name'];?></strong></p>
<?php endif;?>
<?php if(!empty($user['User']['description'])):?>
<p><i><?php echo strip_tags($user['User']['description'])?></i></p>
<?php endif;?>
<?php if(!empty($user['User']['url'])):?>
<p class="user-url"><?php echo $this->Html->link($user['User']['url'],$user['User']['url'] , array('rel' => 'nofollow', 'target' => '_blank'))?></p>
<?php endif;?>
<hr />