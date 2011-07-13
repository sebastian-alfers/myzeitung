<div id="header">
	<h1 id="logo"><a href="/">myZeitung - admin</a></h1>
		
			<?php if($session->read('Auth.User.id')): // logged in??>
			<div id="user-info">
			<?php echo __("logged in as", true)." "; ?><?php echo "<strong>".$this->Html->link($session->read('Auth.User.username'), array('controller' => 'users', 'action' => 'view', $session->read('Auth.User.id')))."</strong> ";?> | <a href="/users/logout"><?php __('logout'); ?></a>

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
				        <li><a href="/admin/users/index"><?php __('User Management'); ?></a></li>
				    </ul>
                <?php endif; ?>
				<ul>
				    <li><a href="/admin/complaints/index"><?php __('Complaints'); ?></a></li>
				</ul>
			</div><!-- / #user-nav -->
			<?php endif;?>
			
		</div><!-- / #nav --> 
</div><!-- / #header -->