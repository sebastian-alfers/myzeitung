<?php 
/**
 * @param $user_documents - array of type Apache_Solr_Document Object
 */
?>

<?php if(isset($user_documents)): ?>
<hr />
<h2><?php __('users');?> (<?php echo count($user_documents); ?>)</h2>
	<ul>
	<?php foreach($user_documents as $user): ?>
		<?php if($user instanceof Apache_Solr_Document): ?>
			<li class="art">
				<?php
				if(isset($user->image)){
					$img = $user->image;
					$link_data = array();
					$link_data['url'] = array('controller' => 'users', 'action' => 'view', 666);
					$link_data['additional'] = array('style' => 'display:inline;overflow:hidden;height:50px;width:50px;');				
					echo $image->render(array('image' => unserialize($img)), 50, 50,null, $link_data);
				} 				
				?>									
				<h6><a href=""><?php echo $user->user_username; ?></a></h6>
				<?php if(isset($user->user_username) && !empty($user->user_username)): ?>
					<p><?php echo $user->user_username; ?></p>
				<?php endif; ?>				
			</li>						
		<?php endif; ?>			
	<?php endforeach; ?>
	</ul>
<?php endif; ?>