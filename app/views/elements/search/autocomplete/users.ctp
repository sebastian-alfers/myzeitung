<?php 
/**
 * @param $user_documents - array of type Apache_Solr_Document Object
 */
?>

<?php if(isset($user_documents)): ?>
	<ul>
	<?php foreach($user_documents as $user): ?>
		<?php if($user instanceof Apache_Solr_Document): ?>
			<li class="user art autoresult">
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
                $link_data['custom'] = array('class' => 'user-image', 'alt' => $this->MzText->getUsername(array('username' => $user->user_username, 'name' => $user->user_name)), 'link' => $this->MzHtml->url(array('controller' => 'users', 'action' => 'view', 'username' => strtolower($user->user_username))), 'rel' => $this->MzText->getSubscribeUrl(), 'id' => $user->id);
				echo '<div>'.$image->render(array('image' => $img), 45, 45,array(), $link_data, ImageHelper::USER).'</div>';
				?>									
				<h6><?php echo $this->Html->link( $user->user_username, array('controller' => 'users', 'action' => 'view', 'username' => strtolower($user->user_username))) ?></h6>
				<?php if(isset($user->user_name) && !empty($user->user_name)): ?>

					<p><?php echo $this->Html->link( $this->MzText->truncate($user->user_name, 20,array('ending' => '...', 'exact' => true, 'html' => false)), array('controller' => 'users', 'action' => 'view', 'username' => strtolower($user->user_username))) ?></p>

                <?php else: ?>
                    <div style="height:0px"></div>
				<?php endif; ?>
			</li>						
		<?php endif; ?>			
	<?php endforeach; ?>
	</ul>
<?php endif; ?>

