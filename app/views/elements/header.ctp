<div id="header">
	<h1 id="logo"><a href="/">myZeitung</a></h1>
		
			<?php if($session->read('Auth.User.id')): // logged in??>
			<div id="user-info">
			<?php echo __("logged in as", true)." "; ?><?php //echo "<strong>".$this->Html->link($session->read('Auth.User.username'), array('controller' => 'users', 'action' => 'view', $session->read('Auth.User.id')))."</strong> ";?>
                        <a href="login" class="signin"><span><strong><?php echo $session->read('Auth.User.username'); ?></strong></span></a>
			<?php
			//echo $this->Html->link($this->Html->image($session->read('Auth.User.image'), array("alt" => $session->read('Auth.User.username')."-image")), array('controller' => 'users', 'action' => 'view', $session->read('Auth.User.id')), array('class' => "user-image", 'escape' => false));
			$user = $session->read('Auth.User');
			$link_data = array();
			$link_data['url'] = array('controller' => 'users', 'action' => 'view', $user['id']);
			$link_data['additional'] = array('class' => 'user-image');
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
				</li><!-- /type-article -->
									
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
				<li><?php echo $this->Html->link(__('My Blog', true), array('controller' => 'users', 'action' => 'view', $session->read('Auth.User.id')));?></li>
				<li><?php echo $this->Html->link(__('Subscriptions', true), array('controller' => 'users', 'action' => 'viewSubscriptions', $session->read('Auth.User.id')));?></li>
				<li><?php echo $this->Html->link(__('Conversations', true), array('controller' => 'conversations', 'action' => 'index', $session->read('Auth.User.id')));?>
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
	
<script>

	function lookup(inputString) {
		if(inputString.length == 0) { // esc btn) {
			// Hide the suggestion box.
			$('#search-suggest').hide();
		} else {
			inputString = $.trim(inputString);
			$.post("<?php echo FULL_BASE_URL.DS.'search/ajxSearch/'?>", {query: ""+inputString+""}, function(data){
				$('#search-suggest').show();
				$('#search-suggest').html(data);
			});
		}
	} // lookup

	$(document).bind('click', function(){
		if($('#search-suggest').is(":visible")){
			hideSuggestion();
		}
		
	});

	$('#inputString').focus(function(e){
		if($('#inputString').val() != '<?php echo __('Find', true);?>') {
			lookup($('#inputString').val());
		}
	});

	$('#inputString').keyup(function(e){
		if (e.keyCode == 27) { // esc btn
			hideSuggestion('');
			$('#inputString').val('');
		}
		else{
			lookup($('#inputString').val());			  
		}
	});
	
	$(document).bind('keyup', function(e){
		  if (e.keyCode == 27) { // esc btn
			  hideSuggestion();
			  $('#inputString').val('');
		   }  
		});	

	function hideSuggestion(value){
		$('#search-suggest').hide();
		lookup('');
		$('#search-suggest').html('');
	}
	</script>			

                <div id="signin_menu">
                    <div style="float:left;width:117px;">
                        <ul>
                            <li><a href="/posts/add"><?php __('New Post'); ?></a></li>
                            <li class="spacer"><a href="/papers/add"><?php __('New Paper'); ?></a></li>
                            <li><a href="/users/view/<?php echo $session->read('Auth.User.id'); ?>"><?php __('my Posts'); ?></a></li>
                            <li><a href="/users/viewSubscriptions/<?php echo $session->read('Auth.User.id'); ?>"><?php __('Subscriptions'); ?></a></li>
                            <?php /*<li><a href="/posts/add"><?php __('my Comments'); ?></a></li> */ ?>
                        </ul>
                    </div>
                    <div>
                        <ul style="float:left">
                            <li><a href="/users/accGeneral"><?php __('Account / Settings'); ?></a></li>
                            <li class="spacer"><a href="/users/logout"><?php __('Logout'); ?></a></li>

                            <?php if($is_admin || $is_superadmin): ?>
                                <li><a href="/admin/admin"><?php __('Admin'); ?></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>


              <script type="text/javascript">
                      $(document).ready(function() {

                          $(".signin").click(function(e) {
                              e.preventDefault();
                              $("div#signin_menu").toggle();
                              $(".signin").toggleClass("menu-open");
                          });

                          $("div#signin_menu").mouseup(function() {
                              return false
                          });
                          $(document).mouseup(function(e) {
                              if($(e.target).parent("a.signin").length==0) {
                                  $(".signin").removeClass("menu-open");
                                  $("div#signin_menu").hide();
                              }
                          });

                      });
              </script>

              <script type='text/javascript'>
                  $(function() {
                    $('#forgot_username_link').tipsy({gravity: 'w'});
                  });
                </script>
