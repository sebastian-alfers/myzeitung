
<?php echo $this->element('posts_navigator'); ?>	
<div class="actions">
	<?php echo $this->element('navigation'); ?>	

		<?php if($session->read('Auth.User.id') != null):?>
		<?php //if logged in?>
		<h3><?php __('Options'); ?></h3>
		<ul>
        <li><?php echo $this->Html->link(__('New Post', true), array('controller' => 'posts',  'action' => 'add')); ?> </li>
		</ul>	
        <?php endif;?>

</div>