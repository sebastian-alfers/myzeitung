			<?php if($user['User']['name']):?>
			<p><strong><?php echo $user['User']['name'];?></strong></p>
			<?php endif;?>
			<?php if(!empty($user['User']['description'])):?>
			<p><i><?php echo strip_tags($user['User']['description'])?></i></p>
			<?php endif;?>
			<?php if(!empty($user['User']['url'])):?>
			<p class="user-url"><strong>URL: </strong><?php echo $this->Html->link($user['User']['url'])?></p>
			<?php endif;?>
			<hr />