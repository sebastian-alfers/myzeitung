<?php 
/**
 * @param $user_documents - array of type Apache_Solr_Document Object
 */
?>

<?php if(isset($user_documents)): ?>
	<ul>
	<?php foreach($user_documents as $user): ?>
		<?php if($user instanceof Apache_Solr_Document): ?>
			<li class="user art">
				<?php
				if(isset($user->user_image)){
					$img = unserialize($user->user_image);
				}
				else{
					$img = '';
				}
				$link_data = array();
				$link_data['url'] = array('controller' => 'users', 'action' => 'view', 'username' => strtolower($user->user_username));
				$link_data['additional'] = 'display:inline;';
				echo $image->render(array('image' => $img), 45, 45,null, $link_data, ImageHelper::USER);
				 				
				?>									
				<h6><?php echo $this->Html->link( $user->user_username, array('controller' => 'users', 'action' => 'view', 'username' => strtolower($user->user_username))) ?></h6>
				<?php if(isset($user->user_name) && !empty($user->user_name)): ?>

					<p><?php echo $this->Html->link( $this->Text->truncate($user->user_name, 20,array('ending' => '...', 'exact' => true, 'html' => false)), array('controller' => 'users', 'action' => 'view', 'username' => strtolower($user->user_username))) ?></p>

                <?php else: ?>
                    <div style="height:20px"></div>
				<?php endif; ?>
			</li>						
		<?php endif; ?>			
	<?php endforeach; ?>
	</ul>
<?php endif; ?>

