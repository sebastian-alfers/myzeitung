<li class="type-article">
<div class="left image">
<?php
//defining variables relevant for the repost / undo repost button
    $article_reposted_by_user = false;
	$article_belongs_to_user = false;


	if($this->Reposter->UserHasAlreadyRepostedPost($post->post_reposters,$session->read('Auth.User.id'))){
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
	$link_data['url'] = $post->route_source;
	echo $image->render(array('image' => $img), 58, 58,array("alt" => $post->post_title), $link_data, 'post');
    // post headline
    $headline = $this->MzText->truncate($post->post_title, 55,array('ending' => '...', 'exact' => true, 'html' => false));
    $content_preview= $this->MzText->truncate($post->post_content, 140,array('ending' => '...', 'exact' => true, 'html' => false));

?>
    </div>
<div class="left">
	<h3><?php echo $this->Html->link($headline, $post->route_source);?></h3>
	<p><?php echo $this->Html->link($content_preview,$post->route_source);?></p>
    <?php $userLinkText = $this->MzText->generateDisplayname(array('username' => $post->user_username, 'name' => $post->user_name) ,true);?>
  
	<p class="from"><strong><?php echo __('Post', true);?></strong> <?php echo __('by', true);?> <?php echo $this->Html->link( $userLinkText,array('controller' => 'users', 'action' => 'view', 'username' => strtolower($post->user_username)),array('escape' => false));?></p>
	

	<div class="actions">	
		<ul class="iconbar">
            <?php // tt-title -> class for tipsy &&  'title=...' text for typsy' ?>

                 <?php /* $tipsy_title = sprintf(__n('%d repost', '%d reposts', $post->post_repost_count,true), $post->post_repost_count);?>
                <li class="reposts tt-title" title="<?php echo $tipsy_title;?>"><?php echo $post->post_repost_count;?></li>
                 <?php $tipsy_title = sprintf(__n('%d time viewed', '%d times viewed', $post->post_view_count,true), $post->post_view_count);?>
                <li class="views tt-title" title="<?php echo $tipsy_title;?>"><?php echo $post->post_view_count;?></li>
                 <?php $tipsy_title = sprintf(__n('%d comment', '%d comments', $post->post_comment_count,true), $post->post_comment_count);?>
                <li class="comments tt-title" title="<?php echo $tipsy_title;?>"><?php echo $post->post_comment_count;?><span>.</span></li>
				<?php */ ?>
		</ul>
		<?php if(!$article_belongs_to_user):?>
			<?php if($article_reposted_by_user):?>
		 	<?php //undo repost button?>
			    <?php echo $this->Html->link('<span class="repost-ico icon"></span>'.__('undo Repost', true), array('controller' => 'posts', 'action' => 'undoRepost', $post->id), array('escape' => false, 'class' => 'btn', ));?>
			<?php else:?>
                <?php
                //if the user has one or more topics, no href. in this case, the link will be observed and a popup comes
                $link = '/posts/repost/'. $post->id;
                $class = 'btn';
                if($has_topics){
                    $link = '/#';
                    $class = 'btn repost';
                }
                ?>

                <a href="<?php echo $link; ?>" class="<?php echo $class; ?>" id="<?php echo $post->id; ?>"><span class="repost-ico icon"></span><?php __('Repost'); ?></a>
			<?php //repost button?>
			<?php endif;?>
		<?php endif;?>
	</div>
    </div>
</li> <!-- /.type-article -->	