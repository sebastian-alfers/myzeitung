

<?php echo $this->element('posts_navigator'); ?>	
<?php echo $this->element('userinfo_sidebar'); ?>	



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
		        <li><?php echo $this->Html->link(__('Subscriptions', true), array('controller' => 'users',  'action' => 'viewSubscriptions', $user['User']['id'])); ?></li>
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

  
