<script type="text/javascript">
<!--
$(document).ready(function() {	
	$("#link_login").click(function(){
		$('#UserLoginForm').submit();
	});	
});
//-->
</script>

<div id="maincolwrapper" class="onecol"> 
	<div id="maincol">
		<div class="register">
			<div class="register-info">
				<h2><?php echo __('Login', true);?></h2>
			</div>
			<?php echo $this->Form->create('User', array('action' => 'login'));?>
			
			<p><?php  echo $this->Form->input('username', array('type' => 'text', 'class' => 'textinput', 'div' => false,'label' => __('Username or Email', true))); ?></p>
			<p><?php  echo $this->Form->input('password', array('type' => 'password', 'class' => 'textinput', 'div' => false,'label' => __('Password', true))); ?></p>
					
			<div class="accept">	
				<p><?php  echo $this->Form->input('auto_login', array('type' => 'checkbox', 'class' => 'checkbox' , 'div' => false, 'label' => false)); ?><strong><?php echo __('Remember Me', true);?></strong></p>
				<a class="btn big" id="link_login"><span>+</span><?php echo __('Login', true);?></a>
			</div>
			
			<?php echo $this->Form->end(); ?>	
	
		</div> <!-- /register -->

	</div><!-- / #maincol -->

</div><!-- / #maincolwrapper -->