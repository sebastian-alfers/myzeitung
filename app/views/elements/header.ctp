<div id="header">
	<h1 id="logo"><a href="/">myZeitung</a></h1>
		<div id="user-info">
			<?php if($session->read('Auth.User.id')): // logged in??>
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
				<?php echo $this->Html->link(__("register", true),
				array('controller' => 'users', 'action' => 'add'));
				echo " ".__("already have an account?", true)." ";
				echo $this->Html->link(__("login", true),
				array('controller' => 'users', 'action' => 'login')); ?>
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
			<form id="search" action="/search/" method="get">
				<input name="q" id="inputString"  onkeyup="lookup(this.value);" class="searchinput" type="text" onblur="if (this.value == '') {this.value = '<?php echo __('Find', true);?>';}" onfocus="if (this.value == '<?php echo __('Find', true);?>') {this.value = '';}" value="<?php  echo isset($query)? $query:  __('Find', true);?>" />
				<button class="submit" type="submit" value=""><?php echo __('Find', true);?></button>
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
		if(inputString.length == 0) {
			// Hide the suggestion box.
			$('#suggestions').hide();
		} else {
			inputString = $.trim(inputString);
			$.post("<?php echo FULL_BASE_URL.DS.'search/ajxSearch/'?>", {query: ""+inputString+""}, function(data){
				$('#suggestions').show();
				$('#autoSuggestionsList').html(data);
			});
		}
	} // lookup
	
	function fill(thisValue) {
		$('#inputString').val(thisValue);
		setTimeout("$('#suggestions').hide();", 200);
	}

	$(document).keyup(function(e) {
		  if (e.keyCode == 27) { // esc btn 
			  $('#suggestions').hide();
			  $('#inputString').val('<?php __('Find'); ?>');
		   }  
		});	
	</script>			
	
	
			<div class="suggestionsBox" id="suggestions" style="display: none;">
				<?php echo $this->Html->image('assets/upArrow.png', array('style' => 'position: relative; top: -12px; left: 700px;'));?>
				<div class="suggestionList" id="autoSuggestionsList">
					&nbsp;
				</div>

	</div>






                <div id="signin_menu">
                    <ul>
                        <li><?php echo $this->Html->link('My Account', array('controller' => 'users', 'action' => 'view', $session->read('Auth.User.id'))); ?></li>
                    </ul>
                    <ul>
                        <li><?php __('My Papers');?></li>
                    </ul>
                    <ul>
                        <li><?php __('My Posts');?></li>
                    </ul>
                    <ul>
                        <li><?php __('Logout');?></li>
                    </ul>
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
