
<?php $subscriberLink = $this->Html->url(array('controller' => 'users', 'action' => 'view', 'username' => strtolower($paper['User']['username'])),true);
     $paperLink = $this->Html->url($paper['Route'][0]['source'],true);
if($topic != null){
    $topicLink = $this->Html->url(array('controller' => 'users', 'action' => 'view', 'username' => strtolower($recipient['User']['username']), $topic['Topic']['id']),true);
}
if($category != null){
    $categoryLink = $this->Html->url($paper['Route'][0]['source'].'/'.$category['Category']['id'],true);
}
?>

<?php if($topic == null &&  $category == null): ?>
<?php echo sprintf(__('User %1$s subscribed to all of your posts into the paper %2$s',true),$paper['User']['username'],$paper['Paper']['title'])."\n";?>
<?php echo sprintf(__('Have a look at the profile of your subscriber: %s', true),$subscriberLink)."\n";?>
<?php echo sprintf(__('Have a look at the paper you have been subscribed to: %s', true),$paperLink)."\n";?>
<?php endif;?>
<?php

if($topic != null &&  $category == null){
echo sprintf(__('User %1$s subscribed to the posts of your topic %2$s into the paper %3$s',true),$paper['User']['username'],$topic['Topic']['name'],$paper['Paper']['title'])."\n";
echo sprintf(__('Have a look at the profile of your subscriber: %s', true),$subscriberLink)."\n";
echo sprintf(__('Have a look at the paper you have been subscribed to: %s', true),$paperLink)."\n";
}

if($topic == null &&  $category != null){
echo sprintf(__('User %1$s subscribed to all of your posts into the category %2$s of the paper %3$s',true),$paper['User']['username'],$category['Category']['name'],$paper['Paper']['title'])."\n";
echo sprintf(__('Have a look at the profile of your subscriber: %s', true),$subscriberLink)."\n";
echo sprintf(__('Have a look at the paper category you have been subscribed to: %s', true),$categoryLink)."\n";
}
if($topic != null &&  $category != null){
echo sprintf(__('User %1$s subscribed to the posts of your topic %2$s into the category %3$s of the paper %4$s',true),$paper['User']['username'],$topic['Topic']['name'],$category['Category']['name'],$paper['Paper']['title'])."\n";
echo sprintf(__('Have a look at the profile of your subscriber: %s', true),$subscriberLink)."\n";
echo sprintf(__('Have a look at the paper category you have been subscribed to: %s', true),$categoryLink)."\n";
}
?>
