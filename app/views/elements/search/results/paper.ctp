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
	$link_data['url'] = array('controller' => 'papers', 'action' => 'view', $paper->id);				
	echo $image->render(array('image' => $img), 58, 58,array(), $link_data, 'paper');
				 				
?>		
</div>
<div class="left">
	<h3><?php echo $paper->paper_title;?></h3>
	<p><?php echo $paper->paper_description;?></p>
	<p class="from"><strong><?php echo __('Paper', true);?></strong> <?php echo __('from', true);?> <a><strong><?php echo $paper->user_username;?></strong></a> &#8212; <?php echo $paper->user_name;?></p>
	<div class="actions">	
		<?php /*?><strong>20320 Leser</strong><?php */?>
		<?php //echo $this->Html->link('<span>+</span>'.__('Subscribe', true), array('controller' => 'papers', 'action' => 'subscribe', $paper->id), array('escape' => false, 'class' => 'btn', ));?>
						<?php 
						//SUBSCRIBE BUTTON - just if user is not the owner of the paper 
						if(($session->read('Auth.User.id') != $paper->user_id) && ($paper->paper_subscribed == false)){
								echo $this->Html->link('<span>+</span>'.__('Subscribe', true), array('controller' => 'papers', 'action' => 'subscribe', $paper->id), array('escape' => false, 'class' => 'btn', ));
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