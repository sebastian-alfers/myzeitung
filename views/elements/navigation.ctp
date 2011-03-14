
	<h3><?php __('Browse'); ?></h3>
		<ul>
		        <li><?php echo $this->Html->link(__('Authors', true), array('controller' => 'users',  'action' => 'index')); ?> </li>
		        <li><?php echo $this->Html->link(__('Posts', true), array('controller' => 'posts', 'action' => 'index')); ?> </li>
		        <li><?php echo $this->Html->link(__('Papers', true), array('controller' => 'papers', 'action' => 'index')); ?> </li>
		</ul>
