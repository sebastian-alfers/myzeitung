<div id="header">
	<h1 id="logo"><?php echo $this->Html->link('myZeitung - Admin', array('controller' => 'home', 'action' => 'index', 'admin' => false));?></h1>
		
			<?php if($session->read('Auth.User.id')):  ?>

			<div id="user-info">
			<?php echo __("logged in as", true)." "; ?><?php echo "<strong>".$this->Html->link($session->read('Auth.User.username'), array('controller' => 'users', 'action' => 'view', 'username' =>  strtolower($session->read('Auth.User.username'))))."</strong> ";?> | <?php echo $this->Html->link(__('logout', true), array('controller' => 'users' , 'action' => 'logout', 'admin' => false)); ?>

			<?php   //end logged in?>
			<?php else: //not logged in?>
				<div id="user-info" class="not-loggedin">
				<?php 
				echo __("You already have an account?", true);
				echo $this->Html->link(__("Login", true),
				array('controller' => 'users', 'action' => 'login'), array('class' => 'btn')); 
				echo __("No?", true);
				echo $this->Html->link(__("Register", true),
				array('controller' => 'users', 'action' => 'add'), array('class' => 'btn btn-register'));
				?>
			<?php endif; //end not logged in? ?>
		</div> <!-- /#user-info -->
		
		<div id="mainnav">
			<ul>
                <li></li>
			</ul>
		</div>
		
		<div id="nav">

			<?php if($session->read('Auth.User.id')):?>
			<div id="user-nav">
                <?php if($is_superadmin): ?>
                    <ul>
                        <li><?php echo $this->Html->link(__('Search', true), array('controller' => 'search', 'action' => 'index'));?></li>
                        <li><?php echo $this->Html->link(__('Data Associations', true), array('controller' => 'index', 'action' => 'index'));?></li>
                         <li><?php echo $this->Html->link(__('Papers', true), array('controller' => 'papers', 'action' => 'index'));?></li>
                         <li><?php echo $this->Html->link(__('Posts', true), array('controller' => 'posts', 'action' => 'index'));?></li>
                         <li><?php echo $this->Html->link(__('Users', true), array('controller' => 'users', 'action' => 'index'));?></li>
				    </ul>
                <?php endif; ?>
				<ul>
                     <li><?php echo $this->Html->link(__('Complaints', true), array('controller' => 'complaints', 'action' => 'index'));?></li>
				</ul>
			</div><!-- / #user-nav -->
			<?php endif;?>
			
		</div><!-- / #nav --> 
</div><!-- / #header -->