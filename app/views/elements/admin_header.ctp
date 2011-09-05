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
		<ul class="sf-menu">
			<li class="current">
				<a href="#a"><?php __('Data'); ?></a>
				<ul>
                        <li><?php echo $this->Html->link(__('Comments', true), array('controller' => 'comments', 'action' => 'index'));?></li>
                         <li><?php echo $this->Html->link(__('Papers', true), array('controller' => 'papers', 'action' => 'index'));?></li>
                         <li><?php echo $this->Html->link(__('Posts', true), array('controller' => 'posts', 'action' => 'index'));?></li>
                         <li><?php echo $this->Html->link(__('Users', true), array('controller' => 'users', 'action' => 'index'));?></li>
					<?php /*
                    <li class="current">
						<a href="#ab">menu item</a>
						<ul>
							<li class="current"><a href="#">menu item</a></li>
							<li><a href="#aba">menu item</a></li>
							<li><a href="#abb">menu item</a></li>
							<li><a href="#abc">menu item</a></li>
							<li><a href="#abd">menu item</a></li>
						</ul>
					</li>
					<li>
						<a href="#">menu item</a>
						<ul>
							<li><a href="#">menu item</a></li>
							<li><a href="#">menu item</a></li>
							<li><a href="#">menu item</a></li>
							<li><a href="#">menu item</a></li>
							<li><a href="#">menu item</a></li>
						</ul>
					</li>
					<li>
						<a href="#">menu item</a>
						<ul>
							<li><a href="#">menu item</a></li>
							<li><a href="#">menu item</a></li>
							<li><a href="#">menu item</a></li>
							<li><a href="#">menu item</a></li>
							<li><a href="#">menu item</a></li>
						</ul>
					</li>
                */ ?>
				</ul>
			</li>
			<li>
				<?php echo $this->Html->link(__('Complaints', true), array('controller' => 'complaints', 'action' => 'index'));?>
			</li>
            <?php if($is_superadmin): ?>
			<li>
				<a href="#"><?php __('Admin'); ?></a>
				<ul>
					<li>
						<a href="#"><?php __('Indeces'); ?></a>
						<ul>
							<li><?php echo $this->Html->link(__('Search', true), array('controller' => 'search', 'action' => 'index'));?></li>
                            <li><?php echo $this->Html->link(__('Data Associations', true), array('controller' => 'index', 'action' => 'index'));?></li>
						</ul>
					</li>
					<li>
						<a href="#">menu item</a>
						<ul>
							<li><a href="#">menu item</a></li>
							<li><a href="#">menu item</a></li>
							<li><a href="#">menu item</a></li>
							<li><a href="#">menu item</a></li>
							<li><a href="#">menu item</a></li>
						</ul>
					</li>
					<li>
						<a href="#">menu item</a>
						<ul>
							<li><a href="#">menu item</a></li>
							<li><a href="#">menu item</a></li>
							<li><a href="#">menu item</a></li>
							<li><a href="#">menu item</a></li>
							<li><a href="#">menu item</a></li>
						</ul>
					</li>
					<li>
						<a href="#">menu item</a>
						<ul>
							<li><a href="#">menu item</a></li>
							<li><a href="#">menu item</a></li>
							<li><a href="#">menu item</a></li>
							<li><a href="#">menu item</a></li>
							<li><a href="#">menu item</a></li>
						</ul>
					</li>
				</ul>

			</li>
            <?php endif;?>

        <li>
            <a href="#"><?php __('AWS'); ?></a>
            <ul>
                <li>
                    <a href="#"><?php __('Email'); ?></a>
                    <ul>
                        <li><?php echo $this->Html->link(__('Statistics (quota)', true), array('controller' => 'aws', 'action' => 'ses', 'quota'));?></li>
                        <li><?php echo $this->Html->link(__('Adresses', true), array('controller' => 'aws', 'action' => 'ses', 'adresses'));?></li>
                    </ul>
                </li>
                <li><a href="#"><?php __('Server / Instances'); ?></a>
                    <ul>
                        <li><?php echo $this->Html->link(__('Manage Instances', true), array('controller' => 'aws', 'action' => 'ec2', 'manage'));?></li>
                    </ul>
                </li>
                <li><a href="#"><?php __('Backups'); ?></a></li>
                <li><?php echo $this->Html->link(__('Email', true), array('controller' => 'aws', 'action' => 'email', 's'));?></li>

                <li><a href="#"><?php __('Database'); ?></a></li>
                <li><a href="#"><?php __('Loadbalancer'); ?></a></li>
                <li><a href="#"><?php __('CDN'); ?></a></li>
                <li><a href="#"><?php __('Cronjobs'); ?></a></li>
            </ul>
        </li>
            </ul>

		</div><!-- / #nav --> 
</div><!-- / #header -->