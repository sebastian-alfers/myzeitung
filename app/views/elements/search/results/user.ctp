<li class="type-user">
<div class="left image">
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
	echo $image->render(array('image' => $img), 58, 58,array('title' => $user->user_username), $link_data);
				 				
?>									
    </div>
<div class="left">
	<h3><?php echo $user->user_username;?></h3>
	<p><?php echo $user->user_name;?></p>								
	<div class="actions">
	<?php if($user->id != $session->read('Auth.User.id') && $user->user_allow_messages == true):?>
		<?php echo $this->Html->link('<span class="send-icon"></span>'.__('Send Message', true), array('controller' => 'conversations', 'action' => 'add', $user->id), array('escape' => false, 'class' => 'btn btn-send', ));?>
	<?php endif;?>
	</div>
	<p class="from"><strong><?php echo __('Author', true);?></strong></p>

</li> <!-- /.type-user -->