<?php 
$label = __('Post comment', true);
$inline_form = '';
$inline_textarea = '';
$onClick ='';
$textarea_class = '';
if(isset($class)){
	$label = __('Post reply', true);
	$inline_form = 'style="width:537px;"';
	$inline_textarea = 'style="width:474px"';
	$textarea_class = 'class="reply_text"';
} 
?>

<div class="your-comment">
	<h3><?php echo __('Leave a comment to this Post')?></h3>
	<p class="user-info">

		

		<?php //echo $this->Html->link($this->Html->image($image->resize($session->read('Auth.User.image'), 65, null)), array('controller' => 'users', 'action' => 'view', $session->read('Auth.User.id')), array('escape' => false));?>
		<?php //echo $this->Html->link($session->read('Auth.User.username'), array('controller' => 'users', 'action' => 'view', $session->read('Auth.User.id')));?>

		<?php
		$user = $session->read('Auth.User');
        $link_data['url'] = array('controller' => 'users', 'action' => 'view', $user['id']);
        $link_data['additional'] = array('class' => 'user-image');
        echo $image->render($user, 65, 65, array("alt" => $user['username']), $link_data);

        /*
		if(is_array($img_data)){
							
			//debug($img_data);die();
			//found img in db
			$info = $image->resize($img_data['path'], 65, 65, $img_data['size'], true);
			$img = $this->Html->image($info['path'], array("alt" => $user['username'], 'style' => $info['inline']));
			echo $this->Html->link($img, array('controller' => 'users', 'action' => 'view', $user['id']), array('class' => "user-image", 'escape' => false, 'style' => 'overflow:hidden;height:65px;width:65px;'));
		}
		else{
			//not logged in
			$path = $image->resize($img_data, 65, 65, null, false);
			$img = $this->Html->image($path, array("alt" => $user['username']));							
			echo $this->Html->link($img, array('controller' => 'users', 'action' => 'view', $user['id']), array('class' => "user-image", 'escape' => false));
		}
        */
		?>
	</p>
	
	<form action="" class="leave-comment" <?php echo $inline_form; ?>>
		<label>Kommentar</label>
		<textarea rows="5" cols="10" id="comment_text" <?php echo $textarea_class; ?> <?php echo $inline_textarea; ?>></textarea>
		<a class="btn" id="add_comment"><span>+</span><?php echo $label;?></a>
	</form>
</div> <!-- / .your-comment -->