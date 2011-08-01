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
					$link_data['url'] = array('controller' => 'papers', 'action' => 'view', $paper->id);
					$link_data['additional'] = 'display:inline;';
					echo $image->render(array('image' => $img), 45, 45,null, $link_data, 'paper',ImageHelper::PAPER);
					?>
					<h6><a href="/papers/view/<?php echo $paper->id; ?>"><?php echo $this->Text->truncate($paper->paper_title, 25,array('ending' => '...', 'exact' => true, 'html' => false)); ?></a></h6>
					<p class="discr"><?php __('by'); ?> <a href="/users/view/<?php echo $paper->user_id; ?>"><?php echo $paper->user_username;?></a></p>
				</p></li>				
			<?php endif; ?>			
		<?php endforeach; ?>
	</ul>
<?php endif; ?>
