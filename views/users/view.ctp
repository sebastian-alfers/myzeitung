<div class="users view">

<h2><?php  __('User');?></h2>
	<?php debug($user);?>
	<dl>
		<dt><?php __('User'); ?></dt>
		<dd><?php echo $user['User']['username'];?></dd>
		
		
		<?php if($user['User']['firstname'] or $user['User']['name']){?>
		<dt><?php __('Name'); ?></dt>
		<dd><?php echo $user['User']['firstname'].' '.$user['User']['name']; ?></dd>
		<?php }?>
		
		<dt><?php __('Member since'); ?></dt>
		<dd><?php echo $user['User']['created'];?></dd>			
	</dl>
	
	
</div>


<div class="actions">
	<?php echo $this->element('navigation'); ?>	
	<h3><?php __('Options'); ?></h3>
	<ul>
				<?php if($session->read('Auth.User.id') == $user['User']['id']):?>
				<?php //new post-button if user is on HIS profile?>
		        <li><?php echo $this->Html->link(__('New Post', true), array('controller' => 'posts',  'action' => 'add')); ?> </li>
		        <?php else:?>
		        <?php //subscribe button if user is on someone else's profile?>
		        <li><b>subscribe</b></li>
		        <?php endif;?>
	</ul>	
</div>

  
<div class="related">
	<h3><?php __('Related Posts');?></h3>
	<?php if (!empty($user['Post'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Repost'); ?></th>
		<th><?php __('Author'); ?></th>
		<th><?php __('Title'); ?></th>
		<th><?php __('Content'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Count Views'); ?></th>
		<th><?php __('Count Reposts'); ?></th>
		<th><?php __('Count Comments'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		
		foreach ($user['Post'] as $index => $post):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
			?>
		
		<tr<?php echo $class;?>>
			<td><?php if($post['user_id'] != $user['User']['id']) {echo "repost";}?></td>
			<td><?php echo $this->Html->link($post['User']['username'], array('controller' => 'users','action' => 'view', $post['user_id'])); ?></td>
			<td><?php echo $this->Html->link($post['title'], array('controller' => 'posts', 'action' => 'view', $post['id']));?></td>
			<td><?php echo $post['content'];?></td>
			<td><?php echo $post['created'];?></td>
			<td><?php echo $post['count_views'];?></td>
			<td><?php echo $post['count_reposts'];?></td>
			<td><?php
			//add link to comments page if there are already comments
			if($post['count_comments'] > 0){
				echo $this->Html->link($post['count_comments'], array('controller' => 'comments','action' => 'show', $post['id']));
			} else {
				echo $post['count_comments'];
			}?></td>
			<td class="actions"><?php 
			//if user did not repost a post yet, there will be repost button. otherwise there will be a undoRepost Button
			if(is_array($post['reposters'])){
				if(!in_array($session->read('Auth.User.id'),$post['reposters'])){
					if($session->read('Auth.User.id') != $user['User']['id']){
						//repost button
						echo $this->Html->link(__('Repost', true), array('controller' => 'posts', 'action' => 'repost', $post['id'], '1'));	
					}
				}else{
				//undoRepost button
					echo $this->Html->link(__('undoRepost', true), array('controller' => 'posts','action' => 'undoRepost', $post['id']));
				} 
			}else 
			{
				if($session->read('Auth.User.id') != $user['User']['id']){
					//repost button (if no array ... )
					echo $this->Html->link(__('Repost', true), array('controller' => 'posts','action' => 'repost', $post['id'], '1'));	
				}
				
			}?>
			
				
				<?php
					//show button only if it's the users own post on it's own blog e 
					if(($session->read('Auth.User.id') == $post['user_id']) && ($session->read('Auth.User.id') == $user['User']['id'])){
							echo $this->Html->link(__('Edit', true), array('controller' => 'posts', 'action' => 'edit', $post['id']));
					} ?>
				<?php
					//show button only if it's the users own post on it's own blog e 
					if(($session->read('Auth.User.id') == $post['user_id']) && ($session->read('Auth.User.id') == $user['User']['id'])){
						 echo $this->Html->link(__('Delete', true), array('controller' => 'posts', 'action' => 'delete', $post['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $post['id'])); 
					}?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
	</div>
<?php endif; /* ?>


	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Post', true), array('controller' => 'posts', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Topics');?></h3>
	<?php if (!empty($user['Topic'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Name'); ?></th>
		<th><?php __('User Id'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th><?php __('Enabled'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($user['Topic'] as $topic):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $topic['id'];?></td>
			<td><?php echo $topic['name'];?></td>
			<td><?php echo $topic['user_id'];?></td>
			<td><?php echo $topic['created'];?></td>
			<td><?php echo $topic['modified'];?></td>
			<td><?php echo $topic['enabled'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'topics', 'action' => 'view', $topic['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'topics', 'action' => 'edit', $topic['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'topics', 'action' => 'delete', $topic['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $topic['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Topic', true), array('controller' => 'topics', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<?php */ ?>