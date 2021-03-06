<?php $this->MzJavascript->link('user/view'); //for the user sidebar?>
<?php $this->MzJavascript->link('post/view'); ?>
<?php $this->MzJavascript->link('pirobox_extended'); ?>


<?php
$has_topics = false;
if($session->read('Auth.User.topic_count') > 0){
    $has_topics = true;
}

if($has_topics){

    echo $this->element('posts/repost_modal_choose_topic');
}
?>

<?php if(!is_array($post['Post']['image'])): ?>
    <?php $images = unserialize($post['Post']['image']); ?>
    <?php //only init if images are available ?>
    <script type="text/javascript">
    $(document).ready(function() {
        $().piroBox_ext({
        piro_speed : 700,
            bg_alpha : 0.5,
            piro_scroll : true // pirobox always positioned at the center of the page
        });
    });
    </script>
<?php endif; ?>




<?php echo $this->element('users/sidebar'); ?>
<?php
    $article_reposted_by_user = false;
    $article_belongs_to_user = false;
      if($this->Reposter->UserHasAlreadyRepostedPost($post['Post']['reposters'], $session->read('Auth.User.id'))){
					$article_reposted_by_user = true;
				}
    if($session->read('Auth.User.id') == $post['Post']['user_id']){
        $article_belongs_to_user = true;
        //just if a user could somehow repost his own post
        $article_reposted_by_user = false;
}?>


<?php
// extracting the first paragraph and the rest of the post-content
// important to not copy the <p> </p> tags, because these are already described in the view with a class
$first_paragraph = '';
if(substr($post['Post']['content'],0,2) == "<p"){
    $end = strpos($post['Post']['content'], '</p>', 0);
    $first_paragraph = substr($post['Post']['content'],0 , $end+4);
    $content_after_first_paragraph = substr($post['Post']['content'], $end+4);
} else{
    $content_after_first_paragraph = $post['Post']['content'];
}

?>
<div id="maincolwrapper">
	<div id="maincol">
		<div class="article-nav post-view">
				<ul class="iconbar">
                    <?php // tt-title -> class for tipsy &&  'title=...' text for typsy'?>
                    <?php $tipsy_title = sprintf(__n('%s comment', '%s comments', $post['Post']['comment_count'],true), $this->MzNumber->counterToReadableSize($post['Post']['comment_count']));?>
                    <li class="comments tt-title" title="<?php echo $tipsy_title;?>"><?php echo $this->MzNumber->counterToReadableSize($post['Post']['comment_count']);?><span>.</span></li>
                    <?php $tipsy_title = sprintf(__n('%s time viewed', '%s times viewed', $post['Post']['view_count'],true), $this->MzNumber->counterToReadableSize($post['Post']['view_count']));?>
                    <li class="views tt-title" title="<?php echo $tipsy_title;?>"><?php echo $this->MzNumber->counterToReadableSize($post['Post']['view_count']);?></li>
                    <?php $tipsy_title = sprintf(__n('%s repost', '%s reposts', $post['Post']['repost_count'],true), $this->MzNumber->counterToReadableSize($post['Post']['repost_count']));?>
                    <li class="reposts tt-title" title="<?php echo $tipsy_title;?>"><?php echo $this->MzNumber->counterToReadableSize($post['Post']['repost_count']);?></li>
				</ul>
		
				<ul class="social-links">
                <?php if($article_belongs_to_user == false): ?>
                    <?php if($article_reposted_by_user == true):?>
                         <li><?php echo $this->Html->link('<span class="repost-ico icon"></span>'.__('Undo repost', true), array('controller' => 'posts','action' => 'undoRepost', $post['Post']['id']),array('class' => 'btn', 'escape' => false));?></li>
                    <?php else:?>
                        <?php
                        //if the user has one or more topics, no href. in this case, the link will be observed and a popup comes
                        $link = '/posts/repost/'. $post['Post']['id'];
                        $class = 'class="btn"';
                        if($has_topics){
                            $link = '#';
                            $class = 'class="repost btn"';
                        }
                        ?>
                        <li><a href="<?php echo $link; ?>" <?php echo $class; ?> id="<?php echo $post['Post']['id']; ?>"><span class="repost-ico icon"></span><?php __('Repost'); ?></a></li>
                    <?php endif;?>
                <?php endif;?>
				<?php if($session->read('Auth.User.id') != null && $post['Post']['user_id'] == $session->read('Auth.User.id')): ?>
                    <li><?php echo $this->Html->link(__('Delete', true), array('controller' => 'posts',  'action' => 'delete', $post['Post']['id']), null, sprintf(__('Are you sure you want to delete your post: %s?', true), $post['Post']['title'])); ?></li>
					<li><?php echo $this->Html->link(__('Edit', true), array('controller' => 'posts',  'action' => 'edit', $post['Post']['id'])); ?></li>
				<?php endif; ?>
				</ul><!-- / .social-links -->
			
		</div><!-- / .article-nav -->

		<div class="articleview-wrapper">
			<div class="articleview">
			<p><strong><?php echo __('posted', true).' '.$this->MzTime->timeAgoInWords($post['Post']['created'], array('format' => 'd.m.y  h:m','end' => '+1 Month'));?></strong></p>
			<h2><?php echo $post['Post']['title'];?></h2>
                
            <?php if(!empty($first_paragraph)):?>
                <div class="first-paragraph" ><?php echo $first_paragraph;?></div>
            <?php endif;?>

            <?php if($images != false && isset($images) && count($images) > 0):?>
					<span class="main-article-imgs">
                        <?php echo $this->element('posts/horizontal_image_scroll', array('images' => $images)); ?>
                    </span>
			<?php endif;?>


            <?php echo $content_after_first_paragraph;?>
            <?php if(isset($post['Post']['links']) && !empty($post['Post']['links'])):?>
                <div style="clear:both;"></div>

                <?php  $links = unserialize($post['Post']['links'])?>
                <h6><?php echo __n('Reference', 'References', count($links, true)); ?></h6>

                <ul id="links">
                        <?php foreach($links as $link):?>
                    <li><?php echo $this->Html->link($link, $link, array('target'  =>'blank','rel' => 'nofollow'));?></li>
                    <?php endforeach;?>

                    </ul>
                <?php endif;?>



            <hr />
            </div><!-- /. articleview -->

            <?php echo $this->element('global/social/icons'); ?>


			<?php //  if($post['Post']['allow_comments'] == PostsController::ALLOW_COMMENTS_TRUE || ($post['Post']['allow_comments'] == PostsController::ALLOW_COMMENTS_DEFAULT && $user['Setting']['user']['privacy']['allow_comments']['value'] == true)):?>
			<div class="comments" style="clear:both">
				<?php // Comment Input Box?>

                <?php echo $this->element('comments/add', array('post_id' => $post['Post']['id'], 'post_owner_id' => $post['Post']['user_id'])); ?>

				<?php // Comments Pagination?>
				<?php echo $this->element('comments/navigator'); ?>
			</div> <!-- / .comments -->
			<?php  // endif;?>
		</div> <!-- /. articleview-wrapper -->							
	
	</div><!-- / #maincol -->

</div><!-- / #maincolwrapper -->