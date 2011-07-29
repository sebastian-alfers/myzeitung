<?php 
/**
 * @param $user_documents - array of type Apache_Solr_Document Object
 */
?>

<?php if(isset($user_documents)): ?>
	<ul>
	<?php foreach($user_documents as $user): ?>
		<?php if($user instanceof Apache_Solr_Document): ?>
			<li class="user">
				<?php
				if(isset($user->user_image)){
					$img = unserialize($user->user_image);
				}
				else{
					$img = '';
				}
				$link_data = array();
				$link_data['url'] = array('controller' => 'users', 'action' => 'view', $user->id);
				$link_data['additional'] = 'display:inline;';
				echo $image->render(array('image' => $img), 45, 45,null, $link_data, ImageHelper::USER);
				 				
				?>									
				<h6><a href="/users/view/<?php echo $user->id; ?>"><?php echo $user->user_username; ?></a></h6>
				<?php if(isset($user->user_name) && !empty($user->user_name)): ?>
					<p><a href="/users/view/<?php echo $user->id; ?>"><?php echo $this->Text->truncate($user->user_name, 20,array('ending' => '...', 'exact' => true, 'html' => false)); ?></a></p>
                <?php else: ?>
                    <div style="height:20px"></div>
				<?php endif; ?>
			</li>						
		<?php endif; ?>			
	<?php endforeach; ?>
	</ul>
<?php endif; ?>

