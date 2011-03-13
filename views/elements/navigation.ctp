

<ul>

        <li><?php if($session->read('Auth.User.id') != null){ echo $this->Html->link(__('Edit Profile', true), array('controller' => 'users', 'action' => 'edit', $session->read('Auth.User.id')));} ?> </li>   
       	<li><?php echo $this->Html->link(__('New Paper', true), array('controller' => 'papers', 'action' => 'add')); ?></li>        
 
        <li><?php echo $this->Html->link(__('List Users', true), array('action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('List Posts', true), array('controller' => 'posts', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Post', true), array('controller' => 'posts', 'action' => 'add')); ?> </li>
        <li><?php echo $this->Html->link(__('List Topics', true), array('controller' => 'topics', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Topic', true), array('controller' => 'topics', 'action' => 'add')); ?> </li>
</ul>