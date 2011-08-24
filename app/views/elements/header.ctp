<div id="header">
	<h1 id="logo"><?php echo $this->Html->link('myZeitung', array('controller' => 'home', 'action' => 'index'));?></h1>
			<?php if($session->read('Auth.User.id')): ?>
			<div id="user-info">
			<?php echo __("logged in as", true)." "; ?><?php //echo "<strong>".$this->Html->link($session->read('Auth.User.username'), array('controller' => 'users', 'action' => 'view','username' => $session->read('Auth.User.username ')))."</strong> ";?>
                        <a href="login" class="signin"><span><strong><?php echo $session->read('Auth.User.username'); ?></strong></span></a>
			<?php
			//echo $this->Html->link($this->Html->image($session->read('Auth.User.image'), array("alt" => $session->read('Auth.User.username')."-image")), array('controller' => 'users', 'action' => 'view', 'username' => $session->read('Auth.User.username')), array('class' => "user-image", 'escape' => false));
			$user = $session->read('Auth.User');
			$link_data = array();

			$link_data['url'] = array('controller' => 'users', 'action' => 'view', 'username' => strtolower($user['username']));
            $name = "<h3>".$user['username']."</h3>";
            if(isset($user['username']) && !empty($user['username'])){
               $name .= $user['username'];
            }



			$link_data['custom'] = array('class' => 'user-image', 'alt' => $this->MzText->getUsername($user), 'rel' => $this->MzText->getSubscribeUrl(), 'id' => $user['id']);

			echo $image->render($user, 30, 30, array("alt" => $user['username']), $link_data);

			?>

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
				<?php if($this->params['controller'] == 'posts' && $this->params['action'] == 'index'):?><li class="current"><?php else:?><li><?php endif;?>
				<?php echo $this->Html->link(__('Posts', true), array('controller' => 'posts', 'action' => 'index'));?></li>
				<?php if($this->params['controller'] == 'users' && $this->params['action'] == 'index'):?><li class="current"><?php else:?><li><?php endif;?>
				<?php echo $this->Html->link(__('Authors', true), array('controller' => 'users', 'action' => 'index'));?></li>
				<?php if($this->params['controller'] == 'papers' && $this->params['action'] == 'index'):?><li class="current"><?php else:?><li><?php endif;?>
				<?php echo $this->Html->link(__('Papers', true), array('controller' => 'papers', 'action' => 'index'));?></li>
			</ul>
			<form id="search" action="/search/" method="get" class="jqtransform">
				<input name="q" id="inputString" autocomplete="off" class="searchinput" type="text" onblur="if (this.value == '') {this.value = '<?php echo __('Find', true);?>';}" onfocus="if (this.value == '<?php echo __('Find', true);?>') {this.value = '';}" value="<?php  echo isset($query)? $query:  __('Find', true);?>" />
				<button class="submit" type="submit" value=""><?php echo __('Find', true);?></button>

				<ul id="search-suggest" style="display:none">
				</ul><!-- end auto suggest -->
			</form>
		</div>

		<div id="nav">

			<div id="breadcrumb">
			 	<?php echo $this->element('header/breadcrumbs'); ?>
			</div><!-- / #breadcrumb -->
			<?php if($session->read('Auth.User.id')):?>
			<div id="user-nav">
				<ul>
				<li><?php echo $this->Html->link(__('My Articles', true), array('controller' => 'users', 'action' => 'view', 'username' => strtolower($session->read('Auth.User.username'))));?></li>
				<li><?php echo $this->Html->link(__('My Papers', true), array('controller' => 'users', 'action' => 'viewSubscriptions', 'username' =>strtolower($session->read('Auth.User.username'))));?></li>
				<li><?php echo $this->Html->link(__('Messages', true), array('controller' => 'conversations', 'action' => 'index'));?>
					<?php if($this->Session->read('Auth.User.allow_messages') && isset($conversation_count) && $conversation_count > 0):?>
					<span class="round-icon"><?php echo $conversation_count;?></span>
					<?php endif;?>
				</li>
				<li class="big-btn"><?php echo $this->Html->link('<span>+</span>'.__('New Post', true), array('controller' => 'posts', 'action' => 'add'), array('escape' => false, 'class' => 'btn', ));?></li>
				</ul>
			</div><!-- / #user-nav -->
			<?php endif;?>

		</div><!-- / #nav -->
</div><!-- / #header -->


<div id="signin_menu">
    <div style="float:left;width:117px;">
        <ul>
            <li><?php echo $this->Html->link(__('New Post', true), array('controller' => 'posts' , 'action' => 'add'));?></li>
            <li class="spacer"><?php echo $this->Html->link(__('New Paper', true), array('controller' => 'papers' , 'action' => 'add'));?></li>
            <li><?php echo $this->Html->link(__('My Articles',true), array('controller' => 'users' , 'action' => 'view', 'username' => strtolower($session->read('Auth.User.username'))));?></li>
            <li><?php echo $this->Html->link(__('My Papers', true), array('controller' => 'users' , 'action' => 'viewSubscriptions', 'username' => strtolower($session->read('Auth.User.username'))));?></li>
            <li><?php echo $this->Html->link(__('Messages', true), array('controller' => 'conversations' , 'action' => 'index' ));?></li>
            <?php /*<li><a href="/posts/add"><?php __('my Comments', true); ?></a></li> */ ?>
        </ul>
    </div>
    <div>
        <ul style="float:left">
            <li><?php echo $this->Html->link(__('Account / Settings', true), array('controller' => 'users' , 'action' => 'accAboutMe'));?></li>
            <li class="spacer"><li><?php echo $this->Html->link(__('Logout', true), array('controller' => 'users' , 'action' => 'logout'));?></li>
            <?php if($is_admin || $is_superadmin): ?>
                <li><?php echo $this->Html->link(__('Admin', true), array('controller' => 'admin' , 'action' => 'admin'));?></li>
            <?php endif; ?>

            <?php if(!$this->Session->read('Config.language') || $this->Session->read('Config.language') == '' || $this->Session->read('Config.language') == 'deu'): ?>
                <li><a href="/locale/eng"><?php __('English'); ?></a></li>
            <?php else: ?>
                <li><a href="/locale/deu"><?php __('Deutsch'); ?></a></li>
            <?php endif; ?>
        </ul>
    </div>
</div>




<?php //echo $this->element('search/autocomplete/script'); ?>
