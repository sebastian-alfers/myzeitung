<?php echo $this->element('papers_navigator'); ?>	
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
	</ul>	
	<h3><?php __('Filter'); ?></h3>
	<ul>
		        <li><?php echo $this->Html->link(__('All Papers', true), array('controller' => 'users',  'action' => 'viewSubscriptions', $user['User']['id'])); ?> </li>
		        <li><?php echo $this->Html->link(__('Own Papers', true), array('controller' => 'users',  'action' => 'viewSubscriptions', $user['User']['id'], 1)); ?> </li> 
		        <li><?php echo $this->Html->link(__('Subscribed Papers', true), array('controller' => 'users',  'action' => 'viewSubscriptions', $user['User']['id'], 0)); ?> </li> 
	</ul>
</div>

  
