<li class="type-news"> 
<?php
	if(isset($paper->paper_image)){
		$img = unserialize($paper->paper_image);
	}
	else{
		$img = '';
	}
	$link_data = array();
	$link_data['url'] = array('controller' => 'papers', 'action' => 'view', $paper->id);				
	echo $image->render(array('image' => $img), 58, 58,null, $link_data, 'paper');
				 				
?>		

	<h3><?php echo $paper->paper_title;?></h3>
	<p><?php echo $paper->paper_description;?></p>
	<p class="from"><strong><?php echo __('Paper', true);?></strong> <?php echo __('from', true);?> <a><strong><?php echo $paper->user_username;?></strong></a> &#8212; <?php echo $paper->user_name;?></p>
	<div class="actions">	
		<?php /*?><strong>20320 Leser</strong><?php */?>
		<?php echo $this->Html->link('<span>+</span>'.__('Subscribe', true), array('controller' => 'papers', 'action' => 'subscribe', $paper->id), array('escape' => false, 'class' => 'btn', ));?>
	</div>
</li> <!-- /.type-news -->	