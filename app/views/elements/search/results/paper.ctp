<li class="type-news">
<div class="left image">
<?php
	if(isset($paper->paper_image)){
		$img = unserialize($paper->paper_image);
	}
	else{
		$img = '';
	}
	$link_data = array();
	$link_data['url'] = $paper->route_source;
	echo $image->render(array('image' => $img), 58, 58,array(), $link_data, 'paper');
	$paper_description = $this->MzText->truncate($paper->paper_description, 75,array('ending' => '...', 'exact' => true, 'html' => false));
?>		
</div>
<div class="left">
	<h3><?php echo $this->Html->link($paper->paper_title,  $paper->route_source);?></h3>
	<p><?php echo $paper_description;?></p>
	    <?php $userLinkText  = $this->MzText->generateDisplayname(array('username' => $paper->user_username, 'name' => $paper->user_name) ,true);?>
  
	<p class="from"><strong><?php echo __('Paper', true);?></strong> <?php echo __('by', true);?> <?php echo $this->Html->link( $userLinkText,array('controller' => 'users', 'action' => 'view', 'username' => strtolower($paper->user_username)),array('escape' => false));?></p>
	<div class="actions">	
		<?php /*?><strong>20320 Leser</strong><?php */?>
		<?php //echo $this->Html->link('<span>+</span>'.__('Subscribe', true), array('controller' => 'papers', 'action' => 'subscribe', $paper->id), array('escape' => false, 'class' => 'btn', ));?>
						<?php 
						//SUBSCRIBE BUTTON - just if user is not the owner of the paper 
						if(($session->read('Auth.User.id') != $paper->user_id) && ($paper->paper_subscribed == false)){
								echo $this->Html->link('<span>+</span>'.__('Subscribe Paper', true), array('controller' => 'papers', 'action' => 'subscribe', $paper->id), array('escape' => false, 'class' => 'btn', ));
						}
					?>
					<?php 
						//UNSUBSCRIBE BUTTON - just if user is not the owner of the paper 
						if(($session->read('Auth.User.id') != $paper->user_id) && ($paper->paper_subscribed == true)){
								echo $this->Html->link('<span>-</span>'.__('Unsubscribe', true), array('controller' => 'papers', 'action' => 'unsubscribe', $paper->id), array('escape' => false, 'class' => 'btn', ));
						}
					?>
	</div>
</div>
</li> <!-- /.type-news -->	