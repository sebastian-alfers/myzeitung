
<?php $subscriberLink = $this->Html->link($paper['User']['username'], $this->Html->url(array('controller' => 'users', 'action' => 'view', 'username' => strtolower($paper['User']['username'])),true), array('style' => 'color:#232424; font-weight:bold;'));
     $paperLink = $this->Html->link($paper['Paper']['title'], $this->Html->url($paper['Route'][0]['source'],true),array('style' => 'color:#232424; font-weight:bold;'));
if($topic != null){
    $topicLink = $this->Html->link($topic['Topic']['name'], $this->Html->url(array('controller' => 'users', 'action' => 'view', 'username' => strtolower($recipient['User']['username']), $topic['Topic']['id']),true),array('style' => 'color:#232424; font-weight:bold;'));
}
if($category != null){
    $categoryLink = $this->Html->link($category['Category']['name'], $this->Html->url(array($paper['Route'][0]['source'],$category['Category']['id']),true),array('style' => 'color:#232424; font-weight:bold;'));
}
?>
<p>
<?php if($topic == null &&  $category == null): ?>
<?php echo sprintf(__('User %1$s subscribed to all of your posts into the paper %2$s',true),$subscriberLink,$paperLink);?>
<?php endif;?>
<?php

if($topic != null &&  $category == null){
echo sprintf(__('User %1$s subscribed to the posts of your topic %2$s into the paper %3$s',true),$subscriberLink,$topicLink,$paperLink);
}

if($topic == null &&  $category != null){
echo sprintf(__('User %1$s subscribed to all of your posts into the category %2$s of the paper %3$s',true),$subscriberLink,$categoryLink,$paperLink);
}
if($topic != null &&  $category != null){
echo sprintf(__('User %1$s subscribed to the posts of your topic %2$s into the category %3$s of the paper %4$s',true),$subscriberLink,$topicLink,$categoryLink,$paperLink);
}
?>
</p>