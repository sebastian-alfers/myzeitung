<li class="type-article">
<div class="left image">
<?php
//defining variables relevant for the repost / undo repost button
	$article_reposted_by_user = false;
	$article_belongs_to_user = false;
	if(is_array($post->post_reposters) && in_array($session->read('Auth.User.id'),$post->post_reposters)){
		$article_reposted_by_user = true;
	}
	if($session->read('Auth.User.id') == $post->user_id){
		$article_belongs_to_user = true;
		//just if a user could somehow repost his own post
		$article_reposted_by_user = false;
	}




	if(isset($post->post_image) and !empty($post->post_image)){
		$img = unserialize($post->post_image);
	}
	else{
		$img = '';
	}

	$link_data = array();
	$link_data['url'] = array('controller' => 'posts', 'action' => 'view', $post->id);
	echo $image->render(array('image' => $img), 58, 58,array("alt" => $post->post_title), $link_data, 'post');
    // post headline
    $headline = substr($post->post_title,0,60);
    if(strlen($post->post_title) > 60){
    $headline .='...';
    }
    $content_preview= substr($post->post_content_preview,0,150);
    if(strlen($post->post_content_preview) > 150){
    $content_preview .='...';
    }
    //$content_preview =
?>
    </div>
<div class="left">
	<h3><?php echo $headline;?></h3>
	<p><?php echo $content_preview;?></p>
	<p class="from"><strong><?php echo __('Post', true);?></strong> <?php echo __('from', true);?> <a><strong><?php echo $post->user_username;?></strong></a> &#8212; <?php echo $post->user_name?></p>
	

	<div class="actions">	
		<ul class="iconbar">
			<li class="reposts"><?php echo $post->post_posts_user_count;?></li>
			<li class="views"><?php echo $post->post_view_count;?></li>
			<li class="comments"><?php echo $post->post_comment_count;?><span>.</span></li>							
		</ul>
		<?php if(!$article_belongs_to_user):?>
			<?php if($article_reposted_by_user):?>
		 	<?php //undo repost button?>
			<?php echo $this->Html->link('<span class="repost-ico icon"></span>'.__('undo Repost', true), array('controller' => 'posts', 'action' => 'undoRepost', $post->id), array('escape' => false, 'class' => 'btn', ));?>
			<?php else:?>
			<?php echo $this->Html->link('<span class="repost-ico icon"></span>'.__('Repost', true), array('controller' => 'posts', 'action' => 'repost', $post->id), array('escape' => false, 'class' => 'btn', ));?>
			<?php //repost button?>
			<?php endif;?>
		<?php endif;?>
	</div>
    </div>
</li> <!-- /.type-article -->	