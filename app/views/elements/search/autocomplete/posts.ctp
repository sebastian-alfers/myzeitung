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
				//$link_data['additional'] = 'display:block;';
				echo '<div style="float:left;margin:0 10px 0 10px;height:45px;width:45px;overflow:hidden">'.$image->render(array('image' => $img), 45, 45,array(), $link_data, 'post').'</div>';
                // post headline
                                    ?>

				<h6><a href="<?php echo $post->route_source?>"><?php echo $this->MzText->truncate($post->post_title, 25,array('ending' => '...', 'exact' => true, 'html' => false)); ?></a></h6>

				<br />
				<span class="from"><?php __('by');?><?php echo ' '.$this->Html->link($this->MzText->generateDisplayname(array('username' =>$post->user_username, 'name' => $post->user_name),false) ,array('controller' => 'users','action' => 'view', 'username' => strtolower($post->user_username)));?><?php /*echo $this->MzTime->timeAgoInWords($post->timestamp);*/ ?></span>
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
