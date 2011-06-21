<?php 
/**
 * @param $post_documents - array of type Apache_Solr_Document Object
 */
?>

<?php if(isset($post_documents)): ?>
	<ul>
	<?php foreach($post_documents as $post): ?>
		<?php if($post instanceof Apache_Solr_Document): ?>
			
			<li class="art">
				<?php 
				if(isset($post->post_image)){
					$img = unserialize($post->post_image);
				}
				else{
					$img = '';
				}				

				$link_data = array();
				$link_data['url'] = array('controller' => 'users', 'action' => 'view', 666);
				$link_data['additional'] = array('style' => 'display:inline;overflow:hidden;height:50px;width:50px;');				
				echo $image->render(array('image' => $img), 50, 50,null, $link_data, 'post');				
				?>
				<h6><a href=""><?php echo $post->post_title;?></a></h6>
				<br />
				<span class="from">von <a><strong><?php echo $post->user_name; ?></strong>, <?php echo $this->Time->timeAgoInWords($post->timestamp); ?></a></span>
				<?php /*
				<ul class="iconbar">
					<li class="reposts tt-title" title="1 repost">1</li>
					<li class="views tt-title" title="200 views">200</li>
					<li class="comments tt-title" title="2 comments">2<span>.</span></li>
				</ul>
				*/ ?>
			</li>			
		<?php endif; ?>			
	<?php endforeach; ?>
	</ul>
<?php endif; ?>
