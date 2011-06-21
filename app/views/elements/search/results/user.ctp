<li class="type-user">
<?php
	if(isset($user->user_image)){
		$img = unserialize($user->user_image);
	}
	else{
		$img = '';
	}
	$link_data = array();
	$link_data['url'] = array('controller' => 'users', 'action' => 'view', $user->id);
	//$link_data['additional'] = array('style' => 'display:inline;overflow:hidden;height:58px;width:58px;');				
	echo $image->render(array('image' => $img), 58, 58,null, $link_data);
				 				
?>									

	<h3><?php echo $user->user_username;?></h3>
	<p><?php echo $user->user_name;?></p>								
	<div class="actions">
		<?php echo $this->Html->link('<span class="send-icon"></span>'.__('Send Message', true), array('controller' => 'conversations', 'action' => 'add', $user->id), array('escape' => false, 'class' => 'btn btn-send', ));?>
	</div>
	<p class="from"><strong><?php echo __('Author', true);?></strong></p>

</li> <!-- /.type-user -->