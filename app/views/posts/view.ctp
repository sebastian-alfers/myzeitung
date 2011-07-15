<?php echo $this->element('users/sidebar'); ?>
<?php
// extracting the first paragraph and the rest of the post-content
// important to not copy the <p> </p> tags, because these are already described in the view with a class
$end = strpos($post['Post']['content'], '</p>', 0);
$first_paragraph = substr($post['Post']['content'], 3 , $end+3);
$content_after_first_paragraph = substr($post['Post']['content'], $end+4);
?>
<div id="maincolwrapper">
	<div id="maincol">
		<div class="article-nav">
				<ul class="iconbar">
				    <?php // tt-title -> class for tipsy &&  'title=...' text for typsy'?>
                     <?php $tipsy_title = sprintf(__n('%d repost', '%d reposts', $post['Post']['posts_user_count'],true), $post['Post']['posts_user_count']);?>
                    <li class="reposts tt-title" title="<?php echo $tipsy_title;?>"><?php echo $post['Post']['posts_user_count'];?></li>
                     <?php $tipsy_title = sprintf(__n('%d time viewed', '%d times viewed', $post['Post']['view_count'],true), $post['Post']['view_count']);?>
                    <li class="views tt-title" title="<?php echo $tipsy_title;?>"><?php echo $post['Post']['view_count'];?></li>
                     <?php $tipsy_title = sprintf(__n('%d comment', '%d comments', $post['Post']['comment_count'],true), $post['Post']['comment_count']);?>
                    <li class="comments tt-title" title="<?php echo $tipsy_title;?>"><?php echo $post['Post']['comment_count'];?><span>.</span></li>
				</ul>
		
				<ul class="social-links">
				<li><a class="btn"><span class="repost-ico icon"></span><?php echo __('Repost', true);?></a></li>
				<?php if($session->read('Auth.User.id') != null && $post['Post']['user_id'] == $session->read('Auth.User.id')): ?>
					<li><?php echo $this->Html->link(__('Edit', true), array('controller' => 'posts',  'action' => 'edit', $post['Post']['id'])); ?></li>
				<?php endif; ?>
				</ul><!-- / .social-links -->
			
		</div><!-- / .article-nav -->
		
		<div class="articleview-wrapper">
			<div class="articleview">
			<p><strong><?php echo __('posted', true).' '.$this->Time->timeAgoInWords($post['Post']['created'], array('end' => '+1 Year'));?></strong></p>
			<h1><?php echo $post['Post']['title'];?></h1>
			<?php if(isset ($post['Post']['image'][0]) && !empty($post['Post']['image'][0])):?>
				<?php
				$link_data = array();
				$link_data['url'] = array('controller' => 'users', 'action' => 'view', $user['User']['id']);
				$link_data['additional'] = array('class' => 'user-image');

                $img_details['image'] = $post['Post']['image'][0];

				unset($post['Post']['image'][0]);
				$images = $post['Post']['image'];
  
				?>
				<span class="main-article-imgs">
					<?php echo $image->render($img_details, 300, 200, array("alt" => 'main image')); ?>
                    <?php echo $this->element('posts/horizontal_image_scroll', array('images' => $images)); ?>
				</span>
			<?php endif;?>
			<p class="first-paragraph" ><?php echo $first_paragraph;?></p>
			<?php echo $content_after_first_paragraph;?>

                <?php if(isset($post['Post']['links']) && !empty($post['Post']['links'])):?>
                    <?php $links = unserialize($post['Post']['links'])?>
                    <h6><?php echo __n('Reference', 'References', count($links, true)); ?></h6>
                    <ul>
                        <?php foreach($links as $link):?>
                          <li><?php echo $this->Html->link($link, $link, array('target'  =>'blank','rel' => 'nofollow'));?></li>
                        <?php endforeach;?>

                    </ul>
                <?php endif;?>
            </div><!-- /. articleview -->


			
			<?php if($post['Post']['allow_comments'] == PostsController::ALLOW_COMMENTS_TRUE || ($post['Post']['allow_comments'] == PostsController::ALLOW_COMMENTS_DEFAULT && $user['User']['allow_comments'] == true)):?>
			<div class="comments" style="clear:both">
				<?php // Comment Input Box?>
				<?php if($session->read('Auth.User.id')):?>
					<?php echo $this->element('comments/add', array('post_id' => $post['Post']['id'])); ?>
				<?php endif; ?>
				<?php // Comments Pagination?>
				<?php echo $this->element('comments/navigator'); ?>
			</div> <!-- / .comments -->
			<?php endif;?>
		</div> <!-- /. articleview-wrapper -->							
	
	</div><!-- / #maincol -->

</div><!-- / #maincolwrapper -->