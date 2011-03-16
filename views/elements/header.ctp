
<ul>

<?php if($session->read('Auth.User.id') != null):?>
	<li><?php echo $this->Html->link(__('Logout', true), array('controller' => 'users', 'action' => 'logout'));?></li>
    <li><?php echo $this->Html->link(__('My Profile', true), array('controller' => 'users', 'action' => 'view',$session->read('Auth.User.id') )); ?> </li>

<?php else:?>
    <li><?php echo $this->Html->link(__('Login', true), array('controller' => 'users', 'action' => 'login'));?></li>
    <li><?php echo $this->Html->link(__('Register', true), array('controller' => 'users', 'action' => 'add')); ?> </li>
<?php endif;?>
</ul>
