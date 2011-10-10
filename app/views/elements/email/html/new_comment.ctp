<?php $postUrl = $this->Html->url($post['Route'][0]['source'],true);?>
<p style='color:#232424;'>
    <?php echo __('Your following post has been commented:',true); ?>
    <?php echo $this->Html->link($post['Post']['title'], $postUrl, array('style' => 'color:#232424; font-weight:bold;'));?>
</p>
<p style='color:#232424;'>
<?php echo $this->MzTime->format('d.m.y G:i', $comment['Comment']['created']);?>
 &nbsp;<?php echo $this->Html->link($this->MzText->generateDisplayName($commentator['User'], true), $this->Html->url(array('controller' => 'users', 'action' => 'view', 'username' => strtolower($commentator['User']['username'])),true),array('style' => 'color:#232424; font-weight:bold;')); ?>
<br />
<?php echo $comment['Comment']['text'];?>
</p>    