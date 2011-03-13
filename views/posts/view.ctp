<div class="posts view">
<h2><?php  __('Post');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('User'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($post['User']['username'], array('controller' => 'users', 'action' => 'view', $post['User']['id'])); ?>
			&nbsp;
		</dd>
		<?php if((!empty($post['User']['firstname']) || (!empty($post['User']['name'])))):?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php 
				if(!empty($post['User']['firstname'])){
					echo $post['User']['firstname'];echo "&nbsp;";
				}
				if(!empty($post['User']['name'])){
					echo $post['User']['name'];
				}
			 ?>
			&nbsp;
		</dd>
		<?php endif;?>
		
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Topic'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($post['Topic']['name'], array('controller' => 'topics', 'action' => 'view', $post['Topic']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Title'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $post['Post']['title']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Content'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $post['Post']['content']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $post['Post']['modified']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $post['Post']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Count Views'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $post['Post']['count_views']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Count Reposts'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $post['Post']['count_reposts']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Count Comments'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($post['Post']['count_comments'], array('controller' => 'comments', 'action' => 'show', $post['Post']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php 
			//if user did not repost a post yet, there will be repost button. otherwise there will be a undoRepost Button
			if(is_array($post['Post']['reposters'])){
				if(!in_array($session->read('Auth.User.id'),$post['Post']['reposters'])){
					if($session->read('Auth.User.id') != $post['Post']['user_id']){
						//repost button
						echo $this->Html->link(__('Repost', true), array('controller' => 'posts', 'action' => 'repost', $post['Post']['id'], '1'));	
					}
				}else{
				//undoRepost button
				echo $this->Html->link(__('undoRepost', true), array('action' => 'undoRepost', $post['Post']['id']));
				} 
			}else 
			{
				//repost button (if no array ... )
				echo $this->Html->link(__('Repost', true), array('action' => 'repost', $post['Post']['id'], '1'));	
				
			}
		?></li>
			<?php
					//show button only if it's the users own post on it's own blog e 
					if($session->read('Auth.User.id') == $post['Post']['user_id']){
							echo '<li>'.$this->Html->link(__('Edit', true), array('controller' => 'posts', 'action' => 'edit', $post['Post']['id'])).'</li>';
					} ?>
				<?php
					//show button only if it's the users own post on it's own blog e 
					if($session->read('Auth.User.id') == $post['Post']['user_id']){
						 echo '<li>'.$this->Html->link(__('Delete', true), array('controller' => 'posts', 'action' => 'delete', $post['Post']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $post['Post']['id'])).'</li>'; 
					}?>
		<li><?php echo $this->Html->link(__('Show Comments', true), array('controller' => 'comments', 'action' => 'show', $post['Post']['id'])); ?></li>
	</ul>
</div>
