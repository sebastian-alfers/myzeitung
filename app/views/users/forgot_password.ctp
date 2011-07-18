<script type="text/javascript">
<!--
$(document).ready(function() {
	$("#link_send_new_password").click(function(){

			$('#forgot-password-form').submit();


	});	

});
//-->
</script>
				<div id="maincolwrapper" class="onecol"> 
					<div id="maincol">
						<div class="register">
							<div class="register-info">
								<h2><?php echo __('I forgot my password', true);?></h2>
								</div>
							<?php echo $this->Form->create('User', array('id' => 'forgot-password-form', 'controller' => 'users', 'action' => 'forgotPassword',
																		'inputDefaults' => array('error' => false, 'div' => false)));?>

							<div><?php  echo $this->Form->input('email', array('type' => 'text', 'class' => 'textinput', 'label' => __('E-Mail', true))); ?>
							<?php if(!is_null($this->Form->error('User.email'))): ?>
								<div class="error-message"><b></b><?php echo $this->Form->error('User.email', array('wrap'=> false));?></div>
							<?php endif; ?>
							</div>
								
								<div class="accept">
									<a class="btn big" id="link_send_new_password"><span>+</span><?php echo __('Send new password', true);?></a>
								</div>
								
								<?php echo $this->Form->end(array('div' => false,'class' => 'hidden')); ?>	
						
						</div> <!-- /register -->

					</div><!-- / #maincol -->
				
				</div><!-- / #maincolwrapper -->