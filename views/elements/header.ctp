<ul>
<?php if($isLoggedIn): ?>
	<li><?php echo $this->Html->link(__('My Account', true), array('controller' => 'users', 'action' => 'view')); ?> </li>
	<li><?php echo $this->Html->link(__('Logout', true), array('controller' => 'users', 'action' => 'logout')); ?> </li>	
<?php else: ?>
    <li><?php echo $this->Html->link(__('Login', true), array('controller' => 'users', 'action' => 'login'));?></li>
    <li><?php echo $this->Html->link(__('Register', true), array('controller' => 'users', 'action' => 'add')); ?> </li>
<?php endif; ?>    
</ul>