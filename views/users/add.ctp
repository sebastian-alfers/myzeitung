<?php //kommentar fŸr tim: #link_add_user bezieht sich auf eine "id" wŠhrend sich .link_add_user oder nur link_add_user auf eine klasse bezogen hŠtte
	 //"ready" meint, dass das erst gilt, sobald die seite geladen ist.?>
<script type="text/javascript">
<!--
$(document).ready(function() {	

	$("#link_add_user").click(function(){
		
		if($('#accept_conditions').attr('checked') == true){
			$('#register-form').submit();
		}
		else{
			alert('please confirm Terms and Conditions');
		}
	});	

});
//-->
</script>
				<div id="maincolwrapper" class="onecol"> 
					<div id="maincol">
						<div class="register">
							<div class="register-info">
								<h2><?php echo __('Create a new account', true);?></h2>
								<p><?php echo __('You already have an account?', true);?>&nbsp;<?php echo $this->Html->link('Click here', array('controller' => 'users', 'action' => 'login'));?></p>
							</div>
							<?php echo $this->Form->create('User', array('id' => 'register-form', 'controller' => 'users', 'action' => 'add'));?>
							<p><?php  echo $this->Form->input('username', array('type' => 'text', 'class' => 'textinput', 'div' => false,'label' => __('Username', true))); ?></p>
							
							<p class="optional info-p"><?php  echo $this->Form->input('name', array('type' => 'text', 'class' => 'textinput', 'div' => false,'label' => __('Name', true))); ?>
							<span class="info"><?php echo __('(optional)', true);?>&nbsp;<?php echo __('If you want to be found by your name', true);?></span>
							</p>
							
							<p><?php  echo $this->Form->input('passwd', array('type' => 'password', 'class' => 'textinput', 'div' => false,'label' => __('Password', true))); ?></p>
							
							<p><?php  echo $this->Form->input('email', array('type' => 'text', 'class' => 'textinput', 'div' => false,'label' => __('E-Mail', true))); ?></p>
							
							
								<p class="agbs"><label for="agbs"><?php echo __('TOS', true);?></label>
								
								<textarea name="agbs" readonly="readonly">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy.
								
Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy.
								
Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy.
								</textarea>
								</p>
								
								<div class="accept">
									<p><input type="checkbox" id="accept_conditions"/> <strong><?php echo __('I accept the',true )?>&nbsp;<a href="" target="_blank"><?php echo __('TOS', true);?></a>&nbsp;<?php echo __('and', true)?> <a href="" target="_blank"><?php echo __('Privacy Policy', true);?></a></strong></p>					
									<a class="btn big" id="link_add_user"><span>+</span><?php echo __('Create Account', true);?></a>
								</div>
								
								<?php echo $this->Form->end(); ?>	
						
						</div> <!-- /register -->

					</div><!-- / #maincol -->
				
				</div><!-- / #maincolwrapper -->