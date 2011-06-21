<li class="type-article">
<?php
	if(isset($post->post_image)){
		$img = unserialize($post->post_image);
	}
	else{
		$img = '';
	}
	$link_data = array();
	$link_data['url'] = array('controller' => 'post', 'action' => 'view', $post->id);				
	echo $image->render(array('image' => $img), 58, 58,null, $link_data, 'post');
				 				
?>		
	<h3><?php echo $post->post_title;?></h3>
	<p><?php echo $post->post_content_preview;?></p>
	<p class="from"><strong><?php echo __('Post', true);?></strong> <?php echo __('from', true);?> <a><strong><?php echo $post->user_username;?></strong></a> &#8212; <?php echo $post->user_name?></p>
	
	<?php /*?>	
	<div class="actions">	
		<ul class="iconbar">
			<li class="reposts">1</li>
			<li class="views">200</li>
			<li class="comments">2<span>.</span></li>							
		</ul>
		<a class="btn"><span class="repost-ico icon"></span>Weiterleiten</a>
	</div> <?php */ ?>
</li> <!-- /.type-article -->	