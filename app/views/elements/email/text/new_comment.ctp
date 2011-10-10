<?php $postUrl = $this->Html->url($post['Route'][0]['source'],true);?>
<?php echo __('Your following post has been commented:',true);?><?php echo " ".$post['Post']['title']."\n\n";?>
<?php echo $this->MzTime->format('d.m.y G:i', $comment['Comment']['created'])." ".$this->MzText->generateDisplayName($commentator['User'], true)."\n";?>
<?php echo $comment['Comment']['text']."\n\n";?>
<?php echo __('Click on this link to see your post with and all comments: ', true)." ".$postUrl;
