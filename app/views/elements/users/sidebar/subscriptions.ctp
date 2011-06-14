
			<hr />
			<h6><?php echo __('Filter Papers', true);?></h6>
			<ul>
				<li>
				<?php //show only links for not selected items?>
				<?php if($this->params['controller'] == 'users' && $this->params['action'] == 'viewSubscriptions' && isset($this->params['pass'][1])):?>
					<?php /*  filter selected - show link*/ echo $this->Html->link(__('All Papers', true), array('controller' => 'users',  'action' => 'viewSubscriptions', $user['User']['id'])); ?>
				<?php else:?>
					<i><?php /* no filter selected */ echo __('All Papers', true);?></i>
				<?php endif;?> </li>

        		<li>	
        	    <?php  if(($this->params['controller'] == 'users' && $this->params['action'] == 'viewSubscriptions' && isset($this->params['pass'][1]) && $this->params['pass'][1] == false || !isset($this->params['pass'][1]))):?>
        	 			<?php /* this topic is not selected - show link */ echo $this->Html->link($user['User']['username'].'\'s '.__('Papers', true), array('controller' => 'users',  'action' => 'viewSubscriptions', $user['User']['id'], 1)); ?> 
        	 		<?php else:?>
        	 			<i><?php  /* this topic is selected - show text*/ echo $user['User']['username'].'\'s '.__('Papers', true);?></i>
        	 		<?php endif;?>
        	 	</li>
        	 	<li>
        	 	    <?php  if(($this->params['controller'] == 'users' && $this->params['action'] == 'viewSubscriptions' && isset($this->params['pass'][1]) && $this->params['pass'][1] == true || !isset($this->params['pass'][1]))):?>
        	 			<?php /* this topic is not selected - show link */ echo $this->Html->link($user['User']['username'].'\'s '.__('Subscriptions', true), array('controller' => 'users',  'action' => 'viewSubscriptions', $user['User']['id'], 0)); ?> 
        	 		<?php else:?>
        	 			<i><?php  /* this topic is selected - show text*/ echo $user['User']['username'].'\'s '.__('Subscriptions', true);?></i>
        	 		<?php endif;?>
        	 	</li>

      		  </ul>
