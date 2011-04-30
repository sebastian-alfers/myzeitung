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
					<li class="reposts"><?php echo $post['Post']['posts_user_count'];?></li>
					<li class="views"><?php echo $post['Post']['view_count'];?></li>
					<li class="comments"><?php echo $post['Post']['comment_count'];?><span></span></li>								
				</ul>
		
				<ul class="social-links">
				<li><a class="btn"><span class="repost-ico icon"></span><?php echo __('Repost', true);?></a></li>
				</ul><!-- / .social-links -->
			
		</div><!-- / .article-nav -->
		
		<div class="articleview-wrapper">
			<div class="articleview">
			<p><strong><?php echo __('posted', true).' '.$this->Time->timeAgoInWords($post['Post']['created'], array('end' => '+1 Year'));?></strong><?php echo ' '.$post['Post']['comment_count'].' '.__('Comments', true);?></p>
			<h1><?php echo $post['Post']['title'];?></h1>
			<?php if(isset ($post['Post']['image'][0]) && !empty($post['Post']['image'][0])):?>
				<?php 
				$infos = $image->resize($post['Post']['image'][0]['path'], 300, 291, true, true);
				unset($post['Post']['image'][0]);
				$images = $post['Post']['image'];
				
				$rel_path = $infos['path'];  
				?>
				<span class="main-article-imgs"><?php echo $this->Html->image($rel_path);?>
					<?php echo $this->element('posts/horizontal_image_scroll', array('images' => $images, 'width' => $infos['width'])); ?>
				</span>
			<?php endif;?>
			<p class="first-paragraph" ><?php echo $first_paragraph;?></p>
			<?php echo $content_after_first_paragraph;?>			
			</div><!-- /. articleview -->
			<br /><br /><br /><br /><br />
			<div class="comments">
			<?php // Comment Input Box?>
			<?php echo $this->element('comments/add', array('post_id' => $post['Post']['id'])); ?>
			<?php // Comments Pagination?>
			<?php echo $this->element('comments/navigator'); ?>
			</div> <!-- / .comments -->
			
		</div> <!-- /. articleview-wrapper -->							
	
	</div><!-- / #maincol -->

</div><!-- / #maincolwrapper -->