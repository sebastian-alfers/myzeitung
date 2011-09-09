<hr />
<h6><?php echo __('Activity', true);?></h6>
<ul>
      <?php $post_count = $user['User']['post_count'] - $user['User']['repost_count'] ;?>
      <li><?php echo sprintf(__dn('default', '%s post', '%s posts', $post_count,true), $this->MzNumber->format( $post_count,'.'));?></li>
      <li><?php echo sprintf(__n('%s repost', '%s reposts', $user['User']['repost_count'],true), $this->MzNumber->format($user['User']['repost_count'],'.'));?></li>
      <li><?php echo sprintf(__n('%s comment', '%s comments', $user['User']['comment_count'],true), $this->MzNumber->format($user['User']['comment_count'],'.'));?></li>
      <li><a id="show-subscribers" href="#show-subscribers" title="users/references/<?php echo $user['User']['id']; ?>" help-text="<strong>Subscribers</strong><br />Eine auflistung von Zeitungen, in denen der Benutzer artikel veröfentlicht"><?php echo sprintf(__n('%s subscriber', '%s subscribers', $user['User']['subscriber_count'],true), $this->MzNumber->format($user['User']['subscriber_count'],'.'));?></a></li>
      <li><?php echo sprintf(__n('%s paper subscription', '%s paper subscriptions', $user['User']['subscription_count'],true), $this->MzNumber->format($user['User']['subscription_count'],'.'));?></li>
      <li><?php echo sprintf(__n('%s paper', '%s papers', $user['User']['paper_count'],true), $this->MzNumber->format($user['User']['paper_count'],'.'));?></li>
  </ul>
