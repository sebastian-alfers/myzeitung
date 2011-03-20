

<?php echo $this->element('posts_navigator'); ?>	

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
	<h3><?php __('Filter by Topic'); ?></h3>
	<ul>
		        <li><?php echo $this->Html->link(__('All Posts', true), array('controller' => 'users',  'action' => 'view', $user['User']['id'])); ?> </li>
		        
		        <?php if(count($user['Topic']) > 0): ?>
			        <?php foreach($user['Topic'] as $topic):?>
			         	<li><?php echo $this->Html->link($topic['name'], array('controller' => 'users',  'action' => 'view', $user['User']['id'], $topic['id'])); ?> </li>
			        <?php endforeach;?>
			    <?php endif; ?>
	</ul>
</div>

  
