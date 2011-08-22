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
				$link_data['url'] = $post->route_source;
				$link_data['additional'] = 'display:inline;';
				echo $image->render(array('image' => $img), 45, 45,null, $link_data, 'post');
                // post headline
                                    ?>

				<h6><?php echo $this->Html->link($this->Text->truncate($post->post_title, 25,array('ending' => '...', 'exact' => true, 'html' => false)),$post->route_source);?></h6>
				<br />
				<span class="from"><?php __('by');?><?php echo ' '.$this->Html->link($post->user_username ,array('controller' => 'users','action' => 'view', 'username' => strtolower($post->user_username)));?><?php /*echo $this->MzTime->timeAgoInWords($post->timestamp);*/ ?></span>
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
