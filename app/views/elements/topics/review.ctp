<h2><?php __('Are you sure?'); ?></h2>

<?php
debug($topic);
//build plural / singular string
$post_posts = sprintf(__n('%s post', '%s posts', $topic['Topic']['post_count'], true), $topic['Topic']['post_count']);
$repost_reposts = sprintf(__n('%s repost', '%s reposts', $topic['Topic']['repost_count'], true), $topic['Topic']['repost_count']);
?>

<?php echo sprintf(__('Your topic %s containts currently', true), $topic['Topic']['name']) . ' ' . $post_posts . ' ' . __('and', true). ' ' . $repost_reposts; ?>.
<?php __('The topic of these entry are going to be set to "no topic"'); ?>