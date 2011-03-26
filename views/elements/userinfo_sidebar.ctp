


<div class="actions">

<h2><?php  __('User');?></h2>
	<dl>
		<dt><?php __('User'); ?></dt>
		<dd><?php echo $user['User']['username'];?></dd>
		
		
		<?php if($user['User']['firstname'] or $user['User']['name']){?>
		<dt><?php __('Name'); ?></dt>
		<dd><?php echo $user['User']['firstname'].' '.$user['User']['name']; ?></dd>
		<?php }?>
		
		<dt><?php __('joined'); ?></dt>
		<dd><?php echo $this->Time->timeAgoInWords($user['User']['created'], array('end' => '+1 Year'));?></dd>			
	</dl>
	
</div>

