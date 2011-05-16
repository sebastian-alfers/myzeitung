			<?php if($user['User']['name']):?>
			<p><strong><?php echo __('Name:').' '; ?></strong><?php echo $user['User']['name'];?></p>
			<?php endif;?>
			<p><strong><?php echo __('Joined:').' '; ?></strong><?php echo $this->Time->timeAgoInWords($user['User']['created'], array('end' => '+1 Year'));?></p>
			<?php if(!empty($user['User']['description'])):?>
			<p><strong><?php echo __('About me:').' ';?></strong><?php echo strip_tags($user['User']['description'])?></p>
			<?php endif;?>
			<?php if(!empty($user['User']['url'])):?>
			<p class="user-url"><strong>URL: </strong><?php echo $this->Html->link($user['User']['url'])?></p>
			<?php endif;?>
			<hr />