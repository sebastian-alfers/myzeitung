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
	$link_data['url'] = array('controller' => 'users', 'action' => 'view', 'username' => strtolower($user->user_username));
    $extra = ($user->id == $session->read('Auth.User.id'))? 'me' : '';
	$link_data['custom'] = array('class' => 'user-image ' . $extra, 'alt' => $this->MzText->getUsername(array('username' => $user->user_username, 'name' => $user->user_name)), 'link' => $this->MzHtml->url(array('controller' => 'users', 'action' => 'view', 'username' => strtolower($user->user_username))), 'rel' => $this->MzText->getSubscribeUrl(), 'id' => $user->id);
	echo $image->render(array('image' => $img), 58, 58,array('title' => $user->user_username), $link_data);
				 				
?>									
    </div>
<div class="left">
	<h3><?php echo  $this->Html->link($user->user_username, array('controller' => 'users', 'action' => 'view',  'username' => strtolower($user->user_username)));?></h3>
	<p><?php echo $this->Html->link($user->user_name, array('controller' => 'users', 'action' => 'view', 'username' => strtolower($user->user_username)));?></p>
	<div class="actions">
	<?php if($user->id != $session->read('Auth.User.id') && $user->user_allow_messages == true):?>
        <form action="<?php echo $this->MzHtml->url(array('controller' => 'users', 'action' => 'view', 'username' => strtolower($user->user_username))); ?>" method="post" accept-charset="utf-8"><div><input type="hidden" name="_method" value="POST"></div><input type="hidden" name="action" value="newConversation" />
            <a href="#" class="btn user-button-1 gray user-new-conversation"><span class="send-icon"></span>Send Message</a>
        </form>
	<?php endif;?>

       <a href="<?php echo $subscribe_link; ?>" class="btn user-button-2 subscribe-user" id="<?php echo $user->id; ?>"><span>+</span><?php __(($user->id == $session->read('Auth.User.id'))? 'Subscribe me' : 'Subscribe author'); ?></a>
	</div>
	<p class="from"><strong><?php echo __('Author', true);?></strong></p>

</li> <!-- /.type-user -->
