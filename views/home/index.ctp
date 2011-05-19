	<div class="start-header">
			<div id="header">
				<h1 id="logo"><a href="article-overview.html">myZeitung</a></h1>
				<p class="slogan">Mit <span>myZeitung</span> bist du immer gut Informiert.</p>
			
			
			<?php  echo $this->Form->create('User', array('id' => 'login-form', 'controller' => 'users', 'action' => 'login')); ?>
			<?php  echo $this->Form->input('username', array('class' => 'textinput-login', 'div' => false,'label' => false)); ?>
			<?php  echo $this->Form->input('password', array('class' => 'textinput-login', 'div' => false, 'label' => false)); ?>
			<?php  echo $this->Form->button('Login', array('type' => 'submit' ,'class' => 'submit btn', 'div' => false, 'label' => false)); ?>
					<div class="remember">
			<?php  echo $this->Form->input('auto_login', array('type' => 'checkbox', 'class' => 'checkbox' , 'div' => false, 'label' => false)); ?>
					<span class="stay"><?php echo __('Remember Me', true);?>	</span>
					</div>
					<?php 	echo $this->Form->end(); ?>

					
					<div id="mainnav">
					
						<form id="search" action="" class="">
							<input class="searchinput" type="text" onblur="if (this.value == '') {this.value = 'Suchen';}" onfocus="if (this.value == 'Suchen') {this.value = '';}" value="Suchen" />
							<button class="submit" type="submit" value="">Suchen</button>
						</form>
					</div>

			</div><!-- / #header -->
		</div>	<!-- /.start-header -->
	
		<div id="main-wrapper">

			
			<div id="content">
				
				<div id="maincolwrapper" class="onecol start"> 
					<div id="maincol">
					
					<div class="col1">
						<h3><?php echo __('Top Papers', true);?></h3>
							<ul>
							  
							<?php foreach($papers as $paper):?>
								<li>
									<?php echo $this->Html->image("../img/news-image-big.jpg", /* @todo ALF hier bild tauschen */
													array('alt' => $paper['Paper']['title'], 
													  'url' => array('controller' => 'papers', 'action' => 'view', $paper['Paper']['id'] )))?>
							    </li>
				
							<?php endforeach;?>
							</ul>
						<hr />			
						<h3>Top Autoren</h3>
							<ul>
							
							<?php foreach($users as $user):?>
								<li>
									<?php echo $this->Html->image("../img/default-user-image-big.jpg", /* @todo ALF hier bild tauschen */
													array('alt' => $user['User']['username'], 
													  'url' => array('controller' => 'users', 'action' => 'view', $user['User']['id'] )))?>
													  <span><?php echo $this->Html->link($user['User']['username'], array('controller' => 'users', 'action' => 'view', $user['User']['id']));?></span>
							    </li>
							<?php endforeach;?>
						</ul>
								
					</div><!-- /.col1 -->
					
					<div class="col2">
						<h3><?php echo __('Newest Articles',true);?></h3>
							
							<?php foreach($posts as $post):?>
								<div class="article"> 
									<?php // post headline?>
									<h5><?php echo $this->Html->link($post['Post']['title'], array('controller' => 'posts', 'action' => 'view', $post['Post']['id']));?></h5>
									<?php // user container?>
									<?php echo $this->Html->link(
											// link content -  image + span with username and name of user 
											$this->Html->image("../img/default-user-image.-small.jpg" /* @todo ALF hier bild tauschen */, array('class' => 'user-image')).'<span>'.$user['User']['username'].'<br />'.$user['User']['name'].'</span>'
											//link options array
											 , array('controller' => 'users', 'action' => 'view',$user['User']['id']), array('class' => "user", 'escape' => false));?>
								</div>
							<?php endforeach;?>

					</div><!-- /.col2 -->
					
					
					<div class="col3">
						<h2>Jetzt anmelden und Artikel abonnieren und eigene Artikel und Zeitungen erstellen!</h2>
						
						<p><strong>Lorem ipsum</strong> dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit </p>
						<?php echo $this->Html->link('<span>+</span>'.__('Register', true), array('controller' => 'users',  'action' => 'add'), array('escape' => false, 'class' => 'big btn', ));?>
						<hr />
						
						
						<h3>Lorem ipsum dolor sit amet, consetetur sadipscing elitr</h3>
						
						<?php echo $this->Html->image('../img/video.jpg');?>
						
					
					</div><!-- /.col3 -->
						
										
					
					</div><!-- / #maincol -->
				
				</div><!-- / #maincolwrapper -->
