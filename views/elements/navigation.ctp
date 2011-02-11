<ul>
<?php if($isMyProfile): ?>  
        <li><?php echo $this->Html->link(__('Edit User', true), array('controller' => 'users', 'action' => 'edit', $user['User']['id'])); ?> </li>   
        <li><?php echo $this->Html->link(__('Delete User', true), array('action' => 'delete', $user['User']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $user['User']['id'])); ?> </li>
<?php endif; ?>  
        <li><?php echo $this->Html->link(__('List Users', true), array('action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('List Posts', true), array('controller' => 'posts', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Post', true), array('controller' => 'posts', 'action' => 'add')); ?> </li>
        <li><?php echo $this->Html->link(__('List Topics', true), array('controller' => 'topics', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Topic', true), array('controller' => 'topics', 'action' => 'add')); ?> </li>
</ul>