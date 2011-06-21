<?php 
/**
 * @param $paper_documents - array of type Apache_Solr_Document Object
 */
?>

<?php if(isset($paper_documents)): ?>
	<ul>
		<?php foreach($paper_documents as $paper): ?>
			<?php if($paper instanceof Apache_Solr_Document): ?>
			<?php //debug($paper); die(); ?>
				<li class="art">
					<?php 
			
					if(isset($paper->paper_image)){
						$img = unserialize($paper->paper_image);
					}
					else{
						$img = '';
					}					
					$link_data = array();
					$link_data['url'] = array('controller' => 'paper', 'action' => 'view', $paper->id);
					$link_data['additional'] = array('style' => 'display:inline;overflow:hidden;height:50px;width:50px;');				
					echo $image->render(array('image' => $img), 50, 50,null, $link_data, 'paper');
					?>
					<h6><a href="/papers/view/<?php echo $paper->id; ?>"><?php echo $paper->paper_title; ?></a></h6>
					<p class="discr"><?php __('by'); ?> <a href="/users/view/<?php echo $paper->user_id; ?>"><?php echo $paper->user_username;?></a></p>
				</p></li>				
			<?php endif; ?>			
		<?php endforeach; ?>
	</ul>
<?php endif; ?>
