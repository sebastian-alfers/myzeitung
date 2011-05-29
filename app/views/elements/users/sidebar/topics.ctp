			<?php if(count($user['Topic']) > 0): ?>
			<h6><?php echo __('Show Posts by Topic', true);?></h6>
			<ul>
				<li>
				<?php //show only links for not selected items when being in blog overview?>
				<?php if(($this->params['controller'] == 'users' && isset($this->params['pass'][1])) || $this->params['controller'] != 'users'):?>
					<?php /* no topic selected */ echo $this->Html->link(__('All Posts'.' ('.$user['User']['post_count'].')', true), array('controller' => 'users',  'action' => 'view', $user['User']['id'])); ?>
				<?php else:?>
					<i><?php /* topic selected - show link*/ echo __('All Posts'.' ('.$user['User']['post_count'].')', true);?></i>
				<?php endif;?> </li>
        			<?php foreach($user['Topic'] as $topic):?>
        		<li>	
        	    <?php  if(($this->params['controller'] == 'users' && isset($this->params['pass'][1]) && $this->params['pass'][1] != $topic['id']) || $this->params['controller'] != 'users' || !isset($this->params['pass'][1])):?>
        	 			<?php /* this topic is not selected - show link */ echo $this->Html->link($topic['name'].' ('.$topic['post_count'].')', array('controller' => 'users',  'action' => 'view', $user['User']['id'], $topic['id'])); ?> 
        	 		<?php else:?>
        	 			<i><?php  /* this topic is selected - show text*/ echo $topic['name'].' ('.$topic['post_count'].')'?></i>
        	 		<?php endif;?>
        	 		</li>
      		  		<?php endforeach;?>
      		  	</ul>
				<hr />
  				  <?php endif; ?>