<?php $postUrl = $this->Html->url(array('controller' => 'posts', 'action' => 'view', $post['Post']['id']),true);?>
<p style='color:#232424;'>
    <?php echo __('Your following post has been commented:',true); ?>
    <?php echo $this->Html->link($post['Post']['title'], $postUrl, array('style' => 'color:#232424; font-weight:bold;'));?>
</p>
<p style='color:#232424;'>
<?php echo $this->MzTime->format('d.m.y G:i', $comment['Comment']['created']);?>
 &nbsp;<?php echo $this->Html->link($commentator['User']['username'], $this->Html->url(array('controller' => 'posts', 'action' => 'view', $post['Post']['id'])),array('style' => 'color:#232424; font-weight:bold;')); ?>
<br />
<?php echo $comment['Comment']['text'];?>
</p>